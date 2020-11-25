<?php

namespace QuarkCMS\QuarkAdmin\Search;

use Exception;
use QuarkCMS\QuarkAdmin\Element;

class Item extends Element
{
    /**
     * 设置保存值。
     *
     * @var array|string|number|bool
     */
    public $value = null;

    /**
     * 设置默认值。
     *
     * @var array|string|number|bool
     */
    public $defaultValue = null;

    /**
     * label 标签的文本
     *
     * @var string
     */
    public $label = null;

    /**
     * 字段名，支持数组
     *
     * @var string|array
     */
    public $name = null;

    /**
     * 下拉菜单类型属性
     *
     * @var array
     */
    public $options = [];

    /**
     * 校验规则，设置字段的校验逻辑
     *
     * @var string|array
     */
    public $rules = [];

    /**
     * 查询操作符
     *
     * @var string
     */
    public $operator = null;

    /**
     * 控件占位符
     *
     * @var string
     */
    public $placeholder = null;

    /**
     * label 标签的文本
     *
     * @param string $label
     * @return $this
     */
    public function label($label = '')
    {
        $this->label = $label;
        return $this;
    }

    /**
     * 字段名，支持数组
     *
     * @param string $name
     * @return $this
     */
    public function name($name = '')
    {
        $this->name = $name;
        return $this;
    }

    /**
     * 校验规则，设置字段的校验逻辑
     *
     * @param array|$this $rules
     * @return $this
     */
    public function rules($rules, $messages = null)
    {
        $this->rules = $rules;
        $this->ruleMessages = $messages;
        return $this;
    }

    /**
     * 设置保存值。
     *
     * @param array|string
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
     * @param array|string
     * @return $this
     */
    public function default($value)
    {
        $this->defaultValue = $value;
        return $this;
    }

    /**
     * placeholder
     *
     * @param string $placeholder
     * @return object
     */
    public function placeholder($placeholder)
    {
        if ($this->operator == 'between') {
            if (!is_array($placeholder)) {
                throw new Exception("argument must be an array!");
            }
        }

        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * 控件宽度
     *
     * @param number|string $value
     * @return object
     */
    public function width($value = '100%')
    {
        $style['width'] = $value;
        $this->style = $style;
        return $this;
    }

    /**
     * 下拉菜单控件
     *
     * @param array $options
     * @return object
     */
    public function select($options)
    {
        $this->component = 'select';

        $data = [];
        foreach ($options as $key => $value) {
            $option['label'] = $value;
            $option['value'] = $key;
            $data[] = $option;
        }
        $this->options = $data;

        $this->placeholder = '请选择' . $this->label;
        return $this;
    }

    /**
     * 多选下拉菜单控件
     *
     * @param array $options
     * @return object
     */
    public function multipleSelect($options)
    {
        $this->component = 'multipleSelect';

        $data = [];
        foreach ($options as $key => $value) {
            $option['label'] = $value;
            $option['value'] = $key;
            $data[] = $option;
        }
        $this->options = $data;

        $this->placeholder = '请选择' . $this->label;
        return $this;
    }

    /**
     * 时间控件
     *
     * @param string $options
     * @return object
     */
    public function datetime($options = [])
    {
        $this->component = 'datetime';
        $this->options = $options;

        return $this;
    }

    /**
     * 日期控件
     *
     * @param string $options
     * @return object
     */
    public function date($options = [])
    {
        $this->component = 'date';
        $this->options = $options;

        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key(__CLASS__ . $this->name . $this->label);

        return array_merge([
            'name' => $this->name,
            'label' => $this->label,
            'value' => $this->value,
            'defaultValue' => $this->defaultValue,
            'rules' => $this->rules,
            'placeholder' => $this->placeholder,
            'operator' => $this->operator,
            'options' => $this->options
        ], parent::jsonSerialize());
    }
}
