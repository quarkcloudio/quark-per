<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;

class ResourceActionController extends Controller
{
    /**
     * 解析行为
     *
     * @param  string  $resource
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle($resource, $uriKey, Request $request)
    {
        $getCalledClass = 'App\\Admin\\Resources\\'.ucfirst($resource);
        if(!class_exists($getCalledClass)) {
            throw new \Exception("Class {$getCalledClass} does not exist.");
        }
        $calledClass = new $getCalledClass();

        return $calledClass->action($request, $uriKey);
    }
}