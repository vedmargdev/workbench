<?php

namespace Workbench\OfflineTest;

use Illuminate\Support\ServiceProvider;

class OfflineTestServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }


    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
    $this->LoadRoutesFrom(__DIR__.'/../routes/web.php');
    $this->LoadViewsFrom(__DIR__.'/../resources/views', 'offlinetest');
    $this->LoadMigrationsFrom(__DIR__.'/../database/migrations');
    $this->publishes([
        __DIR__.'/../resources/js' => public_path('vendor/offlinetest/js'),
    ], 'offlinetest-assets');

    // optional: CSS
    $this->publishes([
        __DIR__.'/../resources/css' => public_path('vendor/offlinetest/css'),
    ], 'offlinetest-assets');
      
    require_once __DIR__ . '/helpers.php';

    }
}
