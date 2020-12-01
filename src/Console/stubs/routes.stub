<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;

Route::group([
    'middleware' => 'admin',
    'namespace' => 'App\\Http\\Controllers\\Admin'
], function (Router $router) {

    $router->get('admin/dashboard/index', 'DashboardController@index')->name('api/admin/dashboard/index');

    $router->get('admin/upgrade/index', 'UpgradeController@index')->name('api/admin/upgrade/index');
    $router->get('admin/upgrade/download', 'UpgradeController@download')->name('api/admin/upgrade/download');
    $router->get('admin/upgrade/extract', 'UpgradeController@extract')->name('api/admin/upgrade/extract');
    $router->get('admin/upgrade/updateFile', 'UpgradeController@updateFile')->name('api/admin/upgrade/updateFile');
    $router->get('admin/upgrade/updateDatabase', 'UpgradeController@updateDatabase')->name('api/admin/upgrade/updateDatabase');
    $router->get('admin/upgrade/clearCache', 'UpgradeController@clearCache')->name('api/admin/upgrade/clearCache');
    $router->get('admin/upgrade/finish', 'UpgradeController@finish')->name('api/admin/upgrade/finish');

    $router->get('admin/demo/index', 'DemoController@index')->name('api/admin/demo/index');
    $router->get('admin/demo/suggest', 'DemoController@suggest')->name('api/admin/demo/suggest');
});