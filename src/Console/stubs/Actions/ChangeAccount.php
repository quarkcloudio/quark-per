<?php

namespace App\Admin\Actions;

use QuarkCMS\QuarkAdmin\Actions\Action;

class ChangeAccount extends Action
{
    /**
     * 执行行为
     *
     * @param  Fields  $fields
     * @param  Collection  $model
     * @return mixed
     */
    public function handle($fields, $model)
    {
        $data = $fields->all();

        if(isset($data['avatar'])) {
            $data['avatar'] = json_encode($data['avatar']);
        }

        if(isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        // 获取资源
        $resource = $fields->resource();

        // 表单验证
        $validator = $resource::validatorForCreation($fields, $fields->newResource());

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

        $result = $model->where('id',ADMINID)->update($data);

        return $result ? success('操作成功！','reload') : error('操作失败，请重试！');
    }
}