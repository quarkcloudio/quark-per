<?php

namespace QuarkCloudIO\Quark\Component\Form\Fields;

use QuarkCloudIO\Quark\Component\Form\Fields\Item;
use QuarkCloudIO\Quark\Component\Form\Form;
use Closure;

class ListField extends Item
{
    /**
     * 组件类型
     *
     * @var string
     */
    public $component = 'listField';

    /**
     * 表单项
     *
     * @var array
     */
    public $items = null;

    /**
     * 按钮名称
     *
     * @var string
     */
    public $buttonText = '添加一行数据';

    /**
     * 按钮位置,top | bottom
     *
     * @var string
     */
    public $buttonPosition = 'top';

    /**
     * Item 中总是展示 label
     *
     * @var bool
     */
    public $alwaysShowItemLabel = true;

    /**
     * 按钮名称
     *
     * @param  string  $text
     * @param  string  $position
     * 
     * @return $this
     */
    public function button($text, $position = 'top')
    {
        $this->buttonText = $text;
        $this->buttonPosition = $position;

        return $this;
    }

    /**
     * 表单项
     *
     * @param  Closure  $callback
     * @return $this
     */
    public function item(Closure $callback = null)
    {
        $this->items = $callback();

        return $this;
    }

    /**
     * Item 中总是展示 label
     *
     * @param  bool  $alwaysShowItemLabel
     * 
     * @return $this
     */
    public function alwaysShowItemLabel($alwaysShowItemLabel = true)
    {
        $this->alwaysShowItemLabel = $alwaysShowItemLabel;

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
            'buttonText' => $this->buttonText,
            'buttonPosition' => $this->buttonPosition,
            'items' => $this->items,
            'alwaysShowItemLabel' => $this->alwaysShowItemLabel
        ], parent::jsonSerialize());
    }
}
