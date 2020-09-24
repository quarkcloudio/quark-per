<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Support\Arr;

class Action
{
    /**
     * 行为树列表
     *
     * @var array
     */
    protected $actions = [];

    /**
     * 获取所有操作
     *
     * @return array
     */
    public function actions()
    {
        return $this->actions;
    }

    /**
     * 注册类到数组
     *
     * @var array
     */
    protected static $registerClasses = [
        'button' => Actions\ButtonStyleAction::class,
        'dropdown' => Actions\DropdownStyleAction::class,
        'select' => Actions\SelectStyleAction::class,
    ];

    /**
     * 获取数组里面的类
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
     * 动态调用类
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
