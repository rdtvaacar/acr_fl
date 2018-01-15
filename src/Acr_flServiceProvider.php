<?php

namespace Acr\Acr_fl;

use Acr\Acr_fl\Controllers\FlController;
use Illuminate\Support\ServiceProvider;

class Acr_flServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        require __DIR__ . "/routes.php";
        $this->loadViewsFrom(__DIR__ . '/views', 'Acr_flv');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->bind('Acr_fl', function () {
            return new FlController();
        });
    }
}