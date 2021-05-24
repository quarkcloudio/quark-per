<?php

use Illuminate\Support\Facades\Route;

Route::get('admin/{resource}', 'ResourceController@handle');