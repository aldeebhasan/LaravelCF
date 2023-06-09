<?php

namespace Aldeebhasan\LaravelCF\Test;

use Aldeebhasan\LaravelCF\LaravelCFProvider;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Orchestra\Testbench\TestCase as BaseTestCase;

abstract class TestCase extends BaseTestCase
{
    use DatabaseMigrations;

    protected function setUp(): void
    {
        parent::setUp();
    }

    protected function getPackageProviders($app)
    {
        return [LaravelCFProvider::class];
    }

    protected function getEnvironmentSetUp($app)
    {
        $app['config']->set('database.default', 'testDB');
        $app['config']->set('database.connections.testDB', [
            'driver' => 'sqlite',
            'database' => ':memory:',
            'prefix' => '',
        ]);
    }
}
