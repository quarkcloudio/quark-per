<?php

namespace QuarkCMS\QuarkAdmin\Components\Form\Fields;

use QuarkCMS\QuarkAdmin\Components\Form\Item;

class Hidden extends Item
{
    /**
     * 初始化隐藏域组件
     *
     * @param  string  $name
     * @return void
     */ 
    function __construct($name)
    {
        $this->component = 'hidden';
        $this->name = $name;
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
            'value' => $this->value,
            'defaultValue' => $this->defaultValue
        ], parent::jsonSerialize());
    }
}
