<?php

namespace QuarkCMS\QuarkAdmin\Form\Fields;

use QuarkCMS\QuarkAdmin\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class ID extends Item
{
    public  $display;

    function __construct($name,$label = '') {
        $this->component = 'id';
        $this->type = 'text';
        $this->name = $name;
        $this->display = 'none';

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $this->placeholder = '请输入'.$this->label;
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

    public function display($display)
    {
        $this->display = $display;
        return $this;
    }
}
