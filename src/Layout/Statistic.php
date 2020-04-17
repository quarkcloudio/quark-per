<?php

namespace QuarkCMS\QuarkAdmin\Layout;
use Illuminate\Support\Arr;

class Statistic
{
    public $component = null;

    function __construct($title,$callback = null) {
        $this->component['name'] = 'statistic';
        $this->component['title'] = $title;
        $callback($this);

        return $this;
    }

    public function value($value = '')
    {
        $this->component['value'] = $value;

        return $this;
    }

    public function precision($precision = 0)
    {
        $this->component['precision'] = $precision;

        return $this;
    }

    public function valueStyle($valueStyle)
    {
        $this->component['valueStyle'] = $valueStyle;

        return $this;
    }

    public function prefix($prefix)
    {
        $this->component['prefix'] = $prefix;

        return $this;
    }
}
