<?php

namespace QuarkCloudIO\Quark\Component\Action;

use QuarkCloudIO\Quark\Component\Element;

class Modal extends Element
{
    /**
     * 标题
     *
     * @var string
     */
    public $title;

    /**
     * Modal body 样式
     *
     * @var array
     */
    public $bodyStyle = [];

    /**
     * 垂直居中展示 Modal
     *
     * @var bool
     */
    public $centered = false;

    /**
     * 是否显示右上角的关闭按钮
     *
     * @var bool
     */
    public $closable = true;

    /**
     * 关闭时销毁 Modal 里的子元素
     *
     * @var bool
     */
    public $destroyOnClose = false;

    /**
     * 对话框关闭后是否需要聚焦触发元素
     *
     * @var bool
     */
    public $focusTriggerAfterClose = true;

    /**
     * 是否支持键盘 esc 关闭
     *
     * @var bool
     */
    public $keyboard = true;

    /**
     * 是否展示遮罩
     *
     * @var bool
     */
    public $mask = true;

    /**
     * 点击蒙层是否允许关闭
     *
     * @var bool
     */
    public $maskClosable = true;

    /**
     * 遮罩样式
     *
     * @var array
     */
    public $maskStyle = [];

    /**
     * 对话框是否可见
     *
     * @var bool
     */
    public $visible = false;

    /**
     * 宽度
     *
     * @var string | number
     */
    public $width = 520;

    /**
     * 设置 Modal 的 z-index
     *
     * @var number
     */
    public $zIndex = 1000;

    /**
     * 弹窗行为
     *
     * @var array
     */
    public $actions = [];

    /**
     * 初始化容器
     *
     * @param  string  $name
     * @param  \Closure|array  $body
     * @return $this
     */
    public function __construct($title = '', $body = [])
    {
        $this->component = 'modal';
        $this->title = $title;
        $this->body = $body;

        return $this;
    }

    /**
     * 标题
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
     * Modal body 样式
     *
     * @param  array  $style
     * @return $this
     */
    public function bodyStyle($style)
    {
        $this->bodyStyle = $style;

        return $this;
    }

    /**
     * 容器控件里面的内容
     *
     * @param  string|array  $body
     * @return $this
     */
    public function body($body)
    {
        $this->body = $body;

        return $this;
    }

    /**
     * 垂直居中展示 Modal
     *
     * @param  bool  $centered
     * @return $this
     */
    public function centered($centered = true)
    {
        $this->centered = $centered;

        return $this;
    }

    /**
     * 是否显示右上角的关闭按钮
     *
     * @param  bool  $closable
     * @return $this
     */
    public function closable($closable = true)
    {
        $this->closable = $closable;

        return $this;
    }

    /**
     * 关闭时销毁 Modal 里的子元素
     *
     * @param  bool  $destroyOnClose
     * @return $this
     */
    public function destroyOnClose($destroyOnClose = true)
    {
        $this->destroyOnClose = $destroyOnClose;

        return $this;
    }

    /**
     * 设置按钮形状，可选值为 circle、 round 或者不设
     *
     * @param  bool  $focusTriggerAfterClose
     * @return $this
     */
    public function focusTriggerAfterClose($focusTriggerAfterClose = true)
    {
        $this->focusTriggerAfterClose = $focusTriggerAfterClose;

        return $this;
    }

    /**
     * 是否支持键盘 esc 关闭
     *
     * @param  bool  $keyboard
     * @return $this
     */
    public function keyboard($keyboard = true)
    {
        $this->keyboard = $keyboard;

        return $this;
    }

    /**
     * 是否展示遮罩
     *
     * @param  bool  $mask
     * @return $this
     */
    public function mask($mask = true)
    {
        $this->mask = $mask;

        return $this;
    }

    /**
     * 点击蒙层是否允许关闭
     *
     * @param  bool  $maskClosable
     * @return $this
     */
    public function maskClosable($maskClosable = true)
    {
        $this->maskClosable = $maskClosable;

        return $this;
    }

    /**
     * 遮罩样式
     *
     * @param  array  $style
     * @return $this
     */
    public function maskStyle($style)
    {
        $this->maskStyle = $style;

        return $this;
    }

    /**
     * 对话框是否可见
     *
     * @param  bool  $visible
     * @return $this
     */
    public function visible($visible = false)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * 宽度
     *
     * @param  string | number  $width
     * @return $this
     */
    public function width($width)
    {
        $this->width = $width;

        return $this;
    }

    /**
     * 设置 Modal 的 z-index
     *
     * @param  number  $zIndex
     * @return $this
     */
    public function zIndex($zIndex)
    {
        $this->zIndex = $zIndex;

        return $this;
    }

    /**
     * 弹窗行为
     *
     * @param  array  $actions
     * @return $this
     */
    public function actions($actions)
    {
        $this->actions = $actions;

        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge([
            'title' => $this->title,
            'bodyStyle' => $this->bodyStyle,
            'body' => $this->body,
            'centered' => $this->centered,
            'closable' => $this->closable,
            'destroyOnClose' => $this->destroyOnClose,
            'focusTriggerAfterClose' => $this->focusTriggerAfterClose,
            'keyboard' => $this->keyboard,
            'mask' => $this->mask,
            'maskClosable' => $this->maskClosable,
            'maskStyle' => $this->maskStyle,
            'visible' => $this->visible,
            'width' => $this->width,
            'zIndex' => $this->zIndex,
            'actions' => $this->actions
        ], parent::jsonSerialize());
    }
}
