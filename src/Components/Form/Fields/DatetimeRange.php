<?php

namespace QuarkCMS\QuarkAdmin\Components\Form\Fields;

use QuarkCMS\QuarkAdmin\Components\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class DatetimeRange extends Item
{
    public  $format,
            $showTime;

    function __construct($name,$label = '') {
        $this->component = 'datetimeRange';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $this->format = 'YYYY-MM-DD HH:mm:ss';
        $showTime['format'] = 'HH:mm:ss';
        $this->showTime = $showTime;
        $this->value = [null,null];
    }

    public function showTime($showTime)
    {
        $this->showTime = $showTime;
        return $this;
    }

    public function format($format)
    {
        $this->format = $format;
        return $this;
    }
}
