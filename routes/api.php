<?php

use Illuminate\Support\Facades\Route;

// 仪表盘
Route::get('admin/dashboard/{dashboard}', 'DashboardController@handle');

// 资源管理
Route::get('admin/{resource}/index', 'ResourceIndexController@handle');
Route::get('admin/{resource}/export', 'ResourceExportController@handle');
Route::any('admin/{resource}/import', 'ResourceImportController@handle');
Route::get('admin/{resource}/import/template', 'ResourceImportController@template');
Route::any('admin/{resource}/import/downloadFailed', 'ResourceImportController@downloadFailed');
Route::any('admin/{resource}/action/{uriKey}', 'ResourceActionController@handle');
Route::any('admin/{resource}/editable', 'ResourceEditableController@handle');
Route::get('admin/{resource}/create', 'ResourceCreateController@handle');
Route::post('admin/{resource}/store', 'ResourceStoreController@handle');
Route::get('admin/{resource}/edit', 'ResourceEditController@handle');
Route::get('admin/{resource}/edit/values', 'ResourceEditController@values');
Route::post('admin/{resource}/save', 'ResourceUpdateController@handle');
Route::get('admin/{resource}/detail', 'ResourceShowController@handle');

// 直接加载前端组件
Route::get('admin/pages/{component}', 'PagesController@handle');

// 通用表单资源
Route::get('admin/{resource}/{uriKey}-form', 'ResourceCreateController@handle');
Route::get('admin/{resource}/{uriKey}/form', 'ResourceCreateController@handle');

// 图片上传、下载
Route::get('admin/picture/getLists', 'PictureController@getLists');
Route::post('admin/picture/upload', 'PictureController@upload');
Route::any('admin/picture/crop', 'PictureController@crop');
Route::any('admin/picture/delete', 'PictureController@delete');
Route::get('admin/picture/download', 'PictureController@download');

// 文件上传、下载
Route::post('admin/file/upload', 'FileController@upload');
Route::get('admin/file/download', 'FileController@download');
