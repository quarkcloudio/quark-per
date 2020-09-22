<?php

namespace QuarkCMS\QuarkAdmin\Table\Search\Fields;

use Illuminate\Support\Arr;
use QuarkCMS\QuarkAdmin\Table\Search\Item;
use Exception;

class Scope extends Item
{

    public $options = [];

    function __construct($name,$label = '',$callback = null) {
        $this->component = 'select';
        $this->name = $name;
        $this->operator = 'scope';

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $callback($this);
    }

    public function option($value,$label = '')
    {
        $option['label'] = $label;
        $option['value'] = $value;
        $this->options[] = $option;

        return $this;
    }

    public function __call($method, $arguments)
    {
        if(count($this->options)) {
            $this->options[count($this->options)-1]['method'][][$method] = $arguments;
        }

        return $this;
    }
}
