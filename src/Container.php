<?php

namespace QuarkCMS\QuarkAdmin;

use Closure;

class Container extends Element
{
    /**
     * 自定义标题文字
     *
     * @var string
     */
    public $title;

    /**
     * 自定义的二级标题文字
     *
     * @var string
     */
    public $subTitle;

    /**
     * pageHeader 的类型，将会改变背景颜色
     *
     * @var bool
     */
    public $ghost = true;

    /**
     * 标题栏旁的头像
     *
     * @var string
     */
    public $avatar;

    /**
     * 自定义 back icon ，如果为 false 不渲染 back icon，默认：<ArrowLeft />
     *
     * @var string
     */
    public $backIcon;

    /**
     * title 旁的 tag 列表
     *
     * @var array
     */
    public $tags;

    /**
     * 操作区，位于 title 行的行尾
     *
     * @var array
     */
    public $extra;

    /**
     * 面包屑的配置
     *
     * @var array
     */
    public $breadcrumb;

    /**
     * PageHeader 的页脚，一般用于渲染 TabBar
     *
     * @var array
     */
    public $footer;

    /**
     * 返回按钮
     *
     * @var bool
     */
    public $backButton = false;

    /**
     * 内容区
     *
     * @var array
     */
    public $content;

    /**
     * 额外内容区，位于 content 的右侧
     *
     * @var array
     */
    public $extraContent;

    /**
     * 当前高亮的 tab 项
     *
     * @var string
     */
    public $tabActiveKey;

    /**
     * tab bar 上额外的元素
     *
     * @var array
     */
    public $tabBarExtraContent;

    /**
     * 初始化容器
     *
     * @param  string  $name
     * @param  \Closure|array  $content
     * @return void
     */
    public function __construct($title = '', $content = [])
    {
        $this->component = 'container';
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
     * 返回按钮
     *
     * @param  bool  $backButton
     * @return $this
     */
    public function backButton($backButton = true)
    {
        $backButton ? $this->backButton = true : $this->backButton = false;

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
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key(__CLASS__.$this->title.$this->subTitle);

        return array_merge([
            'title' => $this->title,
            'subTitle' => $this->subTitle,
            'breadcrumb' => $this->breadcrumb,
            'backButton' => $this->backButton,
            'content' => $this->content
        ], parent::jsonSerialize());
    }
}
