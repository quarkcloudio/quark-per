<?php

namespace QuarkCloudIO\Quark\Component\Menu;

use QuarkCloudIO\Quark\Component\Element;

class ItemGroup extends Element
{
    /**
     * 分组标题
     *
     * @var string
     */
    public $title = null;

    /**
     * 菜单项
     *
     * @var array
     */
    public $items = [];

    /**
     * 初始化容器
     *
     * @param  string  $title
     * @return $this
     */
    public function __construct($title = '', $items)
    {
        $this->component = 'menuItemGroup';
        $this->title = $title;
        $this->items = $items;

        return $this;
    }

    /**
     * 子菜单项值
     *
     * @param  string  $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;
        
        return $this;
    }

    /**
     * 菜单项
     *
     * @param  array  $items
     * @return $this
     */
    public function items($items)
    {
        $this->items = $items;
        
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
            $this->key($this->title.json_encode($this->items), true);
        }

        return array_merge([
            'title' => $this->title,
            'items' => $this->items
        ], parent::jsonSerialize());
    }
}