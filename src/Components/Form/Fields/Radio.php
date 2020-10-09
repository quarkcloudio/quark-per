<?php

namespace QuarkCMS\QuarkAdmin\Components\Form\Fields;

use QuarkCMS\QuarkAdmin\Components\Form\Item;
use Illuminate\Support\Arr;

class Radio extends Item
{
    /**
     * 与 select 相同，根据 options 生成子节点，推荐使用。
     *
     * @var array
     */
    public  $options;

    /**
     * 初始化单选组件
     *
     * @param  string  $name
     * @param  string  $label
     * @return void
     */ 
    public function __construct($name,$label = '')
    {
        $this->component = 'radio';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }
    }

    /**
     * 设置单选属性
     *
     * @param  array $options
     * @return $this
     */
    public function options($options)
    {
        $data = [];
        foreach ($options as $key => $value) {
            $option['label'] = $value;
            $option['value'] = $key;
            $data[] = $option;
        }
        $this->options = $data;

        return $this;
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
            'label' => $this->label,
            'name' => $this->name,
            'disabled' => $this->disabled,
            'options' => $this->options,
            'value' => $this->value,
            'defaultValue' => $this->defaultValue
        ], parent::jsonSerialize());
    }
}
