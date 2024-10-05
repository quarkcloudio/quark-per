<?php

namespace QuarkCloudIO\Quark\Component\Tabs;

use QuarkCloudIO\Quark\Component\Element;

class Tabs extends Element
{
    /**
     * 标签居中展示
     *
     * @var bool
     */
    public $centered = false;

    /**
     * 初始化选中面板的 key，如果没有设置 activeKey
     *
     * @var string
     */
    public $defaultActiveKey = null;

    /**
     * 大小，提供 large default 和 small 三种大小
     *
     * @var string
     */
    public $size = 'default';

    /**
     * tab bar 上额外的元素
     *
     * @var array|string
     */
    public $tabBarExtraContent = null;

    /**
     * tabs 之间的间隙
     *
     * @var number
     */
    public $tabBarGutter = null;

    /**
     * tab bar 的样式对象
     *
     * @var array
     */
    public $tabBarStyle = [];

    /**
     * 页签位置，可选值有 top right bottom left
     *
     * @var string
     */
    public $tabPosition = 'top';

    /**
     * 页签的基本样式，可选 line、card editable-card 类型
     *
     * @var string
     */
    public $type = 'line';

    /**
     * tab 的内容
     *
     * @var array
     */
    public $tabPanes = [];

    /**
     * 初始化组件
     *
     * @return void
     */
    public function __construct() {
        $this->component = 'tabs';

        return $this;
    }

    /**
     * Pane控件
     *
     * @var array
     */
    public static $classFields = [
        'pane' => TabPane::class
    ];

    /**
     * 标签居中展示
     *
     * @param  bool  $centered
     * @return $this
     */
    public function centered($centered = false)
    {
        $this->centered = $centered;

        return $this;
    }

    /**
     * 初始化选中面板的 key，如果没有设置 activeKey
     *
     * @param  string  $defaultActiveKey
     * @return $this
     */
    public function defaultActiveKey($defaultActiveKey)
    {
        $this->defaultActiveKey = $defaultActiveKey;

        return $this;
    }

    /**
     * 大小，提供 large default 和 small 三种大小
     *
     * @param  string  $size
     * @return $this
     */
    public function size($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * tab bar 上额外的元素
     *
     * @param  string|array  $prefix
     * @return $this
     */
    public function tabBarExtraContent($tabBarExtraContent)
    {
        $this->tabBarExtraContent = $tabBarExtraContent;

        return $this;
    }

    /**
     * tabs 之间的间隙
     *
     * @param  number  $tabBarGutter
     * @return $this
     */
    public function tabBarGutter($tabBarGutter)
    {
        $this->tabBarGutter = $tabBarGutter;

        return $this;
    }

    /**
     * tab bar 的样式对象
     *
     * @param  array  $style
     * @return $this
     */
    public function tabBarStyle($style)
    {
        $this->tabBarStyle = $style;

        return $this;
    }

    /**
     * 页签位置，可选值有 top right bottom left
     *
     * @param  string  $tabPosition
     * @return $this
     */
    public function tabPosition($tabPosition)
    {
        $this->tabPosition = $tabPosition;

        return $this;
    }

    /**
     * 页签的基本样式，可选 line、card editable-card 类型
     *
     * @param  string  $type
     * @return $this
     */
    public function type($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * tab 的内容
     *
     * @param  array  $tabPanes
     * @return $this
     */
    public function tabPanes($tabPanes)
    {
        $this->tabPanes = $tabPanes;

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
        $class = static::$classFields[$method];

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

            $column = $parameters[0]; // 列字段
            $label = $parameters[1] ?? null; // 标题
            $callback = $parameters[2] ?? null; // 回调函数

            $element = new $className($column, $label, $callback);

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
            $this->key(json_encode($this), true);
        }

        return array_merge([
            'centered' => $this->centered,
            'defaultActiveKey' => $this->defaultActiveKey,
            'size' => $this->size,
            'tabBarExtraContent' => $this->tabBarExtraContent,
            'tabBarGutter' => $this->tabBarGutter,
            'tabBarStyle' => $this->tabBarStyle,
            'tabPosition' => $this->tabPosition,
            'type' => $this->type,
            'tabPanes' => $this->tabPanes
        ], parent::jsonSerialize());
    }
}