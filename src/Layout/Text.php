<?php

namespace QuarkCMS\QuarkAdmin\Layout;
use Illuminate\Support\Arr;

class Text
{
    public $component = [];

    function __construct($text) {
        
        $this->component['name'] = 'text';
        $this->component['items'] = $text;

        return $this;
    }

}
