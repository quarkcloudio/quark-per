<?php

namespace QuarkCMS\QuarkAdmin\Grid\Search\Fields;

use Illuminate\Support\Arr;
use QuarkCMS\QuarkAdmin\Grid\Search\Item;
use Exception;

class Where extends Item
{

    public $methods = [];

    function __construct($name,$label = '',$callback = null) {
        $this->component = 'input';
        $this->name = $name;
        $this->operator = 'where';

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $callback($this);
    }

    public function __call($method, $arguments)
    {
        $this->methods[][$method] = $arguments;

        return $this;
    }
}
