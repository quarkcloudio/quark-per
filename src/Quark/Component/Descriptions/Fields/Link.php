<?php

namespace QuarkCloudIO\Quark\Component\Descriptions\Fields;

use QuarkCloudIO\Quark\Component\Descriptions\Fields\Item;
use Exception;

class Link extends Item
{
    /**
     * 跳转方式
     *
     * @var string
     */
    public $target = '_blank';

    /**
     * 跳转链接
     *
     * @var string
     */
    public $href = null;

    /**
     * 初始化Input组件
     *
     * @param  string  $dataIndex
     * @param  string  $label
     * @return void
     */ 
    public function __construct($dataIndex, $label = '')
    {
        $this->label = $label ?? $dataIndex;
        $this->component = 'link';
        $this->dataIndex = $dataIndex;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge([
            'component' => $this->component,
            'target' => $this->target,
            'href' => $this->href
        ], parent::jsonSerialize());
    }
}
