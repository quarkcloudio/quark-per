<?php

namespace QuarkCMS\QuarkAdmin\Components\Form\Fields;

use QuarkCMS\QuarkAdmin\Components\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class TimeRange extends Item
{
    public  $format,
            $showTime;

    function __construct($name,$label = '') {
        $this->component = 'timeRange';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $this->format = 'HH:mm';
        $this->value = [null,null];
    }

    public function format($format)
    {
        $this->format = $format;
        return $this;
    }
}
