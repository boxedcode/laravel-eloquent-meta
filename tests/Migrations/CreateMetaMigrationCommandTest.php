<?php

namespace BoxedCode\Tests\Eloquent\Meta\Types;

use BoxedCode\Tests\Eloquent\Meta\Support\AbstractTestCase;

class CreateMetaMigrationCommandTest extends AbstractTestCase
{
    public function testTableCreation()
    {
        $this->assertTrue(\Schema::hasTable('meta'));

        $this->assertSame([
            'id',
            'key',
            'tag',
            'model_id',
            'model_type',
            'type',
            'value',
            'created_at',
            'updated_at',
        ], \Schema::getColumnListing('meta'));
    }

    public function testCustomModelName()
    {
        static::makeMigration(['model_name' => 'test']);

        $this->assertTrue(\Schema::hasTable('test_meta'));
    }

    public function testCustomPath()
    {
        static::makeMigration(['--path' => 'storage']);

        $migration = head(glob($this->app->storagePath().'/*migration.php'));

        $this->assertFileExists($migration);
    }
}
