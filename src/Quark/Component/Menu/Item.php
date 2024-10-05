<?php

namespace QuarkCloudIO\Quark\Component\Menu;

use QuarkCloudIO\Quark\Component\Action\Action;

class Item extends Action
{
    /**
     * 设置收缩时展示的悬浮标题
     *
     * @var string
     */
    public $title;

    /**
     * 初始化容器
     *
     * @param  string  $label
     * @param  string  $title
     * @return $this
     */
    public function __construct($label = '', $title = '')
    {
        $this->component = 'menuItem';
        $this->label = $label;
        $this->title = $title;

        return $this;
    }

    /**
     * 设置收缩时展示的悬浮标题
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
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        if(empty($this->key)) {
            $this->key($this->label.$this->title.$this->api.$this->href.json_encode($this->modal).json_encode($this->drawer), true);
        }

        return array_merge([
            'title' => $this->title,
        ], parent::jsonSerialize());
    }
}