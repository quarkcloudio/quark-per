<?php

namespace QuarkCloudIO\Quark\Component\Tabs;

use QuarkCloudIO\Quark\Component\Element;

class TabPane extends Element
{
    /**
     * 标签标题
     *
     * @var string
     */
    public $title = null;

    /**
     * 内容
     *
     * @var bool|string|number|array
     */
    public $body = null;

    /**
     * 初始化组件
     *
     * @param  string  $title
     * @return void
     */
    public function __construct($title = null, $body = '') {
        $this->component = 'tabPane';
        $this->title = $title;
        $this->body = $body;

        return $this;
    }

    /**
     * 标签标题
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
     * 内容
     *
     * @param  bool|string|number|array  $body
     * @return $this
     */
    public function body($body)
    {
        $this->body = $body;

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
            $this->key(json_encode($this), true);
        }

        return array_merge([
            'title' => $this->title,
            'body' => $this->body
        ], parent::jsonSerialize());
    }
}