<?php

namespace BoxedCode\Tests\Eloquent\Meta;

use BoxedCode\Eloquent\Meta\HasMeta;
use BoxedCode\Eloquent\Meta\MetaItem;
use BoxedCode\Tests\Eloquent\Meta\Support\AbstractTestCase;

class MetableTest extends AbstractTestCase
{
    public function testMeta()
    {
        $m = $this->getMetableStub();

        $this->assertInstanceOf(HasMeta::class, $m->meta());
    }

    public function testIsDirtyParent()
    {
        $m = $this->getMetableStub();

        $m->foo = 'bar';

        $this->assertTrue($m->isDirty());

        $this->assertTrue($m->isDirty('foo'));
    }

    public function testIsDirtyMeta()
    {
        $m = $this->getMetableStub();

        $item = new MetaItem(['key' => 'foo', 'value' => 'bar']);

        $m->meta->add($item);

        $this->assertTrue($m->isDirty());

        $this->assertTrue($m->isDirty('value'));
    }

    public function testGetMetaItemClassNameDefault()
    {
        $m = $this->getMetableStub();

        $this->assertSame(MetaItem::class, $m->getMetaItemClassName());
    }

    public function testGetMetaItemClassNameCustom()
    {
        $m = $this->getMetableStub();

        $m->setProperty('metaItemClassname', 'FooClass');

        $this->assertSame('FooClass', $m->getMetaItemClassName());
    }

    public function testGetMetaItemInstance()
    {
        $m = $this->getMetableStub();

        $this->assertInstanceOf(MetaItem::class, $m->getMetaItemInstance());
    }

    public function testHasMeta()
    {
        $m = $this->getMetableStub();

        $has_meta = $m->hasMeta($m->getMetaItemClassName(), 'model', $m->getMetaItemClassName(), 'id', 'id');

        $this->assertInstanceOf(HasMeta::class, $has_meta);
    }

    public function testObserveSaveAndCascadeNewItems()
    {
        $m = $this->createMetableStub();

        $this->assertSame('bar', $m->meta->foo);
    }

    public function testObserveSaveAndCascadeExistingItems()
    {
        $m = $this->createMetableStub();

        $m->meta->foo = 'baz';

        $m->save();

        $m = $m::first();

        $this->assertSame('baz', $m->meta->foo);
    }

    public function testObserveSaveAndCascadeRemoveItems()
    {
        $m = $this->createMetableStub();

        unset($m->meta[0]);

        $m->save();

        $m = $m::first();

        $this->assertCount(0, $m->meta);
    }

    public function testObserveDeleteAndCascade()
    {
        $m = $this->createMetableStub();

        $m->delete();

        $this->assertNull(MetaItem::first());
    }
}
