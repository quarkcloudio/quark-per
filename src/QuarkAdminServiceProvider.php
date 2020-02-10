<?php

namespace Quarkcms\QuarkAdmin;

use Illuminate\Support\ServiceProvider;

class QuarkAdminServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('quark-admin', function ($app) {
            return new QuarkAdmin($app['config']);
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
