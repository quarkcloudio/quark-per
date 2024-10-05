<?php

namespace QuarkCloudIO\Quark\Component\Table\Search\Fields;

use QuarkCloudIO\Quark\Component\Table\Search\Item;

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
            $this->label = $label[0];
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
}
