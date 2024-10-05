<?php

namespace QuarkCloudIO\Quark\Component\Table\Search\Fields;

use QuarkCloudIO\Quark\Component\Table\Search\Item;

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
            $this->label = $label[0];
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
}
