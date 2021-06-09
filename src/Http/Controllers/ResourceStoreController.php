<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;

class ResourceStoreController extends Controller
{
    /**
     * Store the resources for administration.
     *
     * @param  string  $resource
     * @param  Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function handle($resource, Request $request)
    {
        $getCalledClass = 'App\\Admin\\Resources\\'.ucfirst($resource);

        if(!class_exists($getCalledClass)) {
            throw new \Exception("Class {$getCalledClass} does not exist.");
        }

        $data = $this->getSubmitData(
            (new $getCalledClass)->creationFields($request), $request
        );

        $result = $getCalledClass::newModel()->create($data);

        if($result) {
            return success('操作成功！');
        } else {
            return error('操作失败，请重试！');
        }
    }

    /**
     * 获取提交表单的数据
     *
     * @param  Request  $request
     * @return Response
     */
    protected function getSubmitData($fields, $request)
    {
        $requestData = $request->all();
        $result = [];

        foreach ($fields as $value) {
            if(isset($requestData[$value->name])) {
                $result[$value->name] = is_array($requestData[$value->name]) ? 
                json_encode($requestData[$value->name]) : $requestData[$value->name];
            }
        }

        return $result;
    }
}