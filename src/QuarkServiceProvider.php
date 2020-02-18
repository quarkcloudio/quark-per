<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;

class QuarkServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        Console\InstallCommand::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin' => Middleware\AdminMiddleware::class,
    ];

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

        // 注册auth
        config(Arr::dot(config('quark.auth', []), 'auth.'));
        
        // 注册中间件
        foreach ($this->routeMiddleware as $key => $middleware) {
            app('router')->aliasMiddleware($key, $middleware);
        }

        // 注册命令行
        $this->commands($this->commands);
    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        if ($this->app->runningInConsole()) {
            $this->publishes([__DIR__.'/../config' => config_path()], 'quark-admin-config');
            $this->publishes([__DIR__.'/../database/migrations' => database_path('migrations')], 'quark-admin-migrations');
            $this->publishes([__DIR__.'/../resources/admin' => public_path('admin')], 'quark-admin-assets');
        }

        if (file_exists($routes = base_path().'/routes/admin.php')) {
            $this->loadRoutesFrom($routes);
        }
    }
}
