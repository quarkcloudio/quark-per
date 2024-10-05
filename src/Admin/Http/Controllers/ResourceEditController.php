<?php

namespace QuarkCloudIO\QuarkAdmin\Http\Controllers;

use QuarkCloudIO\QuarkAdmin\Http\Requests\ResourceEditRequest;

class ResourceEditController extends Controller
{
    /**
     * 编辑页
     *
     * @param  ResourceEditRequest  $request
     * @return array
     */
    public function handle(ResourceEditRequest $request)
    {
        $data = $request->newResource()->beforeEditing(
            $request,
            $request->newResourceWith($request->fillData())->toArray($request)
        );
        
        return $request->newResource()
        ->render(
            $request,
            $request->newResource()->updateComponentRender($request,$data)
        );
    }

    /**
     * 获取表单初始化数据
     *
     * @param  ResourceEditRequest  $request
     * @return array
     */
    public function values(ResourceEditRequest $request)
    {
        $data = $request->newResourceWith($request->fillData())->toArray($request);

        return success('获取成功','',$request->newResource()->beforeEditing($request, $data));
    }
}