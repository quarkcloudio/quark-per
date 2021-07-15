<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use QuarkCMS\QuarkAdmin\Http\Requests\ResourceStoreRequest;

class ResourceStoreController extends Controller
{
    /**
     * 保存创建数据
     *
     * @param  ResourceStoreRequest  $request
     * @return array
     */
    public function handle(ResourceStoreRequest $request)
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