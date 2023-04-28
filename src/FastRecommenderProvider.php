<?php

namespace Aldeebhasan\FastRecommender;

use Aldeebhasan\FastRecommender\Components\LottieComponent;
use Illuminate\Support\Facades\Blade;
use Illuminate\Support\ServiceProvider;

class FastRecommenderProvider extends ServiceProvider
{
    public function boot()
    {
        // Views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'lottie');
    }

    public function register()
    {
        $this->app->singleton('lottie', LottieManager::class);
        Blade::component('lottie', LottieComponent::class);
    }
}
