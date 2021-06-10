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

        $validator = $getCalledClass::validatorForCreation($request, new $getCalledClass);

        if ($validator->fails()) {
            $errorMsg = null;
            $errors = $validator->errors()->getMessages();

            foreach($errors as $value) {
                $errorMsg = $value[0];
            }
            
            if($errorMsg) {
                return error($errorMsg);
            }
        }

        $data = $this->getSubmitData(
            (new $getCalledClass)->creationFields($request),
            (new $getCalledClass)->beforeSaving($request, $request->all()) // 保存前回调
        );

        $model = $getCalledClass::newModel()->create($data);

        // 保存后回调
        $result = (new $getCalledClass)->afterSaved($request, $model);

        if(isset($result['msg'])) {
            return $result;
        }

        if($result) {
            return success('操作成功！','/index?api=admin/' . $resource . '/index');
        } else {
            return error('操作失败，请重试！');
        }
    }

    /**
     * 获取提交表单的数据
     *
     * @param  Request  $request
     * @param  array $submitData
     * @return Response
     */
    protected function getSubmitData($fields, $submitData)
    {
        $result = [];

        foreach ($fields as $value) {
            if(isset($submitData[$value->name])) {
                $result[$value->name] = is_array($submitData[$value->name]) ? 
                json_encode($submitData[$value->name]) : $submitData[$value->name];

                if($value->type === 'password') {
                    $result[$value->name] = bcrypt($submitData[$value->name]);
                }
            }
        }

        return $result;
    }
}