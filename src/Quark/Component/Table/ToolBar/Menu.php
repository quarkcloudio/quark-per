<?php

namespace QuarkCloudIO\Quark\Component\Table\ToolBar;

use Exception;
use QuarkCloudIO\Quark\Component\Element;

class Menu extends Element
{
    /**
     * 类型, inline | dropdown | tab
     *
     * @var string
     */
    public $type = 'tab';

    /**
     * 当前值
     *
     * @var string|number
     */
    public $activeKey = null;

    /**
     * 菜单项
     *
     * @var array
     */
    public $items = [];

    /**
     * 初始化
     *
     * @param  string  $type
     * @param  string  $activeKey
     * @return void
     */
    public function __construct($type = 'tab', $activeKey = '')
    {
        $this->component = 'toolBarMenu';
        $this->type = $type;
        $this->activeKey = $activeKey;
    }

    /**
     * 字段控件
     *
     * @var array
     */
    public static $fields = [
        'item' => Item::class,
    ];

    /**
     * 类型, inline | dropdown | tab
     *
     * @param string $type
     * @return $this
     */
    public function type($type)
    {
        $this->type = $type;

        return $this;
    }

    /**
     * 当前值
     *
     * @param string|number $activeKey
     * @return $this
     */
    public function activeKey($activeKey)
    {
        $this->activeKey = $activeKey;

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

            $key = $parameters[0]; // 列字段
            $label = $parameters[1] ?? null; // 标题

            $element = new $className($key, $label);

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
        // 设置组件唯一标识
        if(empty($this->key)) {
            $this->key(__CLASS__.$this->type.$this->activeKey.json_encode($this->items), true);
        }

        return array_merge([
            'type' => $this->type,
            'activeKey' => $this->activeKey,
            'items' => $this->items
        ], parent::jsonSerialize());
    }
}
