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
     * @return Validator
     */
    public static function validatorForCreation(Request $request, $resource)
    {
        return Validator::make($request->all(), static::rulesForCreation($request, $resource))
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
        $fields = $resource->creationFields($request);

        foreach ($fields as $key => $value) {
            $getRules[$value->name] = $value->rules;
        }
    }

    /**
     * 更新请求的验证器
     *
     * @param  Request  $request
     * @param  object  $resource
     * @return Validator
     */
    public static function validatorForUpdate(Request $request, $resource)
    {
        return Validator::make($request->all(), static::rulesForUpdate($request, $resource))
                ->after(function ($validator) use ($request) {
                    static::afterValidation($request, $validator);
                    static::afterUpdateValidation($request, $validator);
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

    }

    /**
     * 解析验证提交数据库前的值
     *
     * @param array $data
     * @return array
     */
    public function itemValidator($data,$name,$rules,$ruleMessages)
    {
        $errorMsg = null;
        foreach ($rules as &$rule) {
            if(is_string($rule) && isset($data['id'])) {
                $rule = str_replace('{id}', $data['id'], $rule);
            }
        }
        $getRules[$name] = $rules;
        $validator = Validator::make($data,$getRules,$ruleMessages);
        if($validator->fails()) {
            $errors = $validator->errors()->getMessages();
            foreach($errors as $key => $value) {
                $errorMsg = $value[0];
            }
            return $errorMsg;
        }

        return $errorMsg;
    }

    /**
     * 验证提交数据库前的值
     *
     * @param array $data
     * @return array
     */
    public function validator($data = null)
    {
        $items = $this->items;

        if(empty($data)) {
            $data = $this->data;
        }

        foreach ($items as $key => $value) {

            // 通用验证规则
            if($value->rules) {
                $errorMsg = $this->itemValidator($data,$value->name,$value->rules,$value->ruleMessages);
                if($errorMsg) {
                    return $errorMsg;
                }
            }

            // 新增数据，验证规则
            if($this->isCreating()) {
                if($value->creationRules) {
                    $errorMsg = $this->itemValidator($data,$value->name,$value->creationRules,$value->creationRuleMessages);
                    if($errorMsg) {
                        return $errorMsg;
                    }
                }
            }

            // 编辑数据，验证规则
            if($this->isEditing()) {
                if($value->updateRules) {
                    $errorMsg = $this->itemValidator($data,$value->name,$value->updateRules,$value->updateRuleMessages);
                    if($errorMsg) {
                        return $errorMsg;
                    }
                }
            }

            // when中的变量
            if(!empty($value->when)) {
                foreach ($value->when as $when) {
                    foreach ($when['items'] as $whenItem) {
                        // 通用验证规则
                        if($whenItem->rules) {
                            $errorMsg = $this->itemValidator($data,$whenItem->name,$whenItem->rules,$whenItem->ruleMessages);
                            if($errorMsg) {
                                return $errorMsg;
                            }
                        }

                        // 新增数据，验证规则
                        if($this->isCreating()) {
                            if($whenItem->creationRules) {
                                $errorMsg = $this->itemValidator($data,$whenItem->name,$whenItem->creationRules,$whenItem->creationRuleMessages);
                                if($errorMsg) {
                                    return $errorMsg;
                                }
                            }
                        }

                        // 编辑数据，验证规则
                        if($this->isEditing()) {
                            if($whenItem->updateRules) {
                                $errorMsg = $this->itemValidator($data,$whenItem->name,$whenItem->updateRules,$whenItem->updateRuleMessages);
                                if($errorMsg) {
                                    return $errorMsg;
                                }
                            }
                        }
                    }
                }
            }
        }
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
}