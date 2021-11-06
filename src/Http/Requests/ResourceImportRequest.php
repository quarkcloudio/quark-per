<?php

namespace QuarkCMS\QuarkAdmin\Http\Requests;

class ResourceImportRequest extends QuarkRequest
{
    /**
     * 执行行为
     *
     * @return array
     */
    public function handleRequest()
    {
        $fileId = $this->input('fileId');

        if(empty($fileId)) {
            return error('参数错误！');
        }

        $resource = $this->resource();
        $importData = import($fileId);

        // 表格头部
        $importHead = $importData[0];

        // 去除表格头部
        unset($importData[0]);

        $lists = $this->newResource()->beforeImporting($importData); // 导入前回调
        $importResult = true;
        $importTotalNum = count($lists);
        $importSuccessedNum = 0;
        $importFailedNum = 0;
        $importFailedData = null;

        foreach ($lists as $key => $item) {

            $formValues = $this->transformFormValues($item);

            $validator = $resource::validatorForCreation($this, $this->newResource(), $formValues);

            $ruleData = static::rulesForCreation($request, $resource);
    
            if ($validator->fails()) {
                $errorMsg = null;
                $errors = $validator->errors()->getMessages();
    
                foreach($errors as $value) {
                    $errorMsg = $value[0];
                }
                
                $importResult = false;
                $importFailedNum = $importFailedNum+1; // 记录错误条数
                $item['errorMsg'] = $errorMsg; // 记录错误信息

                //记录错误数据
                $importFailedData[] = $item;
                continue;
            }
    
            $submitData = $this->newResource()->beforeSaving(
                $this,
                $formValues
            ); // 保存前回调
    
            if(isset($submitData['msg'])) {
                $importResult = false;
                $importFailedNum = $importFailedNum+1; // 记录错误条数
                $item['errorMsg'] = $submitData['msg']; // 记录错误信息

                //记录错误数据
                $importFailedData[] = $item;
                continue;
            }
    
            $data = $this->getSubmitData(
                $this->newResource()->importFields($this),
                $submitData
            );
    
            $model = $this->model()->create($data);

            $this->newResource()->afterSaved($this, $model);
            
            $importSuccessedNum = $importSuccessedNum+1;
        }

        if($importResult) {
            return success('导入成功！','',[
                'totalNum' => $importTotalNum,
                'successedNum' => $importSuccessedNum,
                'failedNum' => $importFailedNum
            ]);
        } else {
            cache([
                'failedFileId'.$fileId => [
                    'failedHead' => $importHead,
                    'failedData' => $importFailedData,
                ]
            ], 300);

            return success('导入失败！','',[
                'totalNum' => $importTotalNum,
                'successedNum' => $importSuccessedNum,
                'failedNum' => $importFailedNum,
                'failedFileId' => 'failedFileId'.$fileId
            ]);
        }
    }

    /**
     * 将表格数据转换成表单数据
     *
     * @param  array $data
     * @return Response
     */
    protected function transformFormValues($data)
    {
        $importFields = $this->newResource()->importFields($this);

        foreach ($importFields as $key => $value) {
            if(isset($data[$key])) {
                $result[$value->name] = $value;
            }
        }

        return $result ?? [];
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
        foreach ($fields as $fieldValue) {

            switch ($fieldValue->component) {
                case 'inputNumberField':
                    $result[$fieldValue->name] = $submitData[$fieldValue->name];
                    break;

                case 'textField':
                    $result[$fieldValue->name] = trim($submitData[$fieldValue->name]);
                    break;

                case 'selectField':
                    $result[$fieldValue->name] = $this->getOptionValue($fieldValue->options,$submitData[$fieldValue->name]);
                    break;

                case 'cascaderField':
                    $result[$fieldValue->name] = $this->getOptionValue($fieldValue->options,$submitData[$fieldValue->name]);
                    break;

                case 'checkboxField':
                    $result[$fieldValue->name] = $this->getOptionValue($fieldValue->options,$submitData[$fieldValue->name]);
                    break;

                case 'radioField':
                    $result[$fieldValue->name] = $this->getOptionValue($fieldValue->options,$submitData[$fieldValue->name]);
                    break;

                case 'switchField':
                    $result[$fieldValue->name] = $this->getSwitchValue($fieldValue->options,$submitData[$fieldValue->name]);
                    break;

                default:
                    $result[$fieldValue->name] = $submitData[$fieldValue->name];
                    break;
            }

            $result[$fieldValue->name] = is_array($result[$fieldValue->name]) ? 
            json_encode($result[$fieldValue->name]) : $result[$fieldValue->name];
        }

        return $result ?? [];
    }

    /**
     * 获取属性值
     *
     * @param  array  $options
     * @param  string|number|bool  $label
     * 
     * @return string
     */
    protected function getOptionValue($options, $label)
    {
        $result = null;

        if(count(explode(',',$label))>1) {
            $labels = explode(',',$label);
            foreach ($options as $key => $option) {
                if(in_array($option['label'],$labels)) {
                    $result[] = $option['value'];
                }
            }
        } else {
            foreach ($options as $key => $option) {
                if($option['label'] == $label) {
                    $result = $option['value'];
                }
            }
        }

        return $result;
    }

    /**
     * 获取开关组件值
     *
     * @param  array  $options
     * @param  string|number|bool  $label
     * 
     * @return string
     */
    protected function getSwitchValue($options, $label)
    {
        return ($options['on'] == $label);
    }
}
