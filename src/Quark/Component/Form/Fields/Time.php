<?php

namespace QuarkCloudIO\Quark\Component\Form\Fields;

use QuarkCloudIO\Quark\Component\Form\Fields\Item;

class Time extends Item
{
    /**
     * 组件类型
     *
     * @var string
     */
    public $component = 'timeField';

    /**
     * 时间显示格式
     *
     * @var string
     */
    public $format = 'HH:mm';

    /**
     * 默认值
     *
     * @var string
     */
    public $value = null;

    /**
     * 设置时间显示格式
     *
     * @param  string $format
     * @return $this
     */
    public function format($format)
    {
        $this->format = $format;
        
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
            'format' => $this->format
        ], parent::jsonSerialize());
    }
}
