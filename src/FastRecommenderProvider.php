<?php

namespace Aldeebhasan\FastRecommender;

use Illuminate\Support\ServiceProvider;

class FastRecommenderProvider extends ServiceProvider
{
    public function boot()
    {
        $this->loadMigrationsFrom(__DIR__.' / …/database/migrations');
    }

    public function register()
    {
    }
}
