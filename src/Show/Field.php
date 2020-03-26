<?php

namespace QuarkCMS\QuarkAdmin\Show;

use Illuminate\Support\Arr;
use Exception;

class Field
{
    public  $name,
            $label,
            $image = false,
            $component;

    function __construct($name,$label = '') {
        $this->component = 'text';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }
    }

    public function image($width = 100,$height = 100)
    {
        $this->component = 'image';

        $image['width'] = $width;
        $image['height'] = $height;
        $this->image = $image;
        return $this;
    }
}
