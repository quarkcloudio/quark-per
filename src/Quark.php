<?php

namespace QuarkCMS\QuarkAdmin;

use Closure;
use Illuminate\Database\Eloquent\Model;
use InvalidArgumentException;
use Route;

/**
 * Class Quark.
 */
class Quark
{
    /**
     * @param $model
     * @param Closure $callable
     *
     * @return QuarkCMS\QuarkAdmin\Form
     */
    public function form($model = null)
    {
        return new Form($model);
    }

    /**
     * @param $model
     * @param Closure $callable
     *
     * @return QuarkCMS\QuarkAdmin\Layout\Content
     */
    public function content()
    {
        return new Layout\Content();
    }

    /**
     * @param $model
     * @param Closure $callable
     *
     * @return QuarkCMS\QuarkAdmin\Layout\Content
     */
    public function table($model = null)
    {
        return new Table($model);
    }

    /**
     * @param $model
     * @param Closure $callable
     *
     * @return QuarkCMS\QuarkAdmin\Layout\Content
     */
    public function grid($model = null)
    {
        return new Grid($model);
    }

    /**
     * @param $model
     * @param Closure $callable
     *
     * @return routes
     */
    public function routes()
    {
        $attributes = [
            'prefix'     => config('quark.route.prefix'),
            'middleware' => config('quark.route.middleware'),
        ];

        app('router')->group($attributes, function ($router) {

            $router->namespace('\QuarkCMS\QuarkAdmin\Controllers')->group(function ($router) {
                
                $router->get('admin/dashboard/index', 'DashboardController@index')->name('api/admin/dashboard/index');
                $router->get('admin/dashboard/clearCache', 'DashboardController@clearCache')->name('api/admin/dashboard/clearCache');
                $router->get('admin/dashboard/update', 'DashboardController@update')->name('api/admin/dashboard/update');
                $router->get('admin/dashboard/download', 'DashboardController@download')->name('api/admin/dashboard/download');
                $router->get('admin/dashboard/extract', 'DashboardController@extract')->name('api/admin/dashboard/extract');
                $router->get('admin/dashboard/updateFile', 'DashboardController@updateFile')->name('api/admin/dashboard/updateFile');
                $router->get('admin/dashboard/updateDatabase', 'DashboardController@updateDatabase')->name('api/admin/dashboard/updateDatabase');
                $router->get('admin/dashboard/finish', 'DashboardController@finish')->name('api/admin/dashboard/finish');
                
                $router->any('admin/account/info', 'AccountController@info')->name('api/admin/account/info');
                $router->post('admin/account/profile', 'AccountController@profile')->name('api/admin/account/profile');
                $router->post('admin/account/password', 'AccountController@password')->name('api/admin/account/password');
                $router->any('admin/account/menus', 'AccountController@menus')->name('api/admin/account/menus');
            
                $router->get('admin/admin/index', 'AdminController@index')->name('api/admin/admin/index');
                $router->get('admin/admin/create', 'AdminController@create')->name('api/admin/admin/create');
                $router->post('admin/admin/store', 'AdminController@store')->name('api/admin/admin/store');
                $router->get('admin/admin/edit', 'AdminController@edit')->name('api/admin/admin/edit');
                $router->post('admin/admin/save', 'AdminController@save')->name('api/admin/admin/save');
                $router->post('admin/admin/changeStatus', 'AdminController@changeStatus')->name('api/admin/admin/changeStatus');
            
                $router->get('admin/permission/index', 'PermissionController@index')->name('api/admin/permission/index');
                $router->get('admin/permission/create', 'PermissionController@create')->name('api/admin/permission/create');
                $router->post('admin/permission/store', 'PermissionController@store')->name('api/admin/permission/store');
                $router->get('admin/permission/edit', 'PermissionController@edit')->name('api/admin/permission/edit');
                $router->post('admin/permission/save', 'PermissionController@save')->name('api/admin/permission/save');
                $router->post('admin/permission/changeStatus', 'PermissionController@changeStatus')->name('api/admin/permission/changeStatus');
            
                $router->get('admin/role/index', 'RoleController@index')->name('api/admin/role/index');
                $router->get('admin/role/create', 'RoleController@create')->name('api/admin/role/create');
                $router->post('admin/role/store', 'RoleController@store')->name('api/admin/role/store');
                $router->get('admin/role/edit', 'RoleController@edit')->name('api/admin/role/edit');
                $router->post('admin/role/save', 'RoleController@save')->name('api/admin/role/save');
                $router->post('admin/role/changeStatus', 'RoleController@changeStatus')->name('api/admin/role/changeStatus');
            
                $router->any('admin/config/website', 'ConfigController@website')->name('api/admin/config/website');
                $router->any('admin/config/saveWebsite', 'ConfigController@saveWebsite')->name('api/admin/config/saveWebsite');
                $router->any('admin/config/index', 'ConfigController@index')->name('api/admin/config/index');
                $router->get('admin/config/create', 'ConfigController@create')->name('api/admin/config/create');
                $router->post('admin/config/store', 'ConfigController@store')->name('api/admin/config/store');
                $router->get('admin/config/edit', 'ConfigController@edit')->name('api/admin/config/edit');
                $router->post('admin/config/save', 'ConfigController@save')->name('api/admin/config/save');
                $router->post('admin/config/changeStatus', 'ConfigController@changeStatus')->name('api/admin/config/changeStatus');
            
                $router->get('admin/menu/index', 'MenuController@index')->name('api/admin/menu/index');
                $router->get('admin/menu/create', 'MenuController@create')->name('api/admin/menu/create');
                $router->post('admin/menu/store', 'MenuController@store')->name('api/admin/menu/store');
                $router->get('admin/menu/edit', 'MenuController@edit')->name('api/admin/menu/edit');
                $router->post('admin/menu/save', 'MenuController@save')->name('api/admin/menu/save');
                $router->post('admin/menu/changeStatus', 'MenuController@changeStatus')->name('api/admin/menu/changeStatus');
            
                $router->get('admin/actionLog/index', 'ActionLogController@index')->name('api/admin/actionLog/index');
                $router->post('admin/actionLog/changeStatus', 'ActionLogController@changeStatus')->name('api/admin/actionLog/changeStatus');
                $router->get('admin/actionLog/export', 'ActionLogController@export')->name('api/admin/actionLog/export');
            
                $router->any('admin/picture/index', 'PictureController@index')->name('api/admin/picture/index');
                $router->post('admin/picture/upload', 'PictureController@upload')->name('api/admin/picture/upload');
                $router->get('admin/picture/download', 'PictureController@download')->name('api/admin/picture/download');
                $router->post('admin/picture/update', 'PictureController@update')->name('api/admin/picture/update');
                $router->get('admin/picture/edit', 'PictureController@edit')->name('api/admin/picture/edit');
                $router->post('admin/picture/save', 'PictureController@save')->name('api/admin/picture/save');
                $router->post('admin/picture/changeStatus', 'PictureController@changeStatus')->name('api/admin/picture/changeStatus');
            
                $router->any('admin/file/index', 'FileController@index')->name('api/admin/file/index');
                $router->post('admin/file/upload', 'FileController@upload')->name('api/admin/file/upload');
                $router->get('admin/file/download', 'FileController@download')->name('api/admin/file/download');
                $router->post('admin/file/update', 'FileController@update')->name('api/admin/file/update');
                $router->post('admin/file/changeStatus', 'FileController@changeStatus')->name('api/admin/file/changeStatus');
            });

            Route::get('admin/captcha', 'QuarkCMS\\QuarkAdmin\\Controllers\\Auth\\AdminLoginController@captcha')->name('api/admin/captcha');
            Route::post('admin/login', 'QuarkCMS\\QuarkAdmin\\Controllers\\Auth\\AdminLoginController@login')->name('api/admin/login');
            Route::any('admin/logout', 'QuarkCMS\\QuarkAdmin\\Controllers\\Auth\\AdminLoginController@logout')->name('api/admin/logout');
        });
    }
}
