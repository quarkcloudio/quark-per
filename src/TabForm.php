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
                    $value = $this->parseInitialValue($item,$initialValues);
                    if($value !== null) {
                        $data[$item->name] = $value;
                    }

                    // when中的变量
                    if(!empty($item->when)) {
                        foreach ($item->when as $when) {
                            foreach ($when['items'] as $whenItem) {
                                $whenValue = $this->parseInitialValue($whenItem,$initialValues);
                                if($whenValue !== null) {
                                    $data[$whenItem->name] = $whenValue;
                                }
                            }
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
        }
    }

    protected function parseSubmitData($data)
    {
        if(isset($this->tab)) {
            foreach ($this->tab as $tabKey => $tabValue) {
                foreach ($tabValue['items'] as $key => $item) {
                    // 删除忽略的值
                    if($item->ignore) {
                        if(isset($data[$item->name])) {
                            unset($data[$item->name]);
                        }
                    }

                    // when中的变量
                    if(!empty($item->when)) {
                        foreach ($item->when as $when) {
                            foreach ($when['items'] as $whenItem) {
                                // 删除忽略的值
                                if($whenItem->ignore) {
                                    if(isset($data[$whenItem->name])) {
                                        unset($data[$whenItem->name]);
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        foreach ($data as $key => $value) {
            if(is_array($value)) {
                $data[$key] = json_encode($value);
            }
        }

        return $data;
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
