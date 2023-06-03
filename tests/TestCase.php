<?php

namespace Aldeebhasan\LaravelCF\Test;

use Illuminate\Database\Capsule\Manager as DB;
use Illuminate\Database\Schema\Blueprint;
use PHPUnit\Framework\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        $this->createApp();
        $this->migrate();
        $this->seed();
    }

    private function createApp()
    {
        $db = new DB();
        $db->addConnection([
            'driver' => 'sqlite',
            'database' => ':memory:',
        ]);
        $db->setAsGlobal();
        $db->bootEloquent();
    }

    private function migrate()
    {
        DB::schema()->dropAllTables();

        DB::schema()->create('rs_relations', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('source')->index();
            $table->bigInteger('target')->index();
            $table->string('type', 10);
            $table->string('value', 25);
            $table->timestamps();
        });
    }

    private function seed()
    {
        // seed some data if required
    }
}
