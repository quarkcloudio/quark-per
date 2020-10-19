<?php

namespace QuarkCMS\QuarkAdmin;

use Illuminate\Support\Arr;
use Closure;
use Exception;

class Show extends Element
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
     * 字段控件
     *
     * @var array
     */
    public static $classFields = [
        'field' => Components\Show\Field::class,
    ];

    /**
     * 初始化组件
     *
     * @param  array  $data
     * @return void
     */
    public function __construct($data = null)
    {
        $this->component = 'show';
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
    public function bordered($bordered)
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
     * 渲染前回调
     *
     * @return bool
     */
    public function showing(Closure $callback = null)
    {
        $callback($this);
    }

    /**
     * 获取注册类
     *
     * @param string $method
     * @return bool|mixed
     */
    public static function getCalledClass($method)
    {
        $class = Arr::get(static::$classFields, $method);

        if (class_exists($class)) {
            return $class;
        }

        return false;
    }

    /**
     * 动态调用类
     *
     * @param string $method
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
                    $item->value = $this->data[$item->name];
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
            'tooltip' =>$this->tooltip,
            'loading' =>$this->loading,
            'bordered' =>$this->bordered,
            'column' =>$this->column,
            'size' =>$this->size,
            'layout' => $this->layout,
            'colon' =>$this->colon,
            'items' => $this->items,
        ], parent::jsonSerialize());
    }
}
