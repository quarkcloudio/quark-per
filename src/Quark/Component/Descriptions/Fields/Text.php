<?php

namespace QuarkCloudIO\Quark\Component\Descriptions\Fields;

use QuarkCloudIO\Quark\Component\Descriptions\Fields\Item;
use Exception;

class Text extends Item
{
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
        $this->component = 'text';
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
            'component' => $this->component
        ], parent::jsonSerialize());
    }
}
