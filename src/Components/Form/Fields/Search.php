<?php

namespace QuarkCMS\QuarkAdmin\Components\Form\Fields;

use QuarkCMS\QuarkAdmin\Components\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class Search extends Item
{
    /**
     * mode
     *
     * @var string
     */
    public $mode;

    /**
     * 与 select 相同，根据 options 生成子节点，推荐使用。
     *
     * @var array
     */
    public $options;

    /**
     * api
     *
     * @var string
     */
    public $api;

    /**
     * 初始化组件
     *
     * @param  string  $name
     * @param  string  $label
     * @return void
     */
    public function __construct($name,$label = '') {
        $this->component = 'search';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }
    }

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
        $this->defaultValue = [];
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
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge([
            'mode' => $this->mode,
            'options' => $this->options,
            'api' => $this->api
        ], parent::jsonSerialize());
    }
}
