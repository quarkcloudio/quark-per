<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class DashboardController extends Controller
{
    /**
     * 仪表盘展示
     *
     * @param  string $resource
     * @return array
     */
    public function show($resource)
    {
        $getCalledClass = 'App\\Admin\\Dashboards\\'.ucfirst($resource);

        if(!class_exists($getCalledClass)) {
            throw new \Exception("Class {$getCalledClass} does not exist.");
        }

        $calledClass = new $getCalledClass();

        return $calledClass->resource();
    }
}
