<?php

namespace QuarkCMS\QuarkAdmin\Form\Fields;

use QuarkCMS\QuarkAdmin\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class Search extends Item
{
    public  $mode;

    function __construct($name,$label = '') {
        $this->component = 'search';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }
    }

    /**
     * 创建组件
     *
     * @param  string $name
     * @param  string $label
     * @return object
     */
    static function make($name,$label = '')
    {
        $self = new self();

        $self->name = $name;
        if(empty($label)) {
            $self->label = $name;
        } else {
            $self->label = $label;
        }

        $self->placeholder = '请输入'.$labelName;

        // 删除空属性
        $self->unsetNullProperty();
        return $self;
    }

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

    public function mode($mode)
    {
        $this->mode = $mode;
        $this->defaultValue = [];
        return $this;
    }

    public function ajax($url)
    {
        $this->url = $url;
        return $this;
    }

    /**
     * 输入框宽度
     * 
     * @param  number|string $value
     * @return object
     */
    public function width($value = '100%')
    {
        $style['width'] = $value;
        $this->style = $style;
        return $this;
    }
}
