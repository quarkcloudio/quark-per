<?php

namespace QuarkCloudIO\Quark\Component\Form;

use QuarkCloudIO\Quark\Component\Element;
use Exception;

class Form extends Element
{
    /**
     * 表单标题
     *
     * @var string
     */
    public $title = null;

    /**
     * 表单宽度
     *
     * @var string|number
     */
    public $width = null;

    /**
     * 配置 Form.Item 的 colon 的默认值。表示是否显示 label 后面的冒号 (只有在属性 layout 为 horizontal 时有效)
     *
     * @var bool
     */
    public $colon = true;

    /**
     * 表单原始数据
     *
     * @var array
     */
    public $values = [];

    /**
     * 解析完之后表单数据
     *
     * @var array
     */
    public $initialValues = [];

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
     * label 标签布局，同 <Col> 组件，设置 span offset 值，如 {span: 3, offset: 12} 或 sm: {span: 3, offset: 12}
     *
     * @var array
     */
    public $labelCol = ['span' => 4];

    /**
     * 需要为输入控件设置布局样式时，使用该属性，用法同 labelCol
     *
     * @var string
     */
    public $wrapperCol = ['span' => 20];

    /**
     * 表单按钮布局样式
     *
     * @var string
     */
    public $buttonWrapperCol = ['offset' => 4, 'span' => 20 ];

    /**
     * 表单提交的地址
     *
     * @var string
     */
    public $api = null;

    /**
     * 表单提交的类型
     *
     * @var string
     */
    public $apiType = 'POST';

    /**
     * 提交表单的数据是否打开新页面，只有在GET类型的时候有效
     *
     * @var bool
     */
    public $targetBlank = false;

    /**
     * 获取表单数据
     *
     * @var string
     */
    public $initApi = null;

    /**
     * 表单项
     *
     * @var array
     */
    public $items = [];

    /**
     * 行为
     *
     * @var array
     */
    public $actions = null;

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
    ];

    /**
     * 初始化表单组件
     *
     * @param  string  $name
     * @return void
     */
    public function __construct()
    {
        $this->component = 'form';

        return $this;
    }

    /**
     *  配置表单标题
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
     *  配置表单宽度
     *
     * @param  string  $width
     * @return $this
     */
    public function width($width)
    {
        $this->width = $width;

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
     *  解析initialValue
     *
     * @param  object  $item
     * @return $this
     */
    public function parseInitialValue($item, $initialValues)
    {
        if(isset($item->name)) {

            if(isset($item->defaultValue)) {
                $value = $item->defaultValue;
            }

            if(isset($initialValues[$item->name])) {
                $value = $initialValues[$item->name];
            }

            if(isset($item->value)) {
                $value = $item->value;
            }
        }

        return $value ?? null;
    }

    /**
     *  表单默认值，只有初始化以及重置时生效
     *
     * @param  array  $initialValues
     * @return $this
     */
    public function initialValues($initialValues = [])
    {
        $data = $initialValues;

        foreach ($this->items as $item) {

            if(!empty($item->when)) {
                $whenItems = [];
                foreach ($item->when['items'] as $when) {
                    $body = $when['body'];
                    if(is_array($body)) {
                        $whenItems = array_merge($whenItems, $body);
                    } elseif(is_object($body)) {
                        $whenItems[] = $body;
                    }
                }

                foreach ($whenItems as $whenKey => $whenItem) {
                    $whenItemValue = $this->parseInitialValue($whenItem,$initialValues);

                    if($whenItemValue !== null) {
                        $data[$whenItem->name] = $whenItemValue;
                    }
                }
            }

            $value = $this->parseInitialValue($item,$initialValues);

            if($value !== null) {
                $data[$item->name] = $value;
            }
        }

        foreach ($data as $key => $value) {
            if(is_string($value)) {
                if(count(explode('[',$value))>1 || count(explode('{',$value))>1) {
                    $getValue = json_decode($value, true);
                    if($getValue) {
                        $value = $getValue;
                    } else {
                        $value = $value;
                    }
                }
            }

            $data[$key] = $value;
        }

        $this->initialValues = $data;

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
     *  表单布局，horizontal|vertical
     *
     * @param  string  $layout
     * @return $this
     */
    public function layout($layout)
    {
        if(!in_array($layout,['horizontal', 'vertical'])) {
            throw new Exception("argument must be in 'horizontal', 'vertical'!");
        }

        if($layout === 'vertical') {
            $this->labelCol = null;
            $this->wrapperCol = null;
            $this->buttonWrapperCol = null;
        }

        $this->layout = $layout;

        return $this;
    }

    /**
     *  label 标签布局，同 <Col> 组件，设置 span offset 值，如 {span: 3, offset: 12} 或 sm: {span: 3, offset: 12}
     *
     * @param  array  $labelCol
     * @return $this
     */
    public function labelCol($labelCol)
    {
        if($this->layout === 'vertical') {
            throw new Exception("If layout set vertical mode,can't set labelCol!");
        }

        $this->labelCol = $labelCol;

        return $this;
    }

    /**
     *  需要为输入控件设置布局样式时，使用该属性，用法同 labelCol
     *
     * @param  array  $wrapperCol
     * @return $this
     */
    public function wrapperCol($wrapperCol)
    {
        if($this->layout === 'vertical') {
            throw new Exception("If layout set vertical mode,can't set wrapperCol!");
        }

        $this->wrapperCol = $wrapperCol;

        return $this;
    }

    /**
     *  表单按钮布局样式,默认：['offset' => 2, 'span' => 22 ]
     *
     * @param  array  $buttonWrapperCol
     * @return $this
     */
    public function buttonWrapperCol($buttonWrapperCol)
    {
        if($this->layout === 'vertical') {
            throw new Exception("If layout set vertical mode,can't set buttonWrapperCol!");
        }

        $this->buttonWrapperCol = $buttonWrapperCol;

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
     *  表单提交接口的类型
     *
     * @param  string  $apiType
     * @return $this
     */
    public function apiType($apiType)
    {
        $this->apiType = $apiType;

        return $this;
    }

    /**
     *  提交表单的数据是否打开新页面，只有在GET类型的时候有效
     *
     * @param  bool  $targetBlank
     * @return $this
     */
    public function targetBlank($targetBlank = true)
    {
        $this->targetBlank = $targetBlank;

        return $this;
    }

    /**
     *  获取表单数据
     *
     * @param  string  $initApi
     * @return $this
     */
    public function initApi($initApi)
    {
        $this->initApi = $initApi;

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
     *  与items方法相同
     *
     * @param  array  $items
     * @return $this
     */
    public function body($items)
    {
        $this->items = $items;

        return $this;
    }

    /**
     * 解析保存提交数据库前的值
     *
     * @param array $rules
     * @return array
     */
    protected function parseSubmitData($data)
    {
        $items = $this->items;

        foreach ($items as $key => $item) {

            // 删除忽略的值
            if($item->ignore) {
                if(isset($data[$item->name])) {
                    unset($data[$item->name]);
                }
            }
        }

        foreach ($data as $key => $value) {
            if(is_array($value)) {
                $data[$key] = json_encode($value);
            }
        }

        return $data;
    }

    /**
     * 行为
     *
     * @param array $actions
     * @return $this
     */
    public function actions($actions)
    {
        $this->actions = $actions;

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
        if(empty($this->key)) {
            $this->key();
        }

        // 为空，初始化表单数据
        if(empty($this->initialValues)) {
            $this->initialValues($this->values);
        }

        return array_merge([
            'api' => $this->api,
            'apiType' => strtoupper($this->apiType),
            'targetBlank' => $this->targetBlank,
            'initApi' => $this->initApi,
            'colon' => $this->colon,
            'initialValues' => $this->initialValues,
            'labelAlign' => $this->labelAlign,
            'title' => $this->title,
            'width' => $this->width,
            'name' => $this->name,
            'actions' => $this->actions,
            'preserve' => $this->preserve,
            'requiredMark' => $this->requiredMark,
            'scrollToFirstError' => $this->scrollToFirstError,
            'size' => $this->size,
            'dateFormatter' => $this->dateFormatter,
            'layout' => $this->layout,
            'labelCol' => $this->labelCol,
            'wrapperCol' => $this->wrapperCol,
            'buttonWrapperCol' => $this->buttonWrapperCol,
            'body' => $this->items,
        ], parent::jsonSerialize());
    }
}
