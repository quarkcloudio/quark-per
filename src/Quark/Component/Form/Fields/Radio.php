<?php

namespace QuarkCloudIO\Quark\Component\Form\Fields;

use QuarkCloudIO\Quark\Component\Form\Fields\Item;

class Radio extends Item
{
    /**
     * 组件类型
     *
     * @var string
     */
    public $component = 'radioField';

    /**
     * 与 select 相同，根据 options 生成子节点，推荐使用。
     *
     * @var array
     */
    public  $options;

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
     * 当前列值的枚举 valueEnum
     *
     * @return array
     */
    public function getValueEnum()
    {
        foreach ($this->options as $option) {
            $options[$option['value']] = $option['label'];
        }

        return $options;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge([
            'options' => $this->options
        ], parent::jsonSerialize());
    }
}
