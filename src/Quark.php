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
     * @return QuarkCMS\QuarkAdmin\Layout\Show
     */
    public function show($model = null)
    {
        return new Show($model);
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

            Route::get('admin/appInfo', 'QuarkCMS\\QuarkAdmin\\Controllers\\QuarkController@appInfo')->name('api/admin/quark/appInfo');
            Route::get('admin/captcha', 'QuarkCMS\\QuarkAdmin\\Controllers\\Auth\\AdminLoginController@captcha')->name('api/admin/captcha');
            Route::post('admin/login', 'QuarkCMS\\QuarkAdmin\\Controllers\\Auth\\AdminLoginController@login')->name('api/admin/login');
            Route::any('admin/logout', 'QuarkCMS\\QuarkAdmin\\Controllers\\Auth\\AdminLoginController@logout')->name('api/admin/logout');

            $router->namespace('\QuarkCMS\QuarkAdmin\Controllers')->group(function ($router) {
                
                $router->get('admin/upgrade/index', 'UpgradeController@index')->name('api/admin/upgrade/index');
                $router->get('admin/upgrade/download', 'UpgradeController@download')->name('api/admin/upgrade/download');
                $router->get('admin/upgrade/extract', 'UpgradeController@extract')->name('api/admin/upgrade/extract');
                $router->get('admin/upgrade/updateFile', 'UpgradeController@updateFile')->name('api/admin/upgrade/updateFile');
                $router->get('admin/upgrade/updateDatabase', 'UpgradeController@updateDatabase')->name('api/admin/upgrade/updateDatabase');
                $router->get('admin/upgrade/finish', 'UpgradeController@finish')->name('api/admin/upgrade/finish');
                
                $router->any('admin/account/info', 'AccountController@info')->name('api/admin/account/info');
                $router->post('admin/account/profile', 'AccountController@profile')->name('api/admin/account/profile');
                $router->post('admin/account/password', 'AccountController@password')->name('api/admin/account/password');
                $router->any('admin/account/menus', 'AccountController@menus')->name('api/admin/account/menus');
            
                $router->get('admin/admin/index', 'AdminController@index')->name('api/admin/admin/index');
                $router->get('admin/admin/show', 'AdminController@show')->name('api/admin/admin/show');
                $router->get('admin/admin/create', 'AdminController@create')->name('api/admin/admin/create');
                $router->post('admin/admin/store', 'AdminController@store')->name('api/admin/admin/store');
                $router->get('admin/admin/edit', 'AdminController@edit')->name('api/admin/admin/edit');
                $router->post('admin/admin/update', 'AdminController@update')->name('api/admin/admin/update');
                $router->any('admin/admin/action', 'AdminController@action')->name('api/admin/admin/action');
                $router->post('admin/admin/destroy', 'AdminController@destroy')->name('api/admin/admin/destroy');
            
                $router->get('admin/permission/index', 'PermissionController@index')->name('api/admin/permission/index');
                $router->post('admin/permission/sync', 'PermissionController@sync')->name('api/admin/permission/sync');
                $router->any('admin/permission/action', 'PermissionController@action')->name('api/admin/permission/action');
                $router->post('admin/permission/destroy', 'PermissionController@destroy')->name('api/admin/permission/destroy');

                $router->get('admin/role/index', 'RoleController@index')->name('api/admin/role/index');
                $router->get('admin/role/create', 'RoleController@create')->name('api/admin/role/create');
                $router->post('admin/role/store', 'RoleController@store')->name('api/admin/role/store');
                $router->get('admin/role/edit', 'RoleController@edit')->name('api/admin/role/edit');
                $router->post('admin/role/update', 'RoleController@update')->name('api/admin/role/update');
                $router->post('admin/role/destroy', 'RoleController@destroy')->name('api/admin/role/destroy');
            
                $router->any('admin/config/website', 'ConfigController@website')->name('api/admin/config/website');
                $router->any('admin/config/saveWebsite', 'ConfigController@saveWebsite')->name('api/admin/config/saveWebsite');
                $router->any('admin/config/index', 'ConfigController@index')->name('api/admin/config/index');
                $router->get('admin/config/create', 'ConfigController@create')->name('api/admin/config/create');
                $router->post('admin/config/store', 'ConfigController@store')->name('api/admin/config/store');
                $router->get('admin/config/edit', 'ConfigController@edit')->name('api/admin/config/edit');
                $router->post('admin/config/update', 'ConfigController@update')->name('api/admin/config/update');
                $router->any('admin/config/action', 'ConfigController@action')->name('api/admin/config/action');
                $router->post('admin/config/destroy', 'ConfigController@destroy')->name('api/admin/config/destroy');
            
                $router->get('admin/menu/index', 'MenuController@index')->name('api/admin/menu/index');
                $router->get('admin/menu/create', 'MenuController@create')->name('api/admin/menu/create');
                $router->post('admin/menu/store', 'MenuController@store')->name('api/admin/menu/store');
                $router->get('admin/menu/edit', 'MenuController@edit')->name('api/admin/menu/edit');
                $router->post('admin/menu/update', 'MenuController@update')->name('api/admin/menu/update');
                $router->any('admin/menu/action', 'MenuController@action')->name('api/admin/menu/action');
                $router->post('admin/menu/destroy', 'MenuController@destroy')->name('api/admin/menu/destroy');
            
                $router->get('admin/actionLog/index', 'ActionLogController@index')->name('api/admin/actionLog/index');
                $router->post('admin/actionLog/destroy', 'ActionLogController@destroy')->name('api/admin/actionLog/destroy');
                $router->get('admin/actionLog/show', 'ActionLogController@show')->name('api/admin/actionLog/show');
            
                $router->get('admin/picture/index', 'PictureController@index')->name('api/admin/picture/index');
                $router->get('admin/picture/getLists', 'PictureController@getLists')->name('api/admin/picture/getLists');
                $router->post('admin/picture/upload', 'PictureController@upload')->name('api/admin/picture/upload');
                $router->get('admin/picture/download', 'PictureController@download')->name('api/admin/picture/download');
                $router->any('admin/picture/action', 'PictureController@action')->name('api/admin/picture/action');
                $router->post('admin/picture/delete', 'PictureController@delete')->name('api/admin/picture/delete');

                $router->any('admin/file/index', 'FileController@index')->name('api/admin/file/index');
                $router->post('admin/file/upload', 'FileController@upload')->name('api/admin/file/upload');
                $router->get('admin/file/download', 'FileController@download')->name('api/admin/file/download');
                $router->post('admin/file/action', 'FileController@action')->name('api/admin/file/action');
                $router->post('admin/file/delete', 'FileController@delete')->name('api/admin/file/delete');
            });
        });
    }
}
