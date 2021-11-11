<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use QuarkCMS\QuarkAdmin\Http\Requests\ResourceCreateRequest;

class ResourceCreateController extends Controller
{
    /**
     * 创建页
     *
     * @param  ResourceCreateRequest  $request
     * @return array
     */
    public function handle(ResourceCreateRequest $request)
    {
        $data = $request->newResource()->beforeCreating($request);

        return $request->newResource()
        ->render(
            $request,
            $request->newResource()->creationComponentRender($request,$data)
        );
    }
}