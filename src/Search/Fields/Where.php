<?php

namespace QuarkCMS\QuarkAdmin\Search\Fields;

use Illuminate\Support\Arr;
use QuarkCMS\QuarkAdmin\Search\Item;

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
        $this->component = 'input';
        $this->name = $name;
        $this->operator = 'where';

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
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
