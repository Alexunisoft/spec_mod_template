<?php

namespace Alexunisoft\SpecModTemplate;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class SpecModTemplateServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->mergeConfigFrom(__DIR__ . '/../config/SpecModTemplate.php', 'spec_mod_template');
        $this->publishThings();
        // $this->loadViewsFrom(__DIR__.'/resources/views', 'spec_mod_template');
        // $this->loadMigrationsFrom(__DIR__.'/../database/migrations');
        // $this->registerRoutes();
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    private function registerRoutes()
    {
        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__ . '/Http/routes.php');
        });
    }

    /**
    * Get the Blogg route group configuration array.
    *
    * @return array
    */
    private function routeConfiguration()
    {
        return [
            'namespace'  => "Alexunisoft\SpecModTemplate\Http\Controllers",
            'middleware' => 'api',
            'prefix'     => 'api'
        ];
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        // Register facade
        $this->app->singleton('spec_mod_template', function () {
            return new SpecModTemplate;
        });
    }

    public function publishThings(){
        if ($this->app->runningInConsole()) {
            $this->publishes([
                __DIR__ . '/../config/SpecModTemplate.php' => config_path('SpecModTemplate.php'),
            ], 'config');
        }
    }
}