<?php

namespace Benxmy\SingleUseSignedUrl;

use Illuminate\Support\ServiceProvider;

class LaravelSingleUseSignedUrlServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        /*
         * Optional methods to load your package assets
         */
        $this->loadMigrationsFrom(__DIR__.'/../database/migrations');

        app('router')->aliasMiddleware('validateSingleUseSignedUrl', ValidateSingleUseSignedUrl::class);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration (currently there is not configuration)
        // $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-single-use-signed-url');

        // Register the main class to use with the facade
        $this->app->bind('laravel-single-use-signed-url', function () {
            return new SingleUseSignedUrl;
        });
    }
}
