<?php

namespace QuarkCloudIO\Quark\Component\Form\Fields;

use QuarkCloudIO\Quark\Component\Form\Fields\Item;
use Exception;

class Select extends Item
{
    /**
     * 组件类型
     *
     * @var string
     */
    public $component = 'selectField';

    /**
     * 根据 options 生成子节点，推荐使用。
     *
     * @var array
     */
    public $options = [];

    /**
     * 设置 Select 的模式为多选或标签，multiple | tags
     *
     * @var string
     */
    public $mode = null;

    /**
     * 控件大小。注：标准表单内的输入框大小限制为 large。可选 large default small
     *
     * @var string
     */
    public $size = null;

    /**
     * 可以点击清除图标删除内容
     *
     * @var bool
     */
    public $allowClear = true;

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
     * 组件样式
     *
     * @var array
     */
    public $style = ['width' => 200];

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
     * 设置多选框属性
     *
     * @param  array $options
     * @return $this
     */
    public function options($options)
    {
        $data = [];
        foreach ($options as $key => $value) {
            $option['label'] = $value;
            $option['value'] = $key;
            $data[] = $option;
        }
        $this->options = $data;

        return $this;
    }

    /**
     * 当前列值的枚举 valueEnum
     *
     * @return array
     */
    public function getValueEnum()
    {
        foreach ($this->options as $option) {
            $options[$option['value']] = $option['label'];
        }

        return $options;
    }

    /**
     * 设置 Select 的模式为多选或标签，multiple | tags
     *
     * @param  string $mode
     * @return $this
     */
    public function mode($mode)
    {
        if(!in_array($mode,['multiple', 'tags'])) {
            throw new Exception("argument must be in 'multiple', 'tags'!");
        }
        $this->mode = $mode;

        return $this;
    }

    /**
     * 可以点击清除图标删除内容
     * 
     * @param  string $allowClear
     * @return $this
     */
    public function allowClear($allowClear = true)
    {
        $allowClear ? $this->allowClear = true : $this->allowClear = false;
        return $this;
    }

    /**
     * 控件占位符
     *
     * @param  string $placeholder
     * @return $this
     */
    public function placeholder($placeholder = '')
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * 控件大小。注：标准表单内的输入框大小限制为 large。可选 large default small
     * 
     * @param  large|default|small $prefix
     * @return $this
     */
    public function size($size = 'default')
    {
        if(!in_array($size,['large', 'default', 'small'])) {
            throw new Exception("argument must be in 'large', 'default', 'small'!");
        }

        $this->size = $size;
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
            'options' => $this->options,
            'placeholder' => $this->placeholder ?? '请选择'.$this->label,
            'allowClear' => $this->allowClear,
            'size' => $this->size,
            'mode' => $this->mode,
            'load' => $this->load
        ], parent::jsonSerialize());
    }
}
