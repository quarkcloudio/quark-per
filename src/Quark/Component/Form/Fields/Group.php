<?php

namespace QuarkCloudIO\Quark\Component\Form\Fields;

use QuarkCloudIO\Quark\Component\Form\Fields\Item;

class Group extends Item
{
    /**
     * 组件类型
     *
     * @var string
     */
    public $component = 'groupField';

    /**
     * 组件内容
     *
     * @var array
     */
    public $body = [];

    /**
     * 子元素个数
     *
     * @var number
     */
    public $size = 32;

    /**
     * 初始化组件
     *
     * @param  string  $label
     * @param  array  $items
     * 
     * @return void
     */
    public function __construct($label, $items = null) {

        $this->body = $items;

        if (is_string($label) || is_numeric($label)) {
            $this->label = $label;
        } else {
            if (empty($items)) {
                $this->body = $label;
            }
        }

        $this->onlyOnForms();
    }

    /**
     * 子元素个数
     *
     * @param  $size
     * @return $this
     */
    public function size($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        if(empty($this->key)) {
            $this->key(__CLASS__.$this->label, true);
        }

        return array_merge([
            'label' => $this->label,
            'size' => $this->size,
            'body' => $this->body
        ], parent::jsonSerialize());
    }
}
