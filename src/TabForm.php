<?php

namespace QuarkCMS\QuarkAdmin;

use Closure;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Facades\Validator;

class TabForm extends Form
{
    /**
     * Tab布局的表单
     *
     * @var array
     */
    public $tab = null;

    /**
     * 初始化容器
     *
     * @param  string  $name
     * @param  \Closure|array  $content
     * @return void
     */
    public function __construct(Eloquent $model)
    {
        $this->component = 'tabForm';
        $this->model = $model;

        // 初始化表单提交地址
        $this->initApi();

        // 初始化表单提交数据
        $this->initData();

        return $this;
    }

    /**
     *  表单默认值，只有初始化以及重置时生效
     *
     * @param  array  $initialValues
     * @return $this
     */
    public function initialValues($initialValues = null)
    {
        $data = [];

        if(isset($this->tab)) {
            foreach ($this->tab as $tabKey => $tabValue) {
                foreach ($tabValue['items'] as $key => $item) {
                    if(isset($item->name)) {
                        if(isset($item->defaultValue)) {
                            $data[$item->name] = $item->defaultValue;
                        }

                        if(isset($initialValues[$item->name])) {
                            $data[$item->name] = $initialValues[$item->name];
                        }

                        if(isset($item->value)) {
                            $data[$item->name] = $item->value;
                        }
                    }
                }
            }
        }

        $this->initialValues = $data;
        return $this;
    }

    /**
     * 验证提交数据库前的值
     *
     * @param array $data
     * @return array
     */
    public function validator($data = null)
    {
        if(empty($data)) {
            $data = $this->data;
        }

        if(isset($this->tab)) {
            foreach ($this->tab as $tabKey => $tabValue) {
                foreach ($tabValue['items'] as $key => $value) {

                    // 通用验证规则
                    if($value->rules) {
                        foreach ($value->rules as &$rule) {
                            if (is_string($rule) && isset($data['id'])) {
                                $rule = str_replace('{id}', $data['id'], $rule);
                            }
                        }
                        $rules[$value->name] = $value->rules;
                        $validator = Validator::make($data,$rules,$value->ruleMessages);
                        if ($validator->fails()) {
                            $errors = $validator->errors()->getMessages();
                            foreach($errors as $key => $value) {
                                $errorMsg = $value[0];
                            }
                            return $errorMsg;
                        }
                    }
        
                    // 新增数据，验证规则
                    if($this->isCreating()) {
                        if($value->creationRules) {
                            $creationRules[$value->name] = $value->creationRules;
                            $validator = Validator::make($data,$creationRules,$value->creationRuleMessages);
                            if ($validator->fails()) {
                                $errors = $validator->errors()->getMessages();
                                foreach($errors as $key => $value) {
                                    $errorMsg = $value[0];
                                }
                                return $errorMsg;
                            }
                        }
                    }
        
                    // 编辑数据，验证规则
                    if($this->isEditing()) {
                        if($value->updateRules) {
                            foreach ($value->updateRules as &$rule) {
                                if (is_string($rule)) {
                                    $rule = str_replace('{id}', $data['id'], $rule);
                                }
                            }
                            $updateRules[$value->name] = $value->updateRules;
                            $validator = Validator::make($data,$updateRules,$value->updateRuleMessages);
                            if ($validator->fails()) {
                                $errors = $validator->errors()->getMessages();
                                foreach($errors as $key => $value) {
                                    $errorMsg = $value[0];
                                }
                                return $errorMsg;
                            }
                        }
                    }
                }
            }
        }
    }

    /**
     * tab布局的表单
     *
     * @return bool
     */
    public function tab($title,Closure $callback = null)
    {
        $callback($this);

        $tab['title'] = $title;
        if(isset($this->items)) {
            $tab['items'] = $this->items;
            $this->items = [];
        }
        $this->tab[] = $tab;

        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge([
            'tab' => $this->tab
        ], parent::jsonSerialize());
    }
}
