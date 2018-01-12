<?php

namespace Acr\Acr_fl;

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
        $this->loadViewsFrom(__DIR__ . '/views', 'Acr_fl');
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
