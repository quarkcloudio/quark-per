<?php

namespace QuarkCloudIO\Quark\Component\Layout;

use QuarkCloudIO\Quark\Component\Element;

class Page extends Element
{
    /**
     * 页面标题
     *
     * @var string
     */
    public $title = null;

    /**
     * 页面内容
     *
     * @var string|object
     */
    public $body = null;

    /**
     * 初始化容器
     *
     * @param  void
     * @return object
     */
    public function __construct()
    {
        $this->component = 'page';

        return $this;
    }

    /**
     * 页面的 title
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
     * @param  bool  $body
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
            $this->key(__CLASS__.$this->title, true);
        }

        return array_merge([
            'title' => $this->title,
            'body' => $this->body
        ], parent::jsonSerialize());
    }
}
