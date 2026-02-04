<?php

namespace Workbench\ContactForm;

use Illuminate\Support\ServiceProvider;

class ContactFormServiceProvider extends ServiceProvider
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
       $this->LoadViewsFrom(__DIR__.'/../resources/views', 'contactform');
       $this->LoadMigrationsFrom(__DIR__.'/../database/migrations');
    }

}
