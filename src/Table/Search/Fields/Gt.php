<?php

namespace QuarkCMS\QuarkAdmin\Table\Search\Fields;

use Illuminate\Support\Arr;
use QuarkCMS\QuarkAdmin\Table\Search\Item;
use Exception;

class Gt extends Item
{
    function __construct($name,$label = '') {
        $this->component = 'input';
        $this->name = $name;
        $this->operator = 'gt';

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $this->placeholder = '请输入'.$this->label;
    }
}
