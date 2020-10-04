<?php

namespace QuarkCMS\QuarkAdmin\Components\Form\Fields;

use QuarkCMS\QuarkAdmin\Components\Form\Item;
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

    public function display($display)
    {
        $this->display = $display;
        return $this;
    }
}
