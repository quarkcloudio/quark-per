<?php

namespace QuarkCMS\QuarkAdmin\Search\Fields;

use Illuminate\Support\Arr;
use QuarkCMS\QuarkAdmin\Search\Item;

class Group extends Item
{
    /**
     * 下拉菜单属性
     *
     * @var array
     */
    public $options = [];

    /**
     * 初始化
     *
     * @param  string  $name
     * @param  string  $label
     * @param  Closure  $callback
     * @return void
     */
    public function __construct($name,$label = '',$callback = null) {
        $this->component = 'inputGroup';
        $this->name = $name;
        $this->operator = 'group';

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $callback($this);
    }

    /**
     * 查询所有的条件
     *
     * @param  string  $label
     * @return $this
     */
    public function all($label = '')
    {
        $option['label'] = $label;
        $option['value'] = 'all';
        $this->options[] = $option;

        return $this;
    }

    /**
     * 查询等于的条件
     *
     * @param  string  $label
     * @return $this
     */
    public function equal($label = '')
    {
        $option['label'] = $label;
        $option['value'] = 'equal';
        $this->options[] = $option;

        return $this;
    }

    /**
     * 查询不等于的条件
     *
     * @param  string  $label
     * @return $this
     */
    public function notEqual($label = '')
    {
        $option['label'] = $label;
        $option['value'] = 'notEqual';
        $this->options[] = $option;

        return $this;
    }

    /**
     * 查询大于的条件
     *
     * @param  string  $label
     * @return $this
     */
    public function gt($label = '')
    {
        $option['label'] = $label;
        $option['value'] = 'gt';
        $this->options[] = $option;

        return $this;
    }

    /**
     * 查询小于的条件
     *
     * @param  string  $label
     * @return $this
     */
    public function lt($label = '')
    {
        $option['label'] = $label;
        $option['value'] = 'lt';
        $this->options[] = $option;

        return $this;
    }

    /**
     * 查询大于等于的条件
     *
     * @param  string  $label
     * @return $this
     */
    public function nlt($label = '')
    {
        $option['label'] = $label;
        $option['value'] = 'nlt';
        $this->options[] = $option;

        return $this;
    }

    /**
     * 查询小于等于的条件
     *
     * @param  string  $label
     * @return $this
     */
    public function ngt($label = '')
    {
        $option['label'] = $label;
        $option['value'] = 'ngt';
        $this->options[] = $option;

        return $this;
    }

    /**
     * 查询like的条件
     *
     * @param  string  $label
     * @return $this
     */
    public function like($label = '')
    {
        $option['label'] = $label;
        $option['value'] = 'like';
        $this->options[] = $option;

        return $this;
    }

    /**
     * 动态添加方法
     *
     * @param  string  $method
     * @param  array   $parameters
     * @return $this
     */
    public function __call($method, $parameters)
    {
        if(count($this->options)) {
            $this->options[count($this->options)-1]['method'][][$method] = $parameters;
        }

        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key(__CLASS__.$this->name.$this->label);

        return array_merge([
            'name' => $this->name,
            'label' => $this->label,
            'value' => $this->value,
            'defaultValue' => $this->defaultValue,
            'rules' => $this->rules,
            'placeholder' => $this->placeholder,
            'options' => $this->options
        ], parent::jsonSerialize());
    }
}
