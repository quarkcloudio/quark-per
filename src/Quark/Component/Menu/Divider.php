<?php

namespace QuarkCloudIO\Quark\Component\Menu;

use QuarkCloudIO\Quark\Component\Element;

class Divider extends Element
{
    /**
     * 是否虚线
     *
     * @var bool
     */
    public $dashed = false;

    /**
     * 初始化容器
     *
     * @return $this
     */
    public function __construct()
    {
        $this->component = 'menuDivider';

        return $this;
    }

    /**
     * 子菜单项值
     *
     * @param  bool  $dashed
     * @return $this
     */
    public function dashed($dashed = true)
    {
        $this->dashed = $dashed;
        
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
            $this->key(__CLASS__.$this->dashed, true);
        }

        return array_merge([
            'dashed' => $this->dashed
        ], parent::jsonSerialize());
    }
}