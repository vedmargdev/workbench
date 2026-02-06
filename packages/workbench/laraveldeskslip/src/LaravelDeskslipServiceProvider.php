<?php

namespace Workbench\LaravelDeskslip;

use Illuminate\Support\ServiceProvider;

class LaravelDeskslipServiceProvider extends ServiceProvider
{
    public function register()
    {
        // Merge config
        $this->mergeConfigFrom(__DIR__.'/../config/deskslip.php', 'deskslip');
    }

    public function boot()
    {
        // Routes
        $this->loadRoutesFrom(__DIR__.'/../routes/web.php');

        // Views
        $this->loadViewsFrom(__DIR__.'/../resources/views', 'deskslip');

        // Publish config
        $this->publishes([
            __DIR__.'/../config/deskslip.php' => config_path('deskslip.php'),
        ], 'deskslip-config');

        // Publish views
        $this->publishes([
            __DIR__.'/../resources/views' => resource_path('views/vendor/deskslip'),
        ], 'deskslip-views');
    }
}
