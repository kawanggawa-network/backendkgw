<?php

namespace App\Providers;

use Laravel\Passport\Passport;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Force Scheme URL
        \URL::forceScheme('https');

        // Ignore migrations
        Passport::ignoreMigrations();
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        // Force set carbon locale to Indonesia (ID)
        \Carbon\Carbon::setLocale('id');
    }
}
