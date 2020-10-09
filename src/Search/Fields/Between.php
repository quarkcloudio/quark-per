<?php

namespace QuarkCMS\QuarkAdmin\Search\Fields;

use Illuminate\Support\Arr;
use QuarkCMS\QuarkAdmin\Search\Item;

class Between extends Item
{
    /**
     * 初始化between条件
     *
     * @param  string  $name
     * @param  string  $label
     * @return void
     */
    public function __construct($name,$label = '') {
        $this->component = 'input';
        $this->name = $name;
        $this->operator = 'between';

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $placeholder[0] = '开始'.$this->label;
        $placeholder[1] = '结束'.$this->label;
        $this->placeholder = $placeholder;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key(__CLASS__.$this->name.$this->label);

        return array_merge([
            'name' => $this->name,
            'label' => $this->label,
            'value' => $this->value,
            'defaultValue' => $this->defaultValue,
            'rules' => $this->rules,
            'placeholder' => $this->placeholder,
            'options' => $this->options
        ], parent::jsonSerialize());
    }
}
