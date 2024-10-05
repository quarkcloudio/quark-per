<?php

namespace QuarkCloudIO\Quark\Component\Descriptions;

class Field
{
    /**
     * 字段控件
     *
     * @var array
     */
    public static $fields = [
        'text' => Fields\Text::class,
        'image' => Fields\Image::class,
        'link' => Fields\Link::class,
    ];

    /**
     * 初始化表单组件
     *
     * @param  string  $name
     * @return void
     */
    public function __construct()
    {
        $this->component = 'descriptionField';

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

            $column = $parameters[0]; // 列字段
            $label = $parameters[1] ?? null; // 标题
            $callback = $parameters[2] ?? null; // 回调函数

            $element = new $className($column, $label, $callback);

            return $element;
        }
    }
}
