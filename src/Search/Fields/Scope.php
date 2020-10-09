<?php

namespace QuarkCMS\QuarkAdmin\Search\Fields;

use Illuminate\Support\Arr;
use QuarkCMS\QuarkAdmin\Search\Item;

class Scope extends Item
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
        $this->component = 'select';
        $this->name = $name;
        $this->operator = 'scope';

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $callback($this);
    }

    /**
     * 下拉菜单属性
     *
     * @param  string  $value
     * @param  string  $label
     * @return void
     */
    public function option($value,$label = '')
    {
        $option['label'] = $label;
        $option['value'] = $value;
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
