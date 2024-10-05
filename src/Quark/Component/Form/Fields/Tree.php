<?php

namespace QuarkCloudIO\Quark\Component\Form\Fields;

use QuarkCloudIO\Quark\Component\Form\Fields\Item;

class Tree extends Item
{
    /**
     * 组件类型
     *
     * @var string
     */
    public $component = 'treeField';

    /**
     * 配置树形组件数据
     *
     * @var array
     */
    public  $treeData;
    
    /**
     * 设置树形组件数据
     *
     * @param  array $treeData
     * @return $this
     */
    public function data($treeData)
    {
        $this->treeData = $treeData;
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
            'treeData' => $this->treeData
        ], parent::jsonSerialize());
    }
}
