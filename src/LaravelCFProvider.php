<?php

namespace Aldeebhasan\FastRecommender;

use Illuminate\Support\ServiceProvider;

class LaravelCFProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.' / …/database/migrations');
    }

    public function register()
    {
    }
}
