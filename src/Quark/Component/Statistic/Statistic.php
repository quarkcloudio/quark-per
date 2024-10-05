<?php

namespace QuarkCloudIO\Quark\Component\Statistic;

use QuarkCloudIO\Quark\Component\Element;

class Statistic extends Element
{
    /**
     * 设置小数点
     *
     * @var string
     */
    public $decimalSeparator = '.';

    /**
     * 设置千分位标识符
     *
     * @var string
     */
    public $groupSeparator = ',';

    /**
     * 数值精度
     *
     * @var number
     */
    public $precision = null;

    /**
     * 设置数值的前缀
     *
     * @var array|string
     */
    public $prefix = null;

    /**
     * 设置数值的后缀
     *
     * @var array|string
     */
    public $suffix = null;

    /**
     * 数值的标题
     *
     * @var string
     */
    public $title = null;

    /**
     * 数值内容
     *
     * @var string | number
     */
    public $value = null;

    /**
     * 设置数值的样式
     *
     * @var array
     */
    public $valueStyle = [];

    /**
     * 初始化组件
     *
     * @param  string  $title
     * @param  string  $value
     * @return void
     */
    public function __construct($title = null,$value = null) {
        $this->component = 'statistic';
        $this->title = $title;
        $this->value = $value;

        return $this;
    }

    /**
     * 设置小数点
     *
     * @param  string  $decimalSeparator
     * @return $this
     */
    public function decimalSeparator($decimalSeparator)
    {
        $this->decimalSeparator = $decimalSeparator;
        return $this;
    }

    /**
     * 设置千分位标识符
     *
     * @param  string  $groupSeparator
     * @return $this
     */
    public function groupSeparator($groupSeparator)
    {
        $this->groupSeparator = $groupSeparator;
        return $this;
    }

    /**
     * 数值精度
     *
     * @param  string  $precision
     * @return $this
     */
    public function precision($precision)
    {
        $this->precision = $precision;
        return $this;
    }

    /**
     * 设置数值的前缀
     *
     * @param  string|array  $prefix
     * @return $this
     */
    public function prefix($prefix)
    {
        $this->prefix = $prefix;
        return $this;
    }

    /**
     * 设置数值的后缀
     *
     * @param  string|array  $suffix
     * @return $this
     */
    public function suffix($suffix)
    {
        $this->suffix = $suffix;
        return $this;
    }

    /**
     * 设置标题
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
     * 数值内容
     *
     * @param  string|number  $value
     * @return $this
     */
    public function value($value)
    {
        $this->value = $value;
        return $this;
    }

    /**
     * 设置数值的样式
     *
     * @param  array  $valueStyle
     * @return $this
     */
    public function valueStyle($valueStyle)
    {
        $this->valueStyle = $valueStyle;
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
            $this->key(json_encode($this->title), true);
        }

        return array_merge([
            'decimalSeparator' => $this->decimalSeparator,
            'groupSeparator' => $this->groupSeparator,
            'precision' => $this->precision,
            'prefix' => $this->prefix,
            'suffix' => $this->suffix,
            'title' => $this->title,
            'value' => $this->value,
            'valueStyle' => $this->valueStyle,
        ], parent::jsonSerialize());
    }
}