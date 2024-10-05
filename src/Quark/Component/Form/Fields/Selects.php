<?php

namespace QuarkCloudIO\Quark\Component\Form\Fields;

use QuarkCloudIO\Quark\Component\Form\Fields\Item;
use Exception;

class Selects extends Item
{
    /**
     * 组件类型
     *
     * @var string
     */
    public $component = 'selects';

    /**
     * 内容
     *
     * @var array
     */
    public $body = null;

    /**
     * 初始化组件
     *
     * @param  array  $body
     * 
     * @return void
     */
    public function __construct($body) {

        $this->body = $body;
    }


    /**
     * 内容
     *
     * @param  array $body
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
        return array_merge([
            'body' => $this->body
        ], parent::jsonSerialize());
    }
}
