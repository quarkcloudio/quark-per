<?php

namespace QuarkCMS\QuarkAdmin\Components\Form\Fields;

use QuarkCMS\QuarkAdmin\Components\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class SwitchField extends Item
{
    public  $options;

    function __construct($name,$label = '') {
        $this->component = 'switch';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }
    }

    public function options($options)
    {
        $this->options = $options;

        return $this;
    }
}
