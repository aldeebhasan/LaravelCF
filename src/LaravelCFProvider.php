<?php

namespace Aldeebhasan\LaravelCF;

use Illuminate\Support\ServiceProvider;

class LaravelCFProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
    }

    public function register()
    {
        $this->app->singleton('recommender', RecommenderManager::class);
    }
}
