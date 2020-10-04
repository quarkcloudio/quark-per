<?php

namespace QuarkCMS\QuarkAdmin\Components\Form\Fields;

use QuarkCMS\QuarkAdmin\Components\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class TextArea extends Item
{
    public  $autosize,
            $rows;

    function __construct($name,$label = '') {
        $this->component = 'textArea';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $this->placeholder = '请输入'.$this->label;
    }

    /**
     * placeholder
     *
     * @param  string $placeholder
     * @return object
     */
    public function placeholder($placeholder = '')
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    public function autosize($autosize)
    {
        $this->autosize = $autosize;
        return $this;
    }

    public function rows($rows)
    {
        $this->rows = $rows;
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
}
