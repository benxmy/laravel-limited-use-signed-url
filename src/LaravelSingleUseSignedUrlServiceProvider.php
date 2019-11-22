<?php

namespace Intellow\SingleUseSignedUrl;

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
        // $this->loadTranslationsFrom(__DIR__.'/../resources/lang', 'laravel-single-use-signed-url');
        // $this->loadViewsFrom(__DIR__.'/../resources/views', 'laravel-single-use-signed-url');
         $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->loadRoutesFrom(__DIR__.'/routes.php');

        app('router')->aliasMiddleware('validateSingleUseSignedUrl', ValidateSingleUseSignedUrl::class);

        if ($this->app->runningInConsole()) {
//            $this->publishes([
//                __DIR__.'/../config/config.php' => config_path('laravel-single-use-signed-url.php'),
//            ], 'config');

            // Publishing the views.
            /*$this->publishes([
                __DIR__.'/../resources/views' => resource_path('views/vendor/laravel-single-use-signed-url'),
            ], 'views');*/

            // Publishing assets.
            /*$this->publishes([
                __DIR__.'/../resources/assets' => public_path('vendor/laravel-single-use-signed-url'),
            ], 'assets');*/

            // Publishing the translation files.
            /*$this->publishes([
                __DIR__.'/../resources/lang' => resource_path('lang/vendor/laravel-single-use-signed-url'),
            ], 'lang');*/

            // Registering package commands.
            // $this->commands([]);
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
//        $this->mergeConfigFrom(__DIR__.'/../config/config.php', 'laravel-single-use-signed-url');

        // Register the main class to use with the facade
        $this->app->bind('laravel-single-use-signed-url', function () {
            return new SingleUseSignedUrl;
        });
    }
}
