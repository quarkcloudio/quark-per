<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use QuarkCMS\QuarkAdmin\Http\Requests\ResourceActionRequest;

class ResourceActionController extends Controller
{
    /**
     * 解析行为
     *
     * @param  ResourceActionRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(ResourceActionRequest $request)
    {
        return $request->handleRequest();
    }
}