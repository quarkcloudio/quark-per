<?php

namespace QuarkCMS\QuarkAdmin\Components\Form\Fields;

use QuarkCMS\QuarkAdmin\Components\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class Number extends Item
{
    public  $min = -100000000,
            $max = 100000000,
            $step = 1;

    function __construct($name,$label = '') {
        $this->component = 'inputNumber';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $this->placeholder = '请输入'.$this->label;

        $style['width'] = 200;
        $this->style = $style;
    }

    public function min($min)
    {
        $this->min = $min;
        return $this;
    }

    /**
     * 输入框宽度
     * 
     * @param  number|string $value
     * @return object
     */
    public function width($value = '100%')
    {
        $style['width'] = $value;
        $this->style = $style;
        return $this;
    }

    public function max($max)
    {
        $this->max = $max;
        return $this;
    }

    public function step($step)
    {
        $this->step = $step;
        return $this;
    }
}
