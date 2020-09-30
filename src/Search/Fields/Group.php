<?php

namespace QuarkCMS\QuarkAdmin\Search\Fields;

use Illuminate\Support\Arr;
use QuarkCMS\QuarkAdmin\Search\Item;

class Group extends Item
{

    public $options = [];

    function __construct($name,$label = '',$callback = null) {
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

    // 所有
    public function all($label = '')
    {
        $option['label'] = $label;
        $option['value'] = 'all';
        $this->options[] = $option;

        return $this;
    }

    // 等于
    public function equal($label = '')
    {
        $option['label'] = $label;
        $option['value'] = 'equal';
        $this->options[] = $option;

        return $this;
    }

    // 不等于
    public function notEqual($label = '')
    {
        $option['label'] = $label;
        $option['value'] = 'notEqual';
        $this->options[] = $option;

        return $this;
    }

    // 大于
    public function gt($label = '')
    {
        $option['label'] = $label;
        $option['value'] = 'gt';
        $this->options[] = $option;

        return $this;
    }

    // 小于
    public function lt($label = '')
    {
        $option['label'] = $label;
        $option['value'] = 'lt';
        $this->options[] = $option;

        return $this;
    }

    // 大于等于
    public function nlt($label = '')
    {
        $option['label'] = $label;
        $option['value'] = 'nlt';
        $this->options[] = $option;

        return $this;
    }

    // 小于等于
    public function ngt($label = '')
    {
        $option['label'] = $label;
        $option['value'] = 'ngt';
        $this->options[] = $option;

        return $this;
    }

    // like查询
    public function like($label = '')
    {
        $option['label'] = $label;
        $option['value'] = 'like';
        $this->options[] = $option;

        return $this;
    }

    public function __call($method, $arguments)
    {
        if(count($this->options)) {
            $this->options[count($this->options)-1]['method'][][$method] = $arguments;
        }

        return $this;
    }
}
