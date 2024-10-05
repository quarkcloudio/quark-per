<?php

namespace QuarkCloudIO\QuarkAdmin\Http\Controllers;

use QuarkCloudIO\QuarkAdmin\Http\Requests\ResourceActionRequest;

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