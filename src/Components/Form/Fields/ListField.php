<?php

namespace QuarkCMS\QuarkAdmin\Form\Fields;

use QuarkCMS\QuarkAdmin\Form\Item;
use QuarkCMS\QuarkAdmin\Form;
use Illuminate\Support\Arr;
use Closure;
use Exception;

class ListField extends Item
{
    public  $items;

    public  $button;

    function __construct($name,$label = '') {
        $this->component = 'list';
        $this->name = $name;

        $this->button = '添加字段';

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

        // 删除空属性
        $self->unsetNullProperty();
        return $self;
    }

    public function button($text)
    {
        $this->button = $text;
        return $this;
    }

    public function item(Closure $callback = null)
    {
        $form = new form();

        $callback($form);

        $this->items = $form->form['items'];
        return $this;
    }
}
