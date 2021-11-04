<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use QuarkCMS\QuarkAdmin\Http\Requests\ResourceExportRequest;

class ResourceExportController extends Controller
{
    /**
     * 导出数据
     *
     * @param  ResourceExportRequest  $request
     * @return array
     */
    public function handle(ResourceExportRequest $request)
    {
        // 列表页展示前回调
        $resource = $request->newResource()->beforeIndexShowing(
            $request,
            $request->newResource()::collection($request->indexQuery())
        );

        $data = $resource->toArray($request);
        $fields = $request->newResource()->indexFields($request);

        $exportData = [];
        $exportTitles = [];

        foreach ($fields as $fieldKey => $fieldValue) {
            $exportTitles[] = $fieldValue->label;

            foreach ($data as $dataKey => $dataValue) {
                switch ($fieldValue->component) {
                    case 'inputNumberField':
                        $exportData[$dataKey][$fieldValue->name] = $dataValue[$fieldValue->name];
                        break;

                    case 'textField':
                        $textValue = $dataValue[$fieldValue->name];
                        if(is_numeric($textValue) && strlen($textValue)>14) {
                            $textValue = $textValue."\t";
                        }
                        $exportData[$dataKey][$fieldValue->name] = $textValue;
                        break;
    
                    case 'selectField':
                        $exportData[$dataKey][$fieldValue->name] = $this->getOptionValue($fieldValue->options,$dataValue[$fieldValue->name]);
                        break;
    
                    case 'cascaderField':
                        $exportData[$dataKey][$fieldValue->name] = $this->getOptionValue($fieldValue->options,$dataValue[$fieldValue->name]);
                        break;
    
                    case 'checkboxField':
                        $exportData[$dataKey][$fieldValue->name] = $this->getOptionValue($fieldValue->options,$dataValue[$fieldValue->name]);
                        break;
    
                    case 'radioField':
                        $exportData[$dataKey][$fieldValue->name] = $this->getOptionValue($fieldValue->options,$dataValue[$fieldValue->name]);
                        break;
    
                    case 'switchField':
                        $exportData[$dataKey][$fieldValue->name] = $this->getSwitchValue($fieldValue->options,$dataValue[$fieldValue->name]);
                        break;
    
                    default:
                        $exportData[$dataKey][$fieldValue->name] = $dataValue[$fieldValue->name];
                        break;
                }
            }
        }

        return export('data', $exportTitles, $exportData);
    }

    /**
     * 获取属性值
     *
     * @param  array  $options
     * @param  string|number|bool  $value
     * 
     * @return string
     */
    protected function getOptionValue($options, $value)
    {
        $result = null;

        if(is_string($value)) {
            if(count(explode('[',$value))>1 || count(explode('{',$value))>1) {
                $value = json_decode($value, true);
            }
        }

        if(is_array($value)) {
            foreach ($options as $key => $option) {
                if(in_array($option['value'],$value)) {
                    $result = $result.','.$option['label'];
                }
            }
        } else {
            foreach ($options as $key => $option) {
                if($option['value'] === $value) {
                    $result = $option['label'];
                }
            }
        }

        return $result;
    }

    /**
     * 获取开关组件值
     *
     * @param  array  $options
     * @param  string|number|bool  $value
     * 
     * @return string
     */
    protected function getSwitchValue($options, $value)
    {
        return $options[$value ? 'on' : 'off'];
    }
}