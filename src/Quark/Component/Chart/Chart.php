<?php

namespace QuarkCloudIO\Quark\Component\Chart;

use QuarkCloudIO\Quark\Component\Element;

class Chart extends Element
{
    /**
     * 初始化组件
     *
     * @return void
     */
    public function __construct() {
        $this->component = 'chart';
        return $this;
    }

    /**
     * 图表控件
     *
     * @var array
     */
    public static $classCharts = [
        'line' => Line::class
    ];

    /**
     * 获取行为类
     *
     * @param string $method
     * @return bool|mixed
     */
    public static function getCalledClass($method)
    {
        $class = static::$classCharts[$method];

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

            $data = $parameters[0] ?? null; // 数据

            $element = new $className($data);

            return $element;
        }
    }
}