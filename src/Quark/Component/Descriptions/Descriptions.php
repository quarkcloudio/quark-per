<?php

namespace QuarkCloudIO\Quark\Component\Descriptions;

use QuarkCloudIO\Quark\Component\Element;
use Closure;
use Exception;

class Descriptions extends Element
{
    /**
     * 描述列表的标题，显示在最顶部
     *
     * @var string
     */
    public $title = null;

    /**
     * 内容的补充描述，hover 后显示
     *
     * @var string
     */
    public $tooltip = null;

    /**
     * 展示一个加载的骨架屏，骨架屏和 dom 不会一一对应
     *
     * @var bool
     */
    public $loading = false;

    /**
     * 是否展示边框
     *
     * @var bool
     */
    public $bordered = false;

    /**
     * 表单布局，horizontal|vertical
     *
     * @var string
     */
    public $layout = 'horizontal';

    /**
     * 一行的 ProDescriptionsItems 数量，可以写成像素值或支持响应式的对象写法 { xs: 8, sm: 16, md: 24}
     *
     * @var number|array
     */
    public $column = 1;

    /**
     * 列
     *
     * @var array
     */
    public $columns = [];

    /**
     * 数据
     *
     * @var array
     */
    public $dataSource = [];

    /**
     * 设置列表的大小。可以设置为 middle 、small, 或不填（只有设置 bordered={true} 生效）,default | middle | small
     *
     * @var string
     */
    public $size = 'default';

    /**
     * 配置 ProDescriptions.Item 的 colon 的默认值
     *
     * @var bool
     */
    public $colon = true;

    /**
     * 展示数据
     *
     * @var array
     */
    public $data = null;

    /**
     * 表单项
     *
     * @var array
     */
    public $items = null;

    /**
     * 行为
     *
     * @var array
     */
    public $actions = null;

    /**
     * 字段控件
     *
     * @var array
     */
    public static $classFields = [
        'text' => Fields\Text::class,
        'image' => Fields\Image::class,
        'link' => Fields\Link::class,
    ];

    /**
     * 初始化组件
     *
     * @param  array  $data
     * @return void
     */
    public function __construct($data = null)
    {
        $this->component = 'descriptions';
        $this->data = $data;
    }

    /**
     * 标题
     *
     * @param string $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * 内容的补充描述，hover 后显示
     *
     * @param string $tooltip
     * @return $this
     */
    public function tooltip($tooltip)
    {
        $this->tooltip = $tooltip;
        return $this;
    }

    /**
     * 展示一个加载的骨架屏，骨架屏和 dom 不会一一对应
     *
     * @param bool $loading
     * @return $this
     */
    public function loading($loading)
    {
        $this->loading = $loading;
        return $this;
    }

    /**
     * 是否展示边框
     *
     * @param bool $bordered
     * @return $this
     */
    public function bordered($bordered = true)
    {
        $this->bordered = $bordered;
        return $this;
    }

    /**
     *  配置 ProDescriptions.Item 的 colon 的默认值
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
     *  布局，horizontal|vertical
     *
     * @param  string  $layout
     * @return $this
     */
    public function layout($layout)
    {
        if(!in_array($layout,['horizontal', 'vertical'])) {
            throw new Exception("argument must be in 'horizontal', 'vertical'!");
        }

        $this->layout = $layout;
        return $this;
    }

    /**
     * 一行的 ProDescriptionsItems 数量，可以写成像素值或支持响应式的对象写法 { xs: 8, sm: 16, md: 24}
     *
     * @param number $column
     * @return $this
     */
    public function column($column)
    {
        $this->column = $column;
        return $this;
    }

    /**
     * 列
     *
     * @param number $columns
     * @return $this
     */
    public function columns($columns)
    {
        $this->columns = $columns;
        return $this;
    }

    /**
     * 数据
     *
     * @param array $dataSource
     * @return $this
     */
    public function dataSource($dataSource)
    {
        $this->dataSource = $dataSource;
        return $this;
    }

    /**
     * 数据项
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
     * 渲染前回调
     *
     * @return bool
     */
    public function showing(Closure $callback = null)
    {
        $callback($this);
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
        $class = static::$classFields[$method];

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

    /**
     * 填充数据
     *
     * @param array $rules
     *
     * @return array
     */
    protected function initialValues($data = null)
    {
        if(!empty($data)) {
            $this->data = $data;
        }

        if($this->items) {
            foreach ($this->items as $key => $item) {
                if(!empty($this->data)) {
                    $item->value = $this->data[$item->dataIndex];
                    $this->items[$key] = $item;
                }
            }
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

        $this->initialValues();

        return array_merge([
            'title' => $this->title,
            'tooltip' => $this->tooltip,
            'loading' => $this->loading,
            'bordered' => $this->bordered,
            'column' => $this->column,
            'size' => $this->size,
            'layout' => $this->layout,
            'colon' => $this->colon,
            'columns' => $this->columns,
            'items' => $this->items,
            'dataSource' => $this->dataSource,
            'actions' => $this->actions
        ], parent::jsonSerialize());
    }
}
