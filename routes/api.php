<?php

use Illuminate\Support\Facades\Route;

// 仪表盘
Route::get('admin/dashboard/{resource}', 'DashboardController@show');

// 资源管理
Route::get('admin/{resource}/index', 'ResourceIndexController@handle');
Route::any('admin/{resource}/action/{uriKey}', 'ResourceActionController@handle');
Route::any('admin/{resource}/editable', 'ResourceEditableController@handle');
Route::get('admin/{resource}/create', 'ResourceCreateController@handle');
Route::post('admin/{resource}/store', 'ResourceStoreController@handle');
Route::get('admin/{resource}/edit', 'ResourceEditController@handle');
Route::post('admin/{resource}/save', 'ResourceUpdateController@handle');
Route::get('admin/{resource}/detail', 'ResourceShowController@handle');

// 图片上传、下载
Route::get('admin/picture/getLists', 'PictureController@getLists');
Route::post('admin/picture/upload', 'PictureController@upload');
Route::get('admin/picture/download', 'PictureController@download');

// 文件上传、下载
Route::post('admin/file/upload', 'FileController@upload');
Route::get('admin/file/download', 'FileController@download');