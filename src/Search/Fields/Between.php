<?php

namespace QuarkCMS\QuarkAdmin\Table\Search\Fields;

use Illuminate\Support\Arr;
use QuarkCMS\QuarkAdmin\Table\Search\Item;
use Exception;

class Between extends Item
{
    function __construct($name,$label = '') {
        $this->component = 'input';
        $this->name = $name;
        $this->operator = 'between';

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $placeholder[0] = '开始'.$this->label;
        $placeholder[1] = '结束'.$this->label;
        $this->placeholder = $placeholder;
    }
}
