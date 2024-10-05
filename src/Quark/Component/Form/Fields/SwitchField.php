<?php

namespace QuarkCloudIO\Quark\Component\Form\Fields;

use QuarkCloudIO\Quark\Component\Form\Fields\Item;

class SwitchField extends Item
{
    /**
     * 组件类型
     *
     * @var string
     */
    public $component = 'switchField';

    /**
     * 设置开关属性
     *
     * @param  array $options
     * @return $this
     */
    public function options($options)
    {
        $this->options = $options;

        return $this;
    }

    /**
     * 设置开关属性
     *
     * @param  string $value
     * @return $this
     */
    public function trueValue($value)
    {
        $this->options['on'] = $value;

        return $this;
    }

    /**
     * 设置开关属性
     *
     * @param  string $value
     * @return $this
     */
    public function falseValue($value)
    {
        $this->options['off'] = $value;

        return $this;
    }

    /**
     * 当前列值的枚举 valueEnum
     *
     * @return array
     */
    public function getValueEnum()
    {
        foreach ($this->options as $key => $value) {
            $valueKey = ($key === 'on') ? 1 : 0;
            $options[$valueKey] = $value;
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
            'options' => $this->options,
        ], parent::jsonSerialize());
    }
}
