<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Http\Requests\ResourceDetailRequest;

class ResourceShowController extends Controller
{
    /**
     * 编辑页
     *
     * @param  ResourceDetailRequest  $request
     * @return array
     */
    public function handle(ResourceDetailRequest $request)
    {
        $data = $request->newResource()->beforeDetailShowing(
            $request,
            $request->newResourceWith($request->fillData())->toArray($request)
        );
        
        return $request->newResource()
        ->render(
            $request,
            $request->newResource()->detailComponentRender($request,$data)
        );
    }

    /**
     * 获取表单初始化数据
     *
     * @param  ResourceDetailRequest  $request
     * @return array
     */
    public function values(ResourceDetailRequest $request)
    {
        $data = $request->newResourceWith($request->fillData())->toArray($request);

        return success('获取成功','',$request->newResource()->beforeDetailShowing($request, $data));
    }
}