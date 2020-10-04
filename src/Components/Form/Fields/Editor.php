<?php

namespace QuarkCMS\QuarkAdmin\Components\Form\Fields;

use QuarkCMS\QuarkAdmin\Components\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class Editor extends Item
{
    function __construct($name,$label = '') {
        $this->component = 'editor';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $this->placeholder = '请输入'.$this->label;

        $style = ['height' => 400, 'boxShadow' => 'inset 0 1px 3px rgba(0,0,0,.1)'];

        $this->style = $style;
    }

    /**
     * 宽度
     * 
     * @param  number|string $value
     * @return object
     */
    public function width($value = '100%')
    {
        $this->style['width'] = $value;
        return $this;
    }

    /**
     * 高度
     * 
     * @param  number|string $value
     * @return object
     */
    public function height($value = 500)
    {
        $this->style['height'] = $value;
        return $this;
    }
}
