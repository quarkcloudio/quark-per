<?php

namespace QuarkCMS\QuarkAdmin;

use Closure;
use Illuminate\Database\Eloquent\Model as Eloquent;
use Illuminate\Support\Arr;
use QuarkCMS\QuarkAdmin\Components\Table\Model;

class Form extends Element
{
    /**
     * 配置 Form.Item 的 colon 的默认值。表示是否显示 label 后面的冒号 (只有在属性 layout 为 horizontal 时有效)
     *
     * @var bool
     */
    public $colon = true;

    /**
     * 表单默认值，只有初始化以及重置时生效
     *
     * @var array
     */
    public $initialValues = null;

    /**
     * label 标签的文本对齐方式,left | right
     *
     * @var string
     */
    public $labelAlign = 'right';

    /**
     * 表单名称，会作为表单字段 id 前缀使用
     *
     * @var string
     */
    public $name = null;

    /**
     * 当字段被删除时保留字段值
     *
     * @var bool
     */
    public $preserve = true;

    /**
     * 必选样式，可以切换为必选或者可选展示样式。此为 Form 配置，Form.Item 无法单独配置
     *
     * @var bool
     */
    public $requiredMark = true;

    /**
     * 提交失败自动滚动到第一个错误字段
     *
     * @var bool
     */
    public $scrollToFirstError = false;

    /**
     * 设置字段组件的尺寸（仅限 antd 组件）,small | middle | large
     *
     * @var string
     */
    public $size = 'default';

    /**
     * 自动格式数据，例如 moment 的表单,支持 string 和 number 两种模式
     *
     * @var string
     */
    public $dateFormatter = 'string';

    /**
     * 表单布局，horizontal|vertical
     *
     * @var string
     */
    public $layout = 'horizontal';

    /**
     * 表格提交的地址
     *
     * @var string
     */
    public $api = null;

    /**
     * 表单项
     *
     * @var array
     */
    public $items = null;

    /**
     * 绑定的模型
     *
     * @var object
     */
    public $model;

    /**
     * eloquent模型
     *
     * @var object
     */
    public $eloquentModel;

    /**
     * 表单字段控件
     *
     * @var array
     */
    public static $formFields = [
        'id' => Components\Form\Fields\ID::class,
        'display' => Components\Form\Fields\Display::class,
        'text' => Components\Form\Fields\Text::class,
        'textarea' => Components\Form\Fields\TextArea::class,
        'textArea' => Components\Form\Fields\TextArea::class,
        'number' => Components\Form\Fields\Number::class,
        'radio' => Components\Form\Fields\Radio::class,
        'image' => Components\Form\Fields\Image::class,
        'file' => Components\Form\Fields\File::class,
        'tree' => Components\Form\Fields\Tree::class,
        'select' => Components\Form\Fields\Select::class,
        'checkbox' => Components\Form\Fields\Checkbox::class,
        'icon' => Components\Form\Fields\Icon::class,
        'switch' => Components\Form\Fields\SwitchField::class,
        'icon' => Components\Form\Fields\Icon::class,
        'datetime' => Components\Form\Fields\Datetime::class,
        'datetimeRange' => Components\Form\Fields\DatetimeRange::class,
        'timeRange' => Components\Form\Fields\TimeRange::class,
        'editor' => Components\Form\Fields\Editor::class,
        'map' => Components\Form\Fields\Map::class,
        'cascader' => Components\Form\Fields\Cascader::class,
        'search' => Components\Form\Fields\Search::class,
        'list' => Components\Form\Fields\ListField::class,
    ];

    /**
     * 初始化容器
     *
     * @param  string  $name
     * @param  \Closure|array  $content
     * @return void
     */
    public function __construct(Eloquent $model)
    {
        $this->component = 'form';
        $this->model = new Model($model,$this);
        return $this;
    }

    /**
     *  配置 Form.Item 的 colon 的默认值。表示是否显示 label 后面的冒号 (只有在属性 layout 为 horizontal 时有效)
     *
     * @param  bool  $colon
     * @return $this
     */
    public function colon($colon)
    {
        $this->colon = $colon;
        return $this;
    }

    /**
     *  表单默认值，只有初始化以及重置时生效
     *
     * @param  array  $initialValues
     * @return $this
     */
    public function initialValues($initialValues)
    {
        $this->initialValues = $initialValues;
        return $this;
    }

    /**
     *  label 标签的文本对齐方式,left | right
     *
     * @param  string  $labelAlign
     * @return $this
     */
    public function labelAlign($labelAlign)
    {
        $this->labelAlign = $labelAlign;
        return $this;
    }

    /**
     *  表单名称，会作为表单字段 id 前缀使用
     *
     * @param  string  $name
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     *  当字段被删除时保留字段值
     *
     * @param  bool  $preserve
     * @return $this
     */
    public function preserve($preserve)
    {
        $this->preserve = $preserve;
        return $this;
    }

    /**
     *  必选样式，可以切换为必选或者可选展示样式。此为 Form 配置，Form.Item 无法单独配置
     *
     * @param  bool  $requiredMark
     * @return $this
     */
    public function requiredMark($requiredMark)
    {
        $this->requiredMark = $requiredMark;
        return $this;
    }

    /**
     *  提交失败自动滚动到第一个错误字段
     *
     * @param  bool  $scrollToFirstError
     * @return $this
     */
    public function scrollToFirstError($scrollToFirstError)
    {
        $this->scrollToFirstError = $scrollToFirstError;
        return $this;
    }

    /**
     *  设置字段组件的尺寸（仅限 antd 组件）,small | middle | large
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
     *  自动格式数据，例如 moment 的表单,支持 string 和 number 两种模式
     *
     * @param  string  $dateFormatter
     * @return $this
     */
    public function dateFormatter($dateFormatter)
    {
        $this->dateFormatter = $dateFormatter;
        return $this;
    }

    /**
     *  表单提交的接口链接
     *
     * @param  string  $api
     * @return $this
     */
    public function api($api)
    {
        $this->api = $api;
        return $this;
    }

    /**
     *  表单项
     *
     * @param  array  $items
     * @return $this
     */
    public function items($items)
    {
        $this->items = $items;
        return $this;
    }

    /**
     * 读取模型
     *
     * @return $this
     */
    public function model()
    {
        return $this->model;
    }

    /**
     * 获取行为类
     *
     * @param string $method
     *
     * @return bool|mixed
     */
    public static function getCalledClass($method)
    {
        $class = Arr::get(static::$formFields, $method);

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

            $column = Arr::get($parameters, 0, ''); //[0];
            $element = new $className($column, array_slice($parameters, 1));
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
            'colon' => $this->colon,
            'initialValues' => $this->initialValues,
            'labelAlign' => $this->labelAlign,
            'name' => $this->name,
            'preserve' => $this->preserve,
            'requiredMark' => $this->requiredMark,
            'scrollToFirstError' => $this->scrollToFirstError,
            'size' => $this->size,
            'dateFormatter' => $this->dateFormatter,
            'layout' => $this->layout,
            'items' => $this->items,
        ], parent::jsonSerialize());
    }
}
