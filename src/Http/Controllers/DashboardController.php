<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

class DashboardController extends Controller
{
    /**
     * 仪表盘展示
     *
     * @param  string $dashboard
     * @return array
     */
    public function show($dashboard)
    {
        $getCalledClass = 'App\\Admin\\Dashboards\\'.ucfirst($dashboard);

        if(!class_exists($getCalledClass)) {
            throw new \Exception("Class {$getCalledClass} does not exist.");
        }

        $calledClass = new $getCalledClass();

        return $calledClass->resource();
    }
}
