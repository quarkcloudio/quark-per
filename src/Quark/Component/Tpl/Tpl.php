<?php

namespace QuarkCloudIO\Quark\Component\Tpl;

use QuarkCloudIO\Quark\Component\Element;

class Tpl extends Element
{
    /**
     * 内容
     *
     * @var bool|string|number|array
     */
    public $body = null;

    /**
     * 初始化容器
     *
     * @param  string  $body
     * @return void
     */
    public function __construct($body = '')
    {
        $this->component = 'tpl';
        $this->body = $body;

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
            $this->key($this->component.$this->body, true);
        }

        return array_merge([
            'body' => $this->body,
        ], parent::jsonSerialize());
    }
}