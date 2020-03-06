<?php

namespace QuarkCMS\QuarkAdmin\Grid\Search\Fields;

use Illuminate\Support\Arr;
use QuarkCMS\QuarkAdmin\Grid\Search\Item;
use Exception;

class Like extends Item
{
    function __construct($name,$label = '') {
        $this->component = 'input';
        $this->name = $name;
        $this->operator = 'like';

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $this->placeholder = '请输入'.$this->label;
    }
}
