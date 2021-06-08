<?php

use Illuminate\Support\Facades\Route;

// Dashboard
Route::get('admin/dashboard/{resource}', 'DashboardController@show');

// Resource Management...
Route::get('admin/{resource}/index', 'ResourceIndexController@handle');
Route::any('admin/{resource}/action/{uriKey}', 'ResourceActionController@handle');
Route::get('admin/{resource}/create', 'ResourceCreateController@handle');
Route::post('admin/{resource}/store', 'ResourceStoreController@handle');
Route::get('admin/{resource}/edit', 'ResourceEditController@handle');
Route::post('admin/{resource}/save', 'ResourceUpdateController@handle');
Route::get('admin/{resource}/detail', 'ResourceShowController@handle');