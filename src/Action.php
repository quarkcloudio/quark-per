<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Support\Arr;

class Action
{
    /**
     * 行为列表
     *
     * @var array
     */
    protected $actions = [];

    /**
     * 获取行为列表
     *
     * @return array
     */
    public function actions()
    {
        return $this->actions;
    }

    /**
     * 注册行为类
     *
     * @var array
     */
    protected static $registerClasses = [
        'a' => Actions\AStyle::class,
        'button' => Actions\ButtonStyle::class,
        'dropdown' => Actions\DropdownStyle::class,
        'item' => Actions\DropdownStyle\Item::class
    ];

    /**
     * 获取行为类
     *
     * @param string $method
     *
     * @return bool|mixed
     */
    protected static function getCalledClass($method)
    {
        $class = Arr::get(static::$registerClasses, $method);

        if (class_exists($class)) {
            return $class;
        }

        return false;
    }

    /**
     * 动态调用行为类
     *
     * @param string $method
     *
     * @return bool|mixed
     */
    public function __call($method, $parameters)
    {
        if ($className = static::getCalledClass($method)) {
            $column = Arr::get($parameters, 0, '');
            $element = new $className($column, array_slice($parameters, 1));
            $this->actions[] = $element;
            return $element;
        }
    }
}
