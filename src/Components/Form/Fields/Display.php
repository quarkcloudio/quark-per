<?php

namespace QuarkCMS\QuarkAdmin\Components\Form\Fields;

use QuarkCMS\QuarkAdmin\Components\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class Display extends Item
{
    function __construct($label = '') {
        $this->component = 'display';
        $this->label = $label;

        $style['width'] = 200;
        $this->style = $style;
    }
}
