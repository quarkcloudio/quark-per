<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use QuarkCMS\QuarkAdmin\Http\Requests\ResourceIndexRequest;

class ResourceIndexController extends Controller
{
    /**
     * 列表页
     *
     * @param  ResourceIndexRequest  $request
     * @return array
     */
    public function handle(ResourceIndexRequest $request)
    {
        // 列表页展示前回调
        $data = $request->newResource()->beforeIndexShowing(
            $request,
            $request->newResource()::collection($request->indexQuery())
        );

        $component = $request->newResource()->indexComponentRender($request, $data);

        return $request->newResource()->setLayoutContent($component);
    }
}