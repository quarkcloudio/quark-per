<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class QuarkServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        Console\InstallCommand::class,
        Console\PublishCommand::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin' => Http\Middleware\AdminMiddleware::class,
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
        config(Arr::dot(config('admin.auth', []), 'auth.'));
        
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
            
            // 兼容laravel8
            if(app()::VERSION >= '8.0.0') {
                $this->publishes([__DIR__.'/../database/seeds' => database_path('seeders')], 'quark-admin-seeds');
            } else {
                $this->publishes([__DIR__.'/../database/seeds' => database_path('seeds')], 'quark-admin-seeds');
            }
            $this->publishes([__DIR__.'/../public' => public_path('admin')], 'quark-admin-assets');
            $this->publishes([__DIR__.'/../resources/lang' => resource_path('lang')], 'quark-admin-resources-lang');
            $this->publishes([__DIR__.'/../resources/views' => resource_path('views/admin')], 'quark-admin-resources-views');
        }

        $this->registerRoutes();
    }

    /**
     * Register the package routes.
     *
     * @return void
     */
    protected function registerRoutes()
    {
        $this->registerApiRoutes();

        $this->registerAdminRoutes();
    }

    /**
     * Define the "api" routes for the application.
     *
     * These routes are typically stateless.
     *
     * @return void
     */
    protected function registerApiRoutes()
    {
        Route::middleware('api')
        ->group(__DIR__.'/../routes/api.php');
    }

    /**
     * Define the "admin" routes for the application.
     *
     * These routes all receive session state, CSRF protection, etc.
     *
     * @return void
     */
    protected function registerAdminRoutes()
    {
        Route::prefix('api')
        ->middleware('api')
        ->group(base_path().'/routes/admin.php');
    }
}
