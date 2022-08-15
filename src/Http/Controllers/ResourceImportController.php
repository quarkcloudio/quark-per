<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use QuarkCMS\QuarkAdmin\Http\Requests\ResourceImportRequest;

class ResourceImportController extends Controller
{
    /**
     * 导入数据
     *
     * @param  ResourceImportRequest  $request
     * @return array
     */
    public function handle(ResourceImportRequest $request)
    {
        return $request->handleRequest();
    }

    /**
     * 导入数据模板
     *
     * @param  ResourceImportRequest  $request
     * @return array
     */
    public function template(ResourceImportRequest $request)
    {
        // 列表页展示前回调
        $resource = $request->newResource();

        $fields = $request->newResource()->importFieldsWithoutHidden($request);

        $exportTitles = [];

        foreach ($fields as $fieldKey => $fieldValue) {
            if($fieldValue->component!='hiddenField') {
                $exportTitles[] = $fieldValue->label . $this->getFieldRemark($fieldValue);
            }
        }

        return export('template', $exportTitles, []);
    }

    /**
     * 下载导入失败的数据
     *
     * @param  ResourceImportRequest  $request
     * @return array
     */
    public function downloadFailed(ResourceImportRequest $request)
    {
        $failedFileId = $request->input('failedFileId');
        
        $failed = cache('failedFileId'.$failedFileId);

        if(empty($failed)) {
            return error('数据已过期！');
        }

        return export('failedData', $failed['failedHead'], $failed['failedData']);
    }

    /**
     * 导入字段提示信息
     *
     * @param  object  $field
     * @return string
     */
    protected function getFieldRemark($field)
    {
        $remark = null;

        switch ($field->component) {
            case 'inputNumberField':
                $remark = '数字格式';
                break;

            case 'textField':
                $remark = null;
                break;

            case 'selectField':
                $fieldOptionLabel = $this->getFieldOptionLabels($field->options);

                if ($field->mode !== null) {
                    $remark = '可多选：' . $fieldOptionLabel . '；多值请用“,”分割';
                } else {
                    $remark = '可选：' . $fieldOptionLabel;
                }
                break;

            case 'cascaderField':
                $remark = '级联格式，例如：省，市，县';
                break;

            case 'checkboxField':
                $remark = '可多选项：' . $this->getFieldOptionLabels($field->options) . '；多值请用“,”分割';
                break;

            case 'radioField':
                $remark = '可选项：' . $this->getFieldOptionLabels($field->options);
                break;

            case 'switchField':
                $remark = '可选项：' . $this->getSwitchLabels($field->options);
                break;

            case 'dateField':
                $remark = '日期格式，例如：1987-02-15';
                break;

            case 'datetimeField':
                $remark = '日期时间格式，例如：1987-02-15 20:00:00';
                break;

            default:
                $remark = null;
                break;
        }

        $rules = $field->rules;
        $creationRules = $field->creationRules;
        $ruleMessage = $this->getFieldRuleMessage(array_merge($rules, $creationRules));

        if ($ruleMessage) {
            $remark = $remark .' 条件：'. $ruleMessage;
        }

        if ($remark) {
            $remark = '（' . $remark . '）';
        }

        return $remark;
    }

    /**
     * 导入字段的规则
     *
     * @param  object  $field
     * @return string
     */
    protected function getFieldRuleMessage($rules)
    {
        $message = [];

        foreach ($rules as $key => $value) {

            if(strpos($value,':') !== false) {
                $arr = explode(':',$value);
                $rule = $arr[0];
            } else {
                $rule = $value;
            }

            switch ($rule) {
                case 'required':
                    // 必填
                    $message[] = '必填';
                    break;

                case 'min':
                    // 最小字符串数
                    $message[] = '大于'.$arr[1].'个字符';
                    break;

                case 'max':
                    // 最大字符串数
                    $message[] = '小于'.$arr[1].'个字符';
                    break;

                case 'email':
                    // 必须为邮箱
                    $message[] = '必须为邮箱格式';
                    break;

                case 'numeric':
                    // 必须为数字
                    $message[] = '必须为数字格式';
                    break;

                case 'url':
                    // 必须为url
                    $message[] = '必须为链接格式';
                    break;

                case 'integer':
                    // 必须为整数
                    $message[] = '必须为整数格式';
                    break;

                case 'date':
                    // 必须为日期
                    $message[] = '必须为日期格式';
                    break;

                case 'boolean':
                    // 必须为布尔值
                    $message[] = '必须为布尔格式';
                    break;

                case 'unique':
                    // 必须为布尔值
                    $message[] = '不可重复';
                    break;

                default:

                    break;
            }
        }

        return implode('/',$message);
    }

    /**
     * 获取字段的可选值
     *
     * @param  array  $options
     * @return string
     */
    protected function getFieldOptionLabels($options)
    {
        $result = [];

        foreach ($options as $key => $option) {
            $result[] = $option['label'];
        }

        return implode('，',$result);
    }

    /**
     * 获取开关组件值
     *
     * @param  array  $options
     * @return string
     */
    protected function getSwitchLabels($options)
    {
        return $options['on'] . '，' . $options['off'];
    }
}