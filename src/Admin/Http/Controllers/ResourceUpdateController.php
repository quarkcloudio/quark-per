<?php

namespace QuarkCloudIO\QuarkAdmin\Http\Controllers;

use QuarkCloudIO\QuarkAdmin\Http\Requests\ResourceUpdateRequest;

class ResourceUpdateController extends Controller
{
    /**
     * 保存创建数据
     *
     * @param  ResourceUpdateRequest  $request
     * @return array
     */
    public function handle(ResourceUpdateRequest $request)
    {
        $result = $request->handleRequest();

        if(isset($result['msg'])) {
            return $result;
        }

        if($result) {
            return success('操作成功！','/index?api=admin/' . $request->route('resource') . '/index');
        } else {
            return error('操作失败，请重试！');
        }
    }
}