<?php

namespace QuarkCMS\QuarkAdmin\Components\Form\Fields;

use QuarkCMS\QuarkAdmin\Components\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class Display extends Item
{
    /**
     * 初始化组件
     *
     * @param  string  $label
     * @return void
     */
    public function __construct($label = '') {
        $this->component = 'display';
        $this->label = $label;
    }
}
