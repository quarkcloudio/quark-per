<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Support\Arr;

class Search extends Element
{
    /**
     * 自动格式数据，例如 moment 的表单,支持 string 和 number 两种模式
     *
     * @var string|number|false
     */
    public $dateFormatter = 'string';

    /**
     * label 标签的文本对齐方式，left | right
     *
     * @var string
     */
    public $labelAlign = 'right';

    /**
     * 设置字段组件的尺寸（仅限 antd 组件）,small | middle | large
     *
     * @var string
     */
    public $size = 'default';

    /**
     * 默认状态下是否折叠超出的表单项
     *
     * @var bool
     */
    public $defaultCollapsed = true;

    /**
     * 隐藏所有表单项的必选标记，默认隐藏
     *
     * @var bool
     */
    public $hideRequiredMark = true;

    /**
     * 自定义折叠状态下默认显示的表单控件数量，没有设置或小于 0，则显示一行控件; 数量大于等于控件数量则隐藏展开按钮
     *
     * @var number
     */
    public $defaultColsNumber = 2;

    /**
     * label 宽度,number | 'auto'
     *
     * @var number|string
     */
    public $labelWidth = 98;

    /**
     * 表单项宽度,number[0 - 24]
     *
     * @var number
     */
    public $span = null;

    /**
     * 每一行是否有分割线
     *
     * @var bool
     */
    public $split = false;

    /**
     * 表单项
     *
     * @var array
     */
    public $items = [];

    /**
     * 初始化
     *
     * @param  void
     * @return $this
     */
    public function __construct()
    {
        $this->component = 'search';

        return $this;
    }

    /**
     * 注册条件类
     *
     * @var array
     */
    public static $registerFields = [
        'equal' => Search\Fields\Equal::class,
        'like' => Search\Fields\Like::class,
        'between' => Search\Fields\Between::class,
        'gt' => Search\Fields\Gt::class,
        'lt' => Search\Fields\Lt::class,
        'in' => Search\Fields\In::class,
        'notIn' => Search\Fields\NotIn::class,
        'scope' => Search\Fields\Scope::class,
        'group' => Search\Fields\Group::class,
        'where' => Search\Fields\Where::class,
    ];

    /**
     * 自动格式数据，例如 moment 的表单,支持 string 和 number 两种模式
     *
     * @param string $dateFormatter
     * @return $this
     */
    public function dateFormatter($dateFormatter)
    {
        $this->dateFormatter = $dateFormatter;

        return $this;
    }

    /**
     * 默认状态下是否折叠超出的表单项
     *
     * @param bool $collapsed
     * @return $this
     */
    public function collapsed($collapsed = true)
    {
        $this->defaultCollapsed = $collapsed;

        return $this;
    }

    /**
     * label 标签的文本对齐方式，left | right
     *
     * @param string $labelAlign
     * @return $this
     */
    public function labelAlign($labelAlign)
    {
        $this->labelAlign = $labelAlign;

        return $this;
    }

    /**
     * 设置字段组件的尺寸（仅限 antd 组件）,small | middle | large
     *
     * @param string $size
     * @return $this
     */
    public function size($size)
    {
        $this->size = $size;

        return $this;
    }

    /**
     * 隐藏所有表单项的必选标记，默认隐藏
     *
     * @param bool $hideRequiredMark
     * @return $this
     */
    public function hideRequiredMark($hideRequiredMark)
    {
        $this->hideRequiredMark = $hideRequiredMark;

        return $this;
    }

    /**
     * 自定义折叠状态下默认显示的表单控件数量，没有设置或小于 0，则显示一行控件; 数量大于等于控件数量则隐藏展开按钮
     *
     * @param number $defaultColsNumber
     * @return $this
     */
    public function defaultColsNumber($defaultColsNumber)
    {
        $this->defaultColsNumber = $defaultColsNumber;

        return $this;
    }

    /**
     * label 宽度,number | 'auto'
     *
     * @param number|string $labelWidth
     * @return $this
     */
    public function labelWidth($labelWidth)
    {
        $this->labelWidth = $labelWidth;

        return $this;
    }

    /**
     * 表单项宽度,number[0 - 24]
     *
     * @param number $span
     * @return $this
     */
    public function span($span)
    {
        $this->span = $span;

        return $this;
    }

    /**
     * 每一行是否有分割线
     *
     * @param bool $split
     * @return $this
     */
    public function split($split = true)
    {
        $this->split = $split;

        return $this;
    }

    /**
     * 查找注册的类
     *
     * @param string $method
     * @return bool|mixed
     */
    public static function calledFieldClass($method)
    {
        $class = Arr::get(static::$registerFields, $method);

        if (class_exists($class)) {
            return $class;
        }

        return false;
    }

    /**
     * 动态调用类
     *
     * @param $method
     * @param $parameters
     * @return Column
     */
    public function __call($method, $parameters)
    {
        if ($className = static::calledFieldClass($method)) {

            $column = Arr::get($parameters, 0, ''); //[0];
            if($method == 'scope' || $method == 'group' || $method == 'where') {
                $element = new $className($column, array_slice($parameters, 1), $parameters[2]);
            } else {
                $element = new $className($column, array_slice($parameters, 1));
            }

            $this->items[] = $element;
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
        $this->key();

        return array_merge([
            'dateFormatter' => $this->dateFormatter,
            'labelAlign' => $this->labelAlign,
            'size' => $this->size,
            'defaultCollapsed' => $this->defaultCollapsed,
            'hideRequiredMark' => $this->hideRequiredMark,
            'defaultColsNumber' => $this->defaultColsNumber,
            'labelWidth' => $this->labelWidth,
            'span' => $this->span,
            'split' => $this->split,
            'items' => $this->items
        ], parent::jsonSerialize());
    }
}
