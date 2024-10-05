<?php

namespace QuarkCloudIO\Quark\Component\Layout\PageContainer;

use QuarkCloudIO\Quark\Component\Element;
use Closure;

class PageHeader extends Element
{
    /**
     * 标题栏旁的头像
     *
     * @var array
     */
    public $avatar;

    /**
     * 自定义 back icon ，如果为 false 不渲染 back icon
     *
     * @var array|bool
     */
    public $backIcon = false;

    /**
     * 面包屑的配置
     *
     * @var array
     */
    public $breadcrumb;

    /**
     * 自定义面包屑区域的内容
     *
     * @var array
     */
    public $breadcrumbRender;

    /**
     * 操作区，位于 title 行的行尾
     *
     * @var array
     */
    public $extra;

    /**
     * PageHeader 的页脚，一般用于渲染 TabBar
     *
     * @var array
     */
    public $footer;

    /**
     * pageHeader 的类型，将会改变背景颜色
     *
     * @var bool
     */
    public $ghost = false;

    /**
     * 自定义的二级标题文字
     *
     * @var string|array
     */
    public $subTitle;

    /**
     * title 旁的 tag 列表
     *
     * @var array
     */
    public $tags;

    /**
     * 自定义标题文字
     *
     * @var string|array
     */
    public $title;

    /**
     * 初始化容器
     *
     * @param  string  $title
     * @param  string  $subTitle
     * @return void
     */
    public function __construct($title = '', $subTitle = '')
    {
        $this->component = 'pageHeader';
        $this->title = $title;
        $this->subTitle = $subTitle;

        return $this;
    }

    /**
     * 标题栏旁的头像
     *
     * @param  string  $avatar
     * @return $this
     */
    public function avatar($avatar)
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * 自定义 back icon ，如果为 false 不渲染 back icon
     *
     * @param  bool|array  $backIcon
     * @return $this
     */
    public function backIcon($backIcon = true)
    {
        $this->subTitle = $subTitle;

        return $this;
    }

    /**
     * 面包屑
     *
     * @param  array  $breadcrumb
     * @return $this
     */
    public function breadcrumb($breadcrumb)
    {
        $this->breadcrumb = $breadcrumb;

        return $this;
    }

    /**
     * 自定义面包屑区域的内容
     *
     * @param  array  $breadcrumbRender
     * @return $this
     */
    public function breadcrumbRender($breadcrumbRender)
    {
        $this->breadcrumbRender = $breadcrumbRender;

        return $this;
    }

    /**
     * 操作区，位于 title 行的行尾
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
     * PageHeader 的页脚，一般用于渲染 TabBar
     *
     * @param  array  $footer
     * @return $this
     */
    public function footer($footer)
    {
        $this->footer = $footer;

        return $this;
    }

    /**
     * pageHeader 的类型，将会改变背景颜色
     *
     * @param  bool  $ghost
     * @return $this
     */
    public function ghost($ghost = true)
    {
        $this->ghost = $ghost;

        return $this;
    }

    /**
     * 自定义的二级标题文字
     *
     * @param  string|array  $subTitle
     * @return $this
     */
    public function subTitle($subTitle)
    {
        $this->subTitle = $subTitle;

        return $this;
    }

    /**
     * title 旁的 tag 列表
     *
     * @param  string|array  $tags
     * @return $this
     */
    public function tags($tags)
    {
        $this->tags = $tags;

        return $this;
    }

    /**
     * 自定义标题文字
     *
     * @param  string|array  $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;

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
            $this->key(__CLASS__.$this->title.$this->subTitle, true);
        }

        return array_merge([
            'avatar' => $this->avatar,
            'backIcon' => $this->backIcon,
            'breadcrumb' => $this->breadcrumb,
            'breadcrumbRender' => $this->breadcrumbRender,
            'extra' => $this->extra,
            //'footer' => $this->footer,
            'ghost' => $this->ghost,
            'subTitle' => $this->subTitle,
            'tags' => $this->tags,
            'title' => $this->title
        ], parent::jsonSerialize());
    }
}
