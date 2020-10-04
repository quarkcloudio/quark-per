<?php

namespace QuarkCMS\QuarkAdmin\Components\Form\Fields;

use QuarkCMS\QuarkAdmin\Components\Form\Item;
use QuarkCMS\QuarkAdmin\Components\Form;
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
