<?php

namespace QuarkCMS\QuarkAdmin\Form\Fields;

use QuarkCMS\QuarkAdmin\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class Editor extends Item
{
    function __construct($name,$label = '') {
        $this->component = 'editor';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $this->placeholder = '请输入'.$this->label;

        $style = ['height' => 400, 'boxShadow' => 'inset 0 1px 3px rgba(0,0,0,.1)'];

        $this->style = $style;
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

        $self->placeholder = '请输入'.$label;

        // 删除空属性
        $self->unsetNullProperty();
        return $self;
    }

    /**
     * 宽度
     * 
     * @param  number|string $value
     * @return object
     */
    public function width($value = '100%')
    {
        $this->style['width'] = $value;
        return $this;
    }

    /**
     * 高度
     * 
     * @param  number|string $value
     * @return object
     */
    public function height($value = 500)
    {
        $this->style['height'] = $value;
        return $this;
    }
}
