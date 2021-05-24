<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class ResourceController extends Controller
{
    /**
     * List the resources for administration.
     *
     * @param  string  $resource
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle($resource, Request $request)
    {
        return response()->json([
            'label' => $resource,
        ]);
    }
}