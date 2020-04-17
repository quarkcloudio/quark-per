<?php

namespace QuarkCMS\QuarkAdmin\Layout;
use Illuminate\Support\Arr;

class Text
{
    public $component = [];

    function __construct($text) {
        
        $this->component['name'] = 'text';
        $this->component['text'] = $text;

        return $this;
    }

}
