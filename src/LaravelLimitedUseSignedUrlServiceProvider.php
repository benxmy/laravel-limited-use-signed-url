<?php

namespace Benxmy\LimitedUseSignedUrl;

use Illuminate\Support\ServiceProvider;

class LaravelLimitedUseSignedUrlServiceProvider extends ServiceProvider
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

        app('router')->aliasMiddleware('validateLimitedUseSignedUrl', ValidateLimitedUseSignedUrl::class);

        $this->publishes([
            __DIR__ .  '../config/config.php' => config_path('limited-use-urls.php'),
        ]);
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration (currently there is not configuration)
        // $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-dual-use-signed-url');

        // Register the main class to use with the facade
        $this->app->bind('laravel-limited-use-signed-url', function () {
            return new LimitedUseSignedUrl;
        });
    }
}
