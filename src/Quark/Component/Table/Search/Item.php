<?php

namespace QuarkCloudIO\Quark\Component\Table\Search;

use Exception;
use QuarkCloudIO\Quark\Component\Element;

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
     * 级联菜单接口
     *
     * @var string
     */
    public $api = null;

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
     * 单向联动
     *
     * @var array
     */
    public $load = null;

    /**
     * 初始化
     *
     * @param  string  $name
     * @param  string  $label
     * @return void
     */
    public function __construct($name, $label = '') {
        $this->component = 'text';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $this->label = $label[0];
        }

        $this->placeholder = '请输入'.$this->label;
    }

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
     * 操作符
     *
     * @param string
     * @return $this
     */
    public function operator($operator)
    {
        $this->operator = $operator;

        if($this->operator == 'between') {
            $this->placeholder = ['开始' . $this->label, '结束' . $this->label];
        }

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
        if($this->operator == 'between') {
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
     * 级联菜单接口
     *
     * @param string $api
     * @return object
     */
    public function api($api)
    {
        $this->api = $api;

        return $this;
    }

    /**
     * 输入框控件
     *
     * @param string $options
     * @return object
     */
    public function input($options = [])
    {
        $this->component = 'input';
        $this->options = $options;

        return $this;
    }

    /**
     * 下拉菜单控件
     *
     * @param array $options
     * @return object
     */
    public function select($options = [])
    {
        $this->component = 'select';

        $data = [];
        foreach ($options as $key => $value) {
            $option['label'] = $value;
            $option['value'] = $key;
            $data[] = $option;
        }
        $this->options = $data;

        if($this->operator == 'between') {
            $this->placeholder = ['开始' . $this->label, '结束' . $this->label];
        } else {
            $this->placeholder = '请选择' . $this->label;
        }

        return $this;
    }

    /**
     * 多选下拉菜单控件
     *
     * @param array $options
     * @return object
     */
    public function multipleSelect($options = [])
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
     * 单向联动
     *
     * @param  string $field
     * @param  string $api
     * @return $this
     */
    public function load($field, $api)
    {
        $data['field'] = $field;
        $data['api'] = $api;
        $this->load = $data;

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
     * 级联菜单
     *
     * @param array $options
     * @return object
     */
    public function cascader($options = [])
    {
        $this->component = 'cascader';

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
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        // 设置组件唯一标识
        if(empty($this->key)) {
            $this->key(__CLASS__ . $this->name . $this->label, true);
        }

        return array_merge([
            'name' => $this->name,
            'label' => $this->label,
            'value' => $this->value,
            'defaultValue' => $this->defaultValue,
            'rules' => $this->rules,
            'placeholder' => $this->placeholder,
            'operator' => $this->operator,
            'options' => $this->options,
            'load' => $this->load,
            'api' => $this->api
        ], parent::jsonSerialize());
    }
}
