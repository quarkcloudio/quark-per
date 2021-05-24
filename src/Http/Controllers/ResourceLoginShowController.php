<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class ResourceLoginShowController extends Controller
{
    /**
     * Login the resources for administration.
     *
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(Request $request)
    {
        $calledClass = 'App\\Admin\\Resources\\Login';

        $loginClass = class_exists($calledClass) ? $calledClass : 'QuarkCMS\\QuarkAdmin\\Resource\\Login';

        return new $loginClass();
    }
}