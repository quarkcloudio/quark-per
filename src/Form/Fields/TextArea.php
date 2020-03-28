<?php

namespace QuarkCMS\QuarkAdmin\Form\Fields;

use QuarkCMS\QuarkAdmin\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class TextArea extends Item
{
    public  $autosize,
            $rows;

    function __construct($name,$label = '') {
        $this->component = 'textArea';
        $this->name = $name;

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

    /**
     * placeholder
     *
     * @param  string $placeholder
     * @return object
     */
    public function placeholder($placeholder = '')
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    public function autosize($autosize)
    {
        $this->autosize = $autosize;
        return $this;
    }

    public function rows($rows)
    {
        $this->rows = $rows;
        return $this;
    }
}
