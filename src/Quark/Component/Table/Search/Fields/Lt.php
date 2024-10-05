<?php

namespace QuarkCloudIO\Quark\Component\Table\Search\Fields;

use QuarkCloudIO\Quark\Component\Table\Search\Item;

class Lt extends Item
{
    /**
     * 初始化
     *
     * @param  string  $name
     * @param  string  $label
     * @return void
     */
    public function __construct($name,$label = '') {
        $this->component = 'text';
        $this->name = $name;
        $this->operator = 'lt';

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $this->label = $label[0];
        }

        $this->placeholder = '请输入'.$this->label;
    }
}
