<?php

namespace QuarkCloudIO\Quark\Component\Menu;

use QuarkCloudIO\Quark\Component\Element;

class SubMenu extends Element
{
    /**
     * 是否禁用
     *
     * @var bool
     */
    public $disabled = false;

    /**
     * 菜单图标
     *
     * @var string
     */
    public $icon = null;

    /**
     * 子菜单样式，mode="inline" 时无效
     *
     * @var string
     */
    public $popupClassName = null;

    /**
     * 子菜单偏移量，mode="inline" 时无效
     *
     * @var [number, number]
     */
    public $popupOffset = [];

    /**
     * 子菜单项值
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
        $this->component = 'menuSubMenu';
        $this->title = $title;
        $this->items = $items;

        return $this;
    }

    /**
     * 是否禁用
     *
     * @param  bool  $disabled
     * @return $this
     */
    public function disabled($disabled)
    {
        $this->disabled = $disabled;
        
        return $this;
    }

    /**
     * 菜单图标
     *
     * @param  string  $icon
     * @return $this
     */
    public function icon($icon)
    {
        $this->icon = $icon;
        
        return $this;
    }

    /**
     * 子菜单样式，mode="inline" 时无效
     *
     * @param  string  $popupClassName
     * @return $this
     */
    public function popupClassName($popupClassName)
    {
        $this->popupClassName = $popupClassName;
        
        return $this;
    }

    /**
     * 子菜单偏移量，mode="inline" 时无效
     *
     * @param  array  $popupOffset
     * @return $this
     */
    public function popupOffset($popupOffset)
    {
        $this->popupOffset = $popupOffset;
        
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
            'disabled' => $this->disabled,
            'icon' => $this->icon,
            'popupClassName' => $this->popupClassName,
            'popupOffset' => $this->popupOffset,
            'title' => $this->title,
            'items' => $this->items
        ], parent::jsonSerialize());
    }
}