<?php

namespace QuarkCMS\QuarkAdmin\Http\Requests;

class ResourceUpdateRequest extends QuarkRequest
{
    /**
     * 执行行为
     *
     * @return array
     */
    public function handleRequest()
    {
        $resource = $this->resource();
        $validator = $resource::validatorForUpdate($this, $this->newResource());

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

        $submitData = $this->newResource()->beforeSaving($this, $this->all()); // 保存前回调

        if(isset($submitData['msg'])) {
            return $submitData;
        }

        $data = $this->getSubmitData(
            $this->newResource()->updateFields($this),
            $submitData
        );

        $updateResult = $this->model()->where('id', $this->id)->update($data);

        if($updateResult === false) {
            $model = $updateResult;
        } else {
            $model = $this->model()->where('id', $this->id)->first();
        }

        // 保存后回调
        return $this->newResource()->afterSaved($this, $model);

        // 保存后回调
        return $this->newResource()->afterSaved($this, $model);
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
        foreach ($fields as $value) {
            if(array_key_exists($value->name, $submitData)) {
                $result[$value->name] = is_array($submitData[$value->name]) ? 
                json_encode($submitData[$value->name]) : $submitData[$value->name];
            }
        }

        return $result ?? [];
    }
}
