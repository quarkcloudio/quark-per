<?php

namespace QuarkCloudIO\QuarkAdmin\Http\Controllers;

use QuarkCloudIO\QuarkAdmin\Http\Requests\ResourceEditableRequest;

class ResourceEditableController extends Controller
{
    /**
     * 解析行为
     *
     * @param  ResourceEditableRequest  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle(ResourceEditableRequest $request)
    {
        $result = $request->handleRequest();

        if($result) {
            return success('操作成功！');
        } else {
            return error('操作失败，请重试！');
        }
    }
}