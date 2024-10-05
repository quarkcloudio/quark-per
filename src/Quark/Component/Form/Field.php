<?php

namespace QuarkCloudIO\Quark\Component\Form;

class Field
{
    /**
     * 表单字段控件
     *
     * @var array
     */
    public static $formFields = [
        'hidden' => Fields\Hidden::class,
        'display' => Fields\Display::class,
        'text' => Fields\Text::class,
        'password' => Fields\Password::class,
        'textarea' => Fields\TextArea::class,
        'textArea' => Fields\TextArea::class,
        'number' => Fields\Number::class,
        'radio' => Fields\Radio::class,
        'image' => Fields\Image::class,
        'file' => Fields\File::class,
        'tree' => Fields\Tree::class,
        'select' => Fields\Select::class,
        'checkbox' => Fields\Checkbox::class,
        'icon' => Fields\Icon::class,
        'switch' => Fields\SwitchField::class,
        'icon' => Fields\Icon::class,
        'date' => Fields\Date::class,
        'week' => Fields\Week::class,
        'month' => Fields\Month::class,
        'quarter' => Fields\Quarter::class,
        'year' => Fields\Year::class,
        'dateRange' => Fields\DateRange::class,
        'datetime' => Fields\Datetime::class,
        'datetimeRange' => Fields\DatetimeRange::class,
        'time' => Fields\Time::class,
        'timeRange' => Fields\TimeRange::class,
        'editor' => Fields\Editor::class,
        'map' => Fields\Map::class,
        'cascader' => Fields\Cascader::class,
        'search' => Fields\Search::class,
        'list' => Fields\ListField::class,
        'group' => Fields\Group::class,
        'selects' => Fields\Selects::class,
    ];

    /**
     * 初始化表单组件
     *
     * @param  string  $name
     * @return void
     */
    public function __construct()
    {
        $this->component = 'field';

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
        $class = static::$formFields[$method];

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
