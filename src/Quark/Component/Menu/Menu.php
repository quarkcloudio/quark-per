<?php

namespace QuarkCloudIO\Quark\Component\Menu;

use QuarkCloudIO\Quark\Component\Element;

class Menu extends Element
{
    /**
     * 初始展开的 SubMenu 菜单项 key 数组
     *
     * @var array
     */
    public $defaultOpenKeys = [];

    /**
     * 初始选中的菜单项 key 数组
     *
     * @var array
     */
    public $defaultSelectedKeys = [];

    /**
     * inline 时菜单是否收起状态
     *
     * @var bool
     */
    public $inlineCollapsed = false;

    /**
     * inline 模式的菜单缩进宽度
     *
     * @var number
     */
    public $inlineIndent = 24;

    /**
     * 菜单类型，现在支持垂直、水平、和内嵌模式三种,vertical | horizontal | inline
     *
     * @var string
     */
    public $mode = 'vertical';

    /**
     * 是否允许多选
     *
     * @var bool
     */
    public $multiple = false;

    /**
     * 是否允许选中
     *
     * @var bool
     */
    public $selectable = true;

    /**
     * 用户鼠标离开子菜单后关闭延时，单位：秒
     *
     * @var number
     */
    public $subMenuCloseDelay = 0.1;

    /**
     * 用户鼠标进入子菜单后开启延时，单位：秒
     *
     * @var number
     */
    public $subMenuOpenDelay = 0;

    /**
     * 主题颜色,light | dark
     *
     * @var string
     */
    public $theme = 'light';

    /**
     * SubMenu 展开/关闭的触发行为,hover | click
     *
     * @var string
     */
    public $triggerSubMenuAction = 'hover';

    /**
     * 菜单项
     *
     * @var array
     */
    public $items = [];

    /**
     * 字段控件
     *
     * @var array
     */
    public static $fields = [
        'item' => Item::class,
        'subMenu' => SubMenu::class,
        'itemGroup' => ItemGroup::class,
        'divider' => Divider::class
    ];

    /**
     * 初始化容器
     *
     * @param  string  $label
     * @param  array  $items
     * @return $this
     */
    public function __construct($items = [])
    {
        $this->component = 'menu';
        $this->items = $items;

        return $this;
    }

    /**
     * 初始展开的 SubMenu 菜单项 key 数组
     *
     * @param  array  $defaultOpenKeys
     * @return $this
     */
    public function defaultOpenKeys($defaultOpenKeys)
    {
        $this->defaultOpenKeys = $defaultOpenKeys;
        
        return $this;
    }

    /**
     * 初始选中的菜单项 key 数组
     *
     * @param  array  $defaultSelectedKeys
     * @return $this
     */
    public function defaultSelectedKeys($defaultSelectedKeys)
    {
        $this->defaultSelectedKeys = $defaultSelectedKeys;

        return $this;
    }

    /**
     * inline 时菜单是否收起状态
     *
     * @param  bool  $inlineCollapsed
     * @return $this
     */
    public function inlineCollapsed($inlineCollapsed)
    {
        $this->inlineCollapsed = $inlineCollapsed;

        return $this;
    }

    /**
     * inline 模式的菜单缩进宽度
     *
     * @param  number  $inlineIndent
     * @return $this
     */
    public function inlineIndent($inlineIndent)
    {
        $this->inlineIndent = $inlineIndent;

        return $this;
    }

    /**
     * 菜单类型，现在支持垂直、水平、和内嵌模式三种,vertical | horizontal | inline
     *
     * @param  string  $mode
     * @return $this
     */
    public function mode($mode)
    {
        $this->mode = $mode;

        return $this;
    }

    /**
     * 是否允许多选
     *
     * @param  bool  $multiple
     * @return $this
     */
    public function multiple($multiple)
    {
        $this->multiple = $multiple;

        return $this;
    }

    /**
     * 是否允许选中
     *
     * @param  bool  $selectable
     * @return $this
     */
    public function selectable($selectable)
    {
        $this->selectable = $selectable;

        return $this;
    }

    /**
     * 用户鼠标离开子菜单后关闭延时，单位：秒
     *
     * @param  number  $subMenuCloseDelay
     * @return $this
     */
    public function subMenuCloseDelay($subMenuCloseDelay)
    {
        $this->subMenuCloseDelay = $subMenuCloseDelay;

        return $this;
    }

    /**
     * 主题颜色,light | dark
     *
     * @param  string  $theme
     * @return $this
     */
    public function theme($theme)
    {
        $this->theme = $theme;

        return $this;
    }

    /**
     * SubMenu 展开/关闭的触发行为,hover | click
     *
     * @param  string  $triggerSubMenuAction
     * @return $this
     */
    public function triggerSubMenuAction($triggerSubMenuAction)
    {
        $this->triggerSubMenuAction = $triggerSubMenuAction;

        return $this;
    }

    /**
     * 菜单项
     *
     * @param array $items
     * @return $this
     */
    public function items($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * 获取行为类
     *
     * @param string $method
     * @return bool|mixed
     */
    public static function getCalledClass($method)
    {
        $class = static::$fields[$method];

        if (class_exists($class)) {
            return $class;
        }

        return false;
    }

    /**
     * 动态调用行为类
     *
     * @param string $method
     * @return bool|mixed
     */
    public function __call($method, $parameters)
    {
        if ($className = static::getCalledClass($method)) {
            if ($method === 'divider') {
                $parameter = $parameters[0];
                $element = new $className($parameter);
            } else {
                $parameter1 = $parameters[0];
                $parameter2 = $parameters[1] ?? null;
                $element = new $className($parameter1, $parameter2);
            }

            return $element;
        }
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        if(empty($this->key)) {
            $this->key(__CLASS__.$this->theme.json_encode($this->items), true);
        }

        return array_merge([
            'defaultOpenKeys' => $this->defaultOpenKeys,
            'defaultSelectedKeys' => $this->defaultSelectedKeys,
            'inlineCollapsed' => $this->inlineCollapsed,
            'inlineIndent' => $this->inlineIndent,
            'mode' => $this->mode,
            'multiple' => $this->multiple,
            'selectable' => $this->selectable,
            'subMenuCloseDelay' => $this->subMenuCloseDelay,
            'subMenuOpenDelay' => $this->subMenuOpenDelay,
            'theme' => $this->theme,
            'triggerSubMenuAction' => $this->triggerSubMenuAction,
            'items' => $this->items
        ], parent::jsonSerialize());
    }
}