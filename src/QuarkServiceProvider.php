<?php

namespace Tangtanglove\QuarkAdmin;

use Illuminate\Support\ServiceProvider;

class QuarkServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('quark', function ($app) {
            return new Quark($app['config']);
        });
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }
}
