<?php

namespace QuarkCloudIO\Quark\Component\Form\Fields;

use QuarkCloudIO\Quark\Component\Form\Fields\Item;
use Exception;

class Checkbox extends Item
{
    /**
     * 组件类型
     *
     * @var string
     */
    public $component = 'checkboxField';

    /**
     * 与 select 相同，根据 options 生成子节点，推荐使用。
     *
     * @var array
     */
    public $options;

    /**
     * 配置 checkbox 的样子，支持垂直vertical 和 horizontal
     *
     * @var string
     */
    public $layout = 'vertical';

    /**
     * 设置多选框属性
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
     * 配置 checkbox 的样子，支持垂直vertical 和 horizontal
     *
     * @param  string $layout
     * @return $this
     */
    public function layout($layout)
    {
        if(!in_array($layout,['vertical', 'horizontal'])) {
            throw new Exception("argument must be in 'vertical', 'horizontal'!");
        }

        $this->layout = $layout;
        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge([
            'layout' => $this->layout,
            'options' => $this->options,
        ], parent::jsonSerialize());
    }
}
