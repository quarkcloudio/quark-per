<?php

use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('admin/dashboard/{resource}', 'DashboardController@show');

// Resource Management...
Route::get('admin/{resource}/index', 'ResourceIndexController@handle');
Route::any('admin/{resource}/action', 'ResourceActionController@handle');
Route::put('admin/{resource}/restore', 'ResourceRestoreController@handle');
Route::get('admin/{resource}/{resourceId}', 'ResourceShowController@handle');
Route::post('admin/{resource}', 'ResourceStoreController@handle');
Route::put('admin/{resource}/{resourceId}', 'ResourceUpdateController@handle');
Route::delete('admin/{resource}', 'ResourceDestroyController@handle');