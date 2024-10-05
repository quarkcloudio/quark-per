<?php

namespace QuarkCloudIO\Quark\Component\Form\Fields;

use QuarkCloudIO\Quark\Component\Form\Fields\Item;
use Exception;

class Search extends Item
{
    /**
     * 组件类型
     *
     * @var string
     */
    public $component = 'searchField';

    /**
     * mode
     *
     * @var string
     */
    public $mode;

    /**
     * 控件大小。注：标准表单内的输入框大小限制为 large。可选 large default small
     *
     * @var string
     */
    public $size = null;

    /**
     * 可以点击清除图标删除内容
     *
     * @var bool
     */
    public $allowClear = true;

    /**
     * 控件占位符
     *
     * @var string
     */
    public $placeholder = '请输入要搜索的内容';

    /**
     * 与 select 相同，根据 options 生成子节点，推荐使用。
     *
     * @var array
     */
    public $options = [];

    /**
     * api
     *
     * @var string
     */
    public $api;

    /**
     * 组件样式
     *
     * @var array
     */
    public $style = ['width' => 200];

    /**
     * 设置组件属性
     *
     * @param  array $options
     * @return $this
     */
    public function options($options)
    {
        $data = [];
        foreach ($options as $key => $value) {
            $option['label'] = $value;
            $option['value'] = $key;
            $data[] = $option;
        }
        $this->options = $data;

        return $this;
    }
    
    /**
     * 设置mode
     *
     * @param  string $mode
     * @return $this
     */
    public function mode($mode)
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * 设置api接口
     *
     * @param  string $api
     * @return $this
     */
    public function api($api)
    {
        $this->api = $api;
        return $this;
    }


    /**
     * 可以点击清除图标删除内容
     * 
     * @param  string $allowClear
     * @return $this
     */
    public function allowClear($allowClear = true)
    {
        $allowClear ? $this->allowClear = true : $this->allowClear = false;
        return $this;
    }

    /**
     * 控件占位符
     *
     * @param  string $placeholder
     * @return $this
     */
    public function placeholder($placeholder = '')
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * 控件大小。注：标准表单内的输入框大小限制为 large。可选 large default small
     * 
     * @param  large|default|small $prefix
     * @return $this
     */
    public function size($size = 'default')
    {
        if(!in_array($size,['large', 'default', 'small'])) {
            throw new Exception("argument must be in 'large', 'default', 'small'!");
        }

        $this->size = $size;
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
            'mode' => $this->mode,
            'options' => $this->options,
            'placeholder' => $this->placeholder,
            'allowClear' => $this->allowClear,
            'size' => $this->size,
            'api' => $this->api
        ], parent::jsonSerialize());
    }
}
