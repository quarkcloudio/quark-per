<?php

namespace QuarkCloudIO\Quark\Component\Form\Fields;

use QuarkCloudIO\Quark\Component\Form\Fields\Item;

class Editor extends Item
{
    /**
     * 组件类型
     *
     * @var string
     */
    public $component = 'editorField';

    /**
     * 组件样式
     *
     * @var array
     */
    public $style = ['height' => 500,'width' => 800];

    /**
     * 宽度
     * 
     * @param  number|string $value
     * @return $this
     */
    public function width($value = '100%')
    {
        $this->style['width'] = $value;
        return $this;
    }

    /**
     * 高度
     * 
     * @param  number|string $value
     * @return $this
     */
    public function height($value = 500)
    {
        $this->style['height'] = $value;
        return $this;
    }
}
