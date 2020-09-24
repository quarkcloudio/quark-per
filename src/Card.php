<?php

namespace QuarkCMS\QuarkAdmin;

use Closure;

class Card extends Element
{
    /**
     * 标题
     *
     * @var string
     */
    public $title;

    /**
     * 副标题
     *
     * @var string
     */
    public $subTitle;

    /**
     * 标题右侧图标 hover 提示信息
     *
     * @var string
     */
    public $tip;

    /**
     * 右上角自定义区域
     *
     * @var array
     */
    public $extra;

    /**
     * 内容布局，支持垂直居中 default | center
     *
     * @var string
     */
    public $layout = 'default';

    /**
     * 加载中，支持自定义 loading 样式
     *
     * @var bool
     */
    public $loading;

    /**
     * 栅格布局宽度，24 栅格，支持指定宽度 px 或百分比, 支持响应式的对象写法 { xs: 8, sm: 16, md: 24}
     *
     * @var number | string
     */
    public $colSpan = 24;

    /**
     * 数字或使用数组形式同时设置 [水平间距, 垂直间距], 支持响应式的对象写法 { xs: 8, sm: 16, md: 24}
     *
     * @var number | array
     */
    public $gutter = 0;

    /**
     * 拆分卡片的方向,vertical | horizontal
     *
     * @var string
     */
    public $split = 'vertical';

    /**
     * 是否有边框
     *
     * @var bool
     */
    public $bordered = false;

    /**
     * 幽灵模式，即是否取消卡片内容区域的 padding 和 卡片的背景颜色。
     *
     * @var bool
     */
    public $ghost = false;

    /**
     * 页头是否有分割线
     *
     * @var bool
     */
    public $headerBordered = false;

    /**
     * 配置是否可折叠，受控时无效
     *
     * @var bool
     */
    public $collapsible = false;

    /**
     * 默认折叠, 受控时无效
     *
     * @var bool
     */
    public $defaultCollapsed = false;

    /**
     * 卡牌内容
     *
     * @var string | array
     */
    public $content = null;

    /**
     * 初始化容器
     *
     * @param  string  $name
     * @param  \Closure|array  $content
     * @return void
     */
    public function __construct($title = '', $content = [])
    {
        $this->component = 'card';
        $this->title = $title;
        $this->content = $content;

        return $this;
    }

    /**
     * 设置标题文字
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
     * 设置二级标题文字
     *
     * @param  string  $subTitle
     * @return $this
     */
    public function subTitle($subTitle)
    {
        $this->subTitle = $subTitle;

        return $this;
    }

    /**
     * 标题右侧图标 hover 提示信息
     *
     * @param  string  $tip
     * @return $this
     */
    public function tip($tip)
    {
        $this->tip = $tip;

        return $this;
    }

    /**
     * 右上角自定义区域
     *
     * @param  array  $extra
     * @return $this
     */
    public function extra($extra)
    {
        $this->extra = $extra;

        return $this;
    }

    /**
     * 内容布局，支持垂直居中 default | center
     *
     * @param  string  $layout
     * @return $this
     */
    public function layout($layout)
    {
        if(!in_array($layout,['default', 'center'])) {
            throw new \Exception("Argument must be in 'default', 'center'!");
        }

        $this->layout = $layout;

        return $this;
    }

    /**
     * 栅格布局宽度，24 栅格，支持指定宽度 px 或百分比, 支持响应式的对象写法 { xs: 8, sm: 16, md: 24}
     *
     * @param  number|string  $colSpan
     * @return $this
     */
    public function colSpan($colSpan)
    {
        $this->colSpan = $colSpan;

        return $this;
    }

    /**
     * 数字或使用数组形式同时设置 [水平间距, 垂直间距], 支持响应式的对象写法 { xs: 8, sm: 16, md: 24}
     *
     * @param  number|array  $gutter
     * @return $this
     */
    public function gutter($gutter)
    {
        $this->gutter = $gutter;

        return $this;
    }

    /**
     * 拆分卡片的方向,vertical | horizontal
     *
     * @param  string  $split
     * @return $this
     */
    public function split($split)
    {
        if(!in_array($split,['vertical', 'horizontal'])) {
            throw new \Exception("Argument must be in 'vertical', 'horizontal'!");
        }

        $this->split = $split;

        return $this;
    }

    /**
     * 是否有边框
     *
     * @param  bool  $bordered
     * @return $this
     */
    public function bordered($bordered = true)
    {
        $bordered ? $this->bordered = true : $this->bordered = false;

        return $this;
    }

    /**
     * 幽灵模式，即是否取消卡片内容区域的 padding 和 卡片的背景颜色。
     *
     * @param  bool  $ghost
     * @return $this
     */
    public function ghost($ghost = true)
    {
        $ghost ? $this->ghost = true : $this->ghost = false;

        return $this;
    }

    /**
     * 页头是否有分割线
     *
     * @param  bool  $headerBordered
     * @return $this
     */
    public function headerBordered($headerBordered = true)
    {
        $headerBordered ? $this->headerBordered = true : $this->headerBordered = false;

        return $this;
    }

    /**
     * 配置是否可折叠，受控时无效
     *
     * @param  bool  $collapsible
     * @return $this
     */
    public function collapsible($collapsible = true)
    {
        $collapsible ? $this->collapsible = true : $this->collapsible = false;

        return $this;
    }

    /**
     * 默认折叠, 受控时无效
     *
     * @param  bool  $defaultCollapsed
     * @return $this
     */
    public function defaultCollapsed($defaultCollapsed = true)
    {
        $defaultCollapsed ? $this->defaultCollapsed = true : $this->defaultCollapsed = false;

        return $this;
    }

    /**
     * 容器控件里面的内容
     *
     * @param  string|array  $content
     * @return $this
     */
    public function content($content)
    {
        $this->content = $content;

        return $this;
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key(__CLASS__.$this->title.$this->subTitle);

        return array_merge([
            'title' => $this->title,
            'subTitle' => $this->subTitle,
            'tip' => $this->tip,
            'extra' => $this->extra,
            'layout' => $this->layout,
            'colSpan' => $this->colSpan,
            'gutter' => $this->gutter,
            'split' => $this->split,
            'bordered' => $this->bordered,
            'ghost' => $this->ghost,
            'headerBordered' => $this->headerBordered,
            'collapsible' => $this->collapsible,
            'defaultCollapsed' => $this->defaultCollapsed,
            'content' => $this->content
        ], parent::jsonSerialize());
    }
}
