<?php

namespace Grayloon\Magento;

use Illuminate\Support\Facades\Route;
use Illuminate\Support\ServiceProvider;

class MagentoServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap the application services.
     */
    public function boot()
    {
        $this->registerRoutes();
        $this->registerPublishing();
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/Http/routes.php');
        });
    }

    /**
     * Get the Telescope route group configuration array.
     *
     * @return array
     */
    private function routeConfiguration()
    {
        return [
            'namespace' => 'Grayloon\Magento\Http\Controllers',
        ];
    }

    /**
     * Register the package's publishable resources.
     *
     * @return void
     */
    private function registerPublishing()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__.'/Database/Migrations' => database_path('migrations'),
            ], 'magento-migrations');

            $this->publishes([
                __DIR__.'/../config/magento.php' => config_path('magento.php'),
            ], 'magento-config');

            $this->publishes([
                __DIR__.'/Database/Factories' => database_path('factories'),
            ], 'magento-factories');
        }
    }

    /**
     * Register the application services.
     */
    public function register()
    {
        // Automatically apply the package configuration
        $this->mergeConfigFrom(__DIR__.'/../config/magento.php', 'magento');

        $this->commands([
            Console\SyncMagnetoProductsCommand::class,
            Console\SyncMagnetoCategoriesCommand::class,
            Console\SyncMagnetoCustomersCommand::class,
            Console\SyncMagentoProductLinkTypesCommand::class,
        ]);

        // Register the main class to use with the facade
        $this->app->singleton('magento', function () {
            return new Magento();
        });
    }
}
