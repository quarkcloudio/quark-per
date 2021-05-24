<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Support\Arr;
use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Route;

class AdminServiceProvider extends ServiceProvider
{
    /**
     * @var array
     */
    protected $commands = [
        Console\InstallCommand::class,
        Console\PublishCommand::class,
        Console\UpdateCommand::class,
    ];

    /**
     * The application's route middleware.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'admin' => Http\Middleware\AdminMiddleware::class,
        'admin.guest' => Http\Middleware\RedirectIfAuthenticated::class,
    ];

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('admin', function ($app) {
            return new Admin($app['config']);
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
            $this->publishes([__DIR__.'/../public' => public_path('admin')], 'quark-admin-assets');
            $this->publishes([__DIR__.'/../resources/lang' => resource_path('lang')], 'quark-admin-resources-lang');
            $this->publishes([__DIR__.'/../resources/views' => resource_path('views/admin')], 'quark-admin-resources-views');
        }
        
        $this->registerApiRoutes();
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
        Route::namespace('QuarkCMS\QuarkAdmin\Http\Controllers')
            ->middleware('admin.guest')
            ->prefix('api')
            ->group(function () {
                Route::get('admin/captcha', 'CaptchaController@captcha');
                Route::get('admin/login', 'ResourceLoginShowController@handle');
                Route::post('admin/login', 'ResourceLoginController@login')->name('admin/login');
            });

        Route::namespace('QuarkCMS\QuarkAdmin\Http\Controllers')
            ->middleware([])
            ->prefix('api')
            ->group(function () {
                Route::get('admin/logout', 'LoginController@logout')->name('admin/logout');
            });

        Route::group($this->routeConfiguration(), function () {
            $this->loadRoutesFrom(__DIR__.'/../routes/api.php');
        });
    }

    /**
     * Get the route group configuration array.
     *
     * @return array
     */
    protected function routeConfiguration()
    {
        return [
            'namespace' => 'QuarkCMS\QuarkAdmin\Http\Controllers',
            'prefix' => 'api',
            'middleware' => 'admin',
        ];
    }
}
