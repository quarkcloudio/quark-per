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

        $data = $this->getSubmitData(
            $this->newResource()->updateFields($this),
            $this->newResource()->beforeSaving($this, $this->all()) // 保存前回调
        );

        $updateResult = $this->model()->where('id', $this->id)->update($data);

        if($updateResult) {
            $model = $this->model()->where('id', $this->id)->first();
        } else {
            $model = $updateResult;
        }

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
            if(isset($submitData[$value->name])) {
                $result[$value->name] = is_array($submitData[$value->name]) ? 
                json_encode($submitData[$value->name]) : $submitData[$value->name];
            }
        }

        return $result ?? [];
    }
}
