<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

trait PerformsValidation
{
    /**
     * 创建请求的验证器
     *
     * @param  Request  $request
     * @param  object  $resource
     * @param  array  $data
     * 
     * @return Validator
     */
    public static function validatorForCreation(Request $request, $resource, $data=false)
    {
        $ruleData = static::rulesForCreation($request, $resource);

        return Validator::make($data ? $data : $request->all(), $ruleData['rules'], $ruleData['messages'])
                ->after(function ($validator) use ($request) {
                    static::afterValidation($request, $validator);
                    static::afterCreationValidation($request, $validator);
                });
    }

    /**
     * 创建请求的验证规则
     *
     * @param  Request  $request
     * @param  object  $resource
     * @return array
     */
    public static function rulesForCreation(Request $request, $resource)
    {
        $fields = $resource->creationFieldsWithoutWhen($request);
        $rules = [];
        $ruleMessages = [];

        foreach ($fields as $value) {

            $getResult = static::getRulesForCreation($request, $value);

            $rules = array_merge($rules, $getResult['rules']);
            $ruleMessages = array_merge($ruleMessages, $getResult['messages']);

            // when中的变量
            if(!empty($value->when)) {
                foreach ($value->when['items'] as $when) {
                    if(static::needValidateWhenRules($request, $when)) {
                        $body = $when['body'];
                        if(is_array($body)) {
                            foreach ($when['body'] as $whenItem) {
                                $whenItemResult = static::getRulesForCreation($request, $whenItem);
                                $rules = array_merge($rules, $whenItemResult['rules']);
                                $ruleMessages = array_merge($ruleMessages, $whenItemResult['messages']);
                            }
                        } elseif(is_object($body)) {
                            $whenItemResult = static::getRulesForCreation($request, $when['body']);
                            $rules = array_merge($rules, $whenItemResult['rules']);
                            $ruleMessages = array_merge($ruleMessages, $whenItemResult['messages']);
                        }
                    }
                }
            }
        }

        $result['rules'] = $rules;
        $result['messages'] = $ruleMessages;

        return $result;
    }

    /**
     * 判断是否需要验证When组件里的规则
     *
     * @param  Request  $request
     * @param  object  $when
     * @return array
     */
    protected static function needValidateWhenRules(Request $request, $when)
    {
        $conditionName = $when['condition_name'];
        $conditionOption = $when['condition_option'];
        $conditionOperator = $when['condition_operator'];

        $value = $request->input($conditionName);

        if(empty($value)) {
            return false;
        }

        switch ($conditionOperator) {
            case '=':
                $result = ($value == $conditionOption);
              break;
            case '>':
                $result = ($value > $conditionOption);
              break;
            case '<':
                $result = ($value < $conditionOption);
              break;
            case '<=':
                $result = ($value <= $conditionOption);
              break;
            case '>=':
                $result = ($value >= $conditionOption);
              break;
            case 'has':
                if(is_array($value)) {
                    $result = (strpos(json_encode($value), chr($conditionOption)) !== false);
                } else {
                    $result = (strpos($value, chr($conditionOption)) !== false);
                }
              break;
            case 'in':
                $result = (in_array($value, $conditionOption));
              break;
            default:
                $result = ($value == $conditionOption);
              break;
        }

        return $result;
    }

    /**
     * 获取创建请求资源规则
     *
     * @param  Request  $request
     * @param  array  $rules
     * @return array
     */
    protected static function getRulesForCreation(Request $request, $field)
    {
        foreach (static::formatRules($request, $field->rules) as $ruleKey => $ruleValue) {
            $getRules[$field->name][] = $ruleValue;
        }

        foreach ($field->ruleMessages as $messageKey => $messageValue) {
            $getRuleMessages[$field->name.'.'.$messageKey] = $messageValue;
        }

        foreach (static::formatRules($request, $field->creationRules) as $creationRuleKey => $creationRuleValue) {
            $getRules[$field->name][] = $creationRuleValue;
        }

        foreach ($field->creationRuleMessages as $creationMessageKey => $creationMessageValue) {
            $getRuleMessages[$field->name.'.'.$creationMessageKey] = $creationMessageValue;
        }

        $result['rules'] = $getRules ?? [];
        $result['messages'] = $getRuleMessages ?? [];

        return $result;
    }

    /**
     * 更新请求的验证器
     *
     * @param  Request  $request
     * @param  object  $resource
     * @param  array  $data
     * 
     * @return Validator
     */
    public static function validatorForUpdate(Request $request, $resource, $data=false)
    {
        $ruleData = static::rulesForUpdate($request, $resource);

        return Validator::make($data ? $data : $request->all(), $ruleData['rules'], $ruleData['messages'])
                ->after(function ($validator) use ($request) {
                    static::afterValidation($request, $validator);
                    static::afterImportValidation($request, $validator);
                });
    }

    /**
     * 更新请求的验证规则
     *
     * @param  Request  $request
     * @param  object  $resource
     * @return array
     */
    public static function rulesForUpdate(Request $request, $resource)
    {
        $fields = $resource->updateFieldsWithoutWhen($request);
        $rules = [];
        $ruleMessages = [];

        foreach ($fields as $value) {

            $getResult = static::getRulesForUpdate($request, $value);

            $rules = array_merge($rules, $getResult['rules']);
            $ruleMessages = array_merge($ruleMessages, $getResult['messages']);

            // when中的变量
            if(!empty($value->when)) {
                foreach ($value->when['items'] as $when) {
                    if(static::needValidateWhenRules($request, $when)) {
                        $body = $when['body'];
                        if(is_array($body)) {
                            foreach ($when['body'] as $whenItem) {
                                $whenItemResult = static::getRulesForUpdate($request, $whenItem);
                                $rules = array_merge($rules, $whenItemResult['rules']);
                                $ruleMessages = array_merge($ruleMessages, $whenItemResult['messages']);
                            }
                        } elseif(is_object($body)) {
                            $whenItemResult = static::getRulesForUpdate($request, $when['body']);
                            $rules = array_merge($rules, $whenItemResult['rules']);
                            $ruleMessages = array_merge($ruleMessages, $whenItemResult['messages']);
                        }
                    }
                }
            }
        }

        $result['rules'] = $rules;
        $result['messages'] = $ruleMessages;

        return $result;
    }

    /**
     * 获取更新请求资源规则
     *
     * @param  Request  $request
     * @param  array  $rules
     * @return array
     */
    protected static function getRulesForUpdate(Request $request, $field)
    {
        foreach (static::formatRules($request, $field->rules) as $ruleKey => $ruleValue) {
            $getRules[$field->name][] = $ruleValue;
        }

        foreach ($field->ruleMessages as $messageKey => $messageValue) {
            $getRuleMessages[$field->name.'.'.$messageKey] = $messageValue;
        }

        foreach (static::formatRules($request, $field->updateRules) as $updateRuleKey => $updateRuleValue) {
            $getRules[$field->name][] = $updateRuleValue;
        }

        foreach ($field->updateRuleMessages as $updateMessageKey => $updateMessageValue) {
            $getRuleMessages[$field->name.'.'.$updateMessageKey] = $updateMessageValue;
        }

        $result['rules'] = $getRules ?? [];
        $result['messages'] = $getRuleMessages ?? [];

        return $result;
    }

    /**
     * 导入请求的验证器
     *
     * @param  Request  $request
     * @param  object  $resource
     * @param  array  $data
     * 
     * @return Validator
     */
    public static function validatorForImport(Request $request, $resource, $data=false)
    {
        $ruleData = static::rulesForImport($request, $resource);

        return Validator::make($data ? $data : $request->all(), $ruleData['rules'], $ruleData['messages'])
                ->after(function ($validator) use ($request) {
                    static::afterValidation($request, $validator);
                    static::afterImportValidation($request, $validator);
                });
    }

    /**
     * 创建请求的验证规则
     *
     * @param  Request  $request
     * @param  object  $resource
     * @return array
     */
    public static function rulesForImport(Request $request, $resource)
    {
        $fields = $resource->importFieldsWithoutWhen($request);
        $rules = [];
        $ruleMessages = [];

        foreach ($fields as $value) {

            $getResult = static::getRulesForCreation($request, $value);

            $rules = array_merge($rules, $getResult['rules']);
            $ruleMessages = array_merge($ruleMessages, $getResult['messages']);

            // when中的变量
            if(!empty($value->when)) {
                foreach ($value->when['items'] as $when) {
                    if(static::needValidateWhenRules($request, $when)) {
                        $body = $when['body'];
                        if(is_array($body)) {
                            foreach ($when['body'] as $whenItem) {
                                $whenItemResult = static::getRulesForCreation($request, $whenItem);
                                $rules = array_merge($rules, $whenItemResult['rules']);
                                $ruleMessages = array_merge($ruleMessages, $whenItemResult['messages']);
                            }
                        } elseif(is_object($body)) {
                            $whenItemResult = static::getRulesForCreation($request, $when['body']);
                            $rules = array_merge($rules, $whenItemResult['rules']);
                            $ruleMessages = array_merge($ruleMessages, $whenItemResult['messages']);
                        }
                    }
                }
            }
        }

        $result['rules'] = $rules;
        $result['messages'] = $ruleMessages;

        return $result;
    }

    /**
     * 格式化规则
     *
     * @param  Request  $request
     * @param  array  $rules
     * @return array
     */
    protected static function formatRules(Request $request, $rules)
    {
        foreach ($rules as &$rule) {
            if(is_string($rule) && isset($request->id)) {
                $rule = str_replace('{id}', $request->id, $rule);
            }
        }

        return $rules;
    }

    /**
     * 验证完成后回调
     *
     * @param  Request  $request
     * @param  Validator  $validator
     * @return void
     */
    protected static function afterValidation(Request $request, $validator)
    {
        //
    }

    /**
     * 创建请求验证完成后回调
     *
     * @param  Request  $request
     * @param  Validator  $validator
     * @return void
     */
    protected static function afterCreationValidation(Request $request, $validator)
    {
        //
    }

    /**
     * 更新请求验证完成后回调
     *
     * @param  Request  $request
     * @param  Validator  $validator
     * @return void
     */
    protected static function afterUpdateValidation(Request $request, $validator)
    {
        //
    }

    /**
     * 创建请求验证完成后回调
     *
     * @param  Request  $request
     * @param  Validator  $validator
     * @return void
     */
    protected static function afterImportValidation(Request $request, $validator)
    {
        //
    }
}