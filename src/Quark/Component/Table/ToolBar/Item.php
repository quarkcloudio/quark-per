<?php

namespace QuarkCloudIO\Quark\Component\Table\ToolBar;

use Exception;
use QuarkCloudIO\Quark\Component\Element;

class Item extends Element
{
    /**
     * 名称
     *
     * @var string
     */
    public $label = null;

    /**
     * 初始化
     *
     * @param  string  $key
     * @param  string  $label
     * @return void
     */
    public function __construct($key = '', $label = '') {
        $this->component = 'toolBarMenuItem';
        $this->key = $key;

        if(empty($label)) {
            $this->label = $key;
        } else {
            $this->label = $label;
        }
    }

    /**
     * label 标签的文本
     *
     * @param string $label
     * @return $this
     */
    public function label($label = '')
    {
        $this->label = $label;

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
            'label' => $this->label,
        ], parent::jsonSerialize());
    }
}
