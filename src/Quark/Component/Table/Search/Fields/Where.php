<?php

namespace QuarkCloudIO\Quark\Component\Table\Search\Fields;

use QuarkCloudIO\Quark\Component\Table\Search\Item;

class Where extends Item
{
    /**
     * 动态调用的方法
     *
     * @var array
     */
    public $methods = [];

    /**
     * 初始化
     *
     * @param  string  $name
     * @param  string  $label
     * @param  Closure  $callback
     * @return void
     */
    public function __construct($name,$label = '',$callback = null) {
        $this->component = 'text';
        $this->name = $name;
        $this->operator = 'where';

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $this->label = $label[0];
        }

        $callback($this);
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
        $this->methods[][$method] = $parameters;

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
            'methods' => $this->methods
        ], parent::jsonSerialize());
    }
}
