<?php

namespace QuarkCMS\QuarkAdmin\Components\Form;

use QuarkCMS\QuarkAdmin\Element;
use Exception;

class Item extends Element
{
    /**
     * 会在 label 旁增加一个 icon，悬浮后展示配置的信息
     *
     * @var string
     */
    public $tooltip = null;

    /**
     * Field 的长度，我们归纳了常用的 Field 长度以及适合的场景，支持了一些枚举 "xs" , "s" , "m" , "l" , "x"
     *
     * @var string
     */
    public $width = null;

    /**
     * 配合 label 属性使用，表示是否显示 label 后面的冒号
     *
     * @var bool
     */
    public $colon = true;

    /**
     * 设置保存值。
     *
     * @var bool|string|number|array
     */
    public $value = null;

    /**
     * 设置默认值。
     *
     * @var bool|string|number|array
     */
    public $defaultValue = null;

    /**
     * 额外的提示信息，和 help 类似，当需要错误信息和提示文案同时出现时，可以使用这个。
     *
     * @var string
     */
    public $extra = null;

    /**
     * 配合 validateStatus 属性使用，展示校验状态图标，建议只配合 Input 组件使用
     *
     * @var string
     */
    public $hasFeedback;

    /**
     * 额外的提示信息。
     *
     * @var string
     */
    public $help = null;

    /**
     * 为 true 时不带样式，作为纯字段控件使用
     *
     * @var bool
     */
    public $noStyle = false;

    /**
     * label 标签的文本
     *
     * @var string
     */
    public $label = null;

    /**
     * 标签文本对齐方式,left | right
     *
     * @var string
     */
    public $labelAlign = 'right';

    /**
     * label 标签布局，同 <Col> 组件，设置 span offset 值，如 {span: 3, offset: 12} 或 sm: {span: 3, offset: 12}。
     * 你可以通过 Form 的 labelCol 进行统一设置。当和 Form 同时设置时，以 Item 为准
     *
     * @var array
     */
    public $labelCol = [];

    /**
     * 字段名，支持数组
     *
     * @var string|array
     */
    public $name = null;

    /**
     * 是否必填，如不设置，则会根据校验规则自动生成
     *
     * @var bool
     */
    public $required = false;

    /**
     * 控件是否禁用
     *
     * @var bool
     */
    public $disabled = false;

    /**
     * 校验规则，设置字段的校验逻辑
     *
     * @var array
     */
    public $rules = null;

    /**
     * 校验规则的提示信息
     *
     * @var array
     */
    public $ruleMessages = null;

    /**
     * 创建的校验规则，设置字段的校验逻辑
     *
     * @var array
     */
    public $creationRules = null;

    /**
     * 创建的校验规则提示信息
     *
     * @var array
     */
    public $creationRuleMessages = null;

    /**
     * 更新的校验规则，设置字段的校验逻辑
     *
     * @var array
     */
    public $updateRules = null;

    /**
     * 更新的校验规则提示信息
     *
     * @var array
     */
    public $updateRuleMessages = null;

    /**
     * 前台的校验规则
     *
     * @var array
     */
    public $frontendRules = null;

    /**
     * 子节点的值的属性，如 Switch 的是 'checked'
     *
     * @var string
     */
    public $valuePropName = null;

    /**
     * 需要为输入控件设置布局样式时，使用该属性，用法同 labelCol。
     * 你可以通过 Form 的 wrapperCol 进行统一设置。当和 Form 同时设置时，以 Item 为准。
     *
     * @var array
     */
    public $wrapperCol = null;

    /**
     * 会在 label 旁增加一个 icon，悬浮后展示配置的信息
     *
     * @param  string $tooltip
     * @return $this
     */
    public function tooltip($tooltip)
    {
        $this->tooltip = $tooltip;
        return $this;
    }

    /**
     * Field 的长度，我们归纳了常用的 Field 长度以及适合的场景，支持了一些枚举 "xs" , "s" , "m" , "l" , "x"
     *
     * @param  string $width
     * @return $this
     */
    public function width($width)
    {
        if(!in_array($width,['xs','s','m','l','x'])) {
            throw new Exception("argument must be 'xs','s','m','l','x'!");
        }

        $this->width = $width;
        return $this;
    }

    /**
     * 配合 label 属性使用，表示是否显示 label 后面的冒号
     *
     * @param  bool $colon
     * @return $this
     */
    public function colon($colon = true)
    {
        $colon ? $this->colon = true : $this->colon = false;
        return $this;
    }

    /**
     * 额外的提示信息，和 help 类似，当需要错误信息和提示文案同时出现时，可以使用这个。
     *
     * @param  string $extra
     * @return $this
     */
    public function extra($extra = '')
    {
        $this->extra = $extra;
        return $this;
    }

    /**
     * 配合 validateStatus 属性使用，展示校验状态图标，建议只配合 Input 组件使用
     *
     * @param  bool $hasFeedback
     * @return $this
     */
    public function hasFeedback($hasFeedback = true)
    {
        $hasFeedback ? $this->hasFeedback = true : $this->hasFeedback = false;
        return $this;
    }

    /**
     * 配合 help 属性使用，展示校验状态图标，建议只配合 Input 组件使用
     *
     * @param  bool $help
     * @return $this
     */
    public function help($help = '')
    {
        $this->help = $help;
        return $this;
    }

    /**
     * 为 true 时不带样式，作为纯字段控件使用
     *
     * @return $this
     */
    public function noStyle()
    {
        $this->noStyle = true;
        return $this;
    }

    /**
     * label 标签的文本
     *
     * @param  string $label
     * @return $this
     */
    public function label($label = '')
    {
        $this->label = $label;
        return $this;
    }

    /**
     * 标签文本对齐方式
     *
     * @param  left|right $align
     * @return $this
     */
    public function labelAlign($align = 'right')
    {
        if(!in_array($align,['left','right'])) {
            throw new Exception("argument must be 'left' or 'right'!");
        }

        $this->labelAlign = $align;
        return $this;
    }

    /**
     * label 标签布局，同 <Col> 组件，设置 span offset 值，如 {span: 3, offset: 12} 或 sm: {span: 3, offset: 12}。
     * 你可以通过 Form 的 labelCol 进行统一设置。当和 Form 同时设置时，以 Item 为准
     *
     * @param  array|$this $col
     * @return $this
     */
    public function labelCol($col)
    {
        if(!is_array($col)) {
            throw new Exception("argument must be an array!");
        }

        $this->labelCol = $col;
        return $this;
    }

    /**
     * 字段名，支持数组
     *
     * @param  string $name
     * @return $this
     */
    public function name($name = '')
    {
        $this->name = $name;
        return $this;
    }

    /**
     * 是否必填，如不设置，则会根据校验规则自动生成
     * 
     * @return $this
     */
    public function required()
    {
        $this->required = true;
        return $this;
    }

    /**
     * 校验规则，设置字段的校验逻辑
     * 
     * @param  array|$this $rules
     * @return $this
     */
    public function rules($rules,$messages = null)
    {
        $this->rules = $rules;
        $this->ruleMessages = $messages;
        return $this;
    }

    /**
     * 校验规则，只在创建表单提交时生效
     * 
     * @param  array|$this $rules
     * @return $this
     */
    public function creationRules($rules,$messages = null)
    {
        $this->creationRules = $rules;
        $this->creationRuleMessages = $messages;
        return $this;
    }

    /**
     * 校验规则，只在更新表单提交时生效
     * 
     * @param  array|$this $rules
     * @return $this
     */
    public function updateRules($rules,$messages = null)
    {
        $this->updateRules = $rules;
        $this->updateRuleMessages = $messages;
        return $this;
    }

    /**
     * 子节点的值的属性，如 Switch 的是 'checked'
     * 
     * @param  string $valuePropName
     * @return $this
     */
    public function valuePropName($valuePropName = '')
    {
        $this->valuePropName = $valuePropName;
        return $this;
    }

    /**
     * 需要为输入控件设置布局样式时，使用该属性，用法同 labelCol。
     * 你可以通过 Form 的 wrapperCol 进行统一设置。当和 Form 同时设置时，以 Item 为准。
     *
     * @param  array|$this $col
     * @return $this
     */
    public function wrapperCol($col)
    {
        if(!is_array($col)) {
            throw new Exception("argument must be an array!");
        }

        $this->wrapperCol = $col;
        return $this;
    }

    /**
     * 设置保存值。
     *
     * @param  array|string
     * @return $this
     */
    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * 设置默认值。
     *
     * @param  array|string
     * @return $this
     */
    public function default($value)
    {
        $this->defaultValue = $value;
        return $this;
    }

    /**
     * 是否禁用状态，默认为 false
     * 
     * @param  bool $status
     * @return object
     */
    public function disabled($status = true)
    {
        $status ? $this->disabled = true : $this->disabled = false;
        return $this;
    }

    protected function unsetNullProperty()
    {
        foreach ($this as $key => $value) {
            if(empty($value)) {
                unset($this->$key);
            }
        }
    }
}
