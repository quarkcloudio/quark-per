<?php

namespace QuarkCMS\QuarkAdmin\Components\Form\Fields;

use QuarkCMS\QuarkAdmin\Components\Form\Item;
use Illuminate\Support\Arr;
use Exception;

class Checkbox extends Item
{
    /**
     * 与 select 相同，根据 options 生成子节点，推荐使用。
     *
     * @var array
     */
    public $options;

    /**
     * 配置 checkbox 的样子，支持垂直vertical 和 horizontal
     *
     * @var string
     */
    public $layout = 'vertical';
    
    /**
     * 初始化多选框组件
     *
     * @param  string  $name
     * @param  string  $label
     * @return void
     */
    public function __construct($name,$label = '')
    {
        $this->component = 'checkbox';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }
    }

    /**
     * 设置多选框属性
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
     * 配置 checkbox 的样子，支持垂直vertical 和 horizontal
     *
     * @param  string $layout
     * @return $this
     */
    public function layout($layout)
    {
        if(!in_array($layout,['vertical', 'horizontal'])) {
            throw new Exception("argument must be in 'vertical', 'horizontal'!");
        }

        $this->layout = $layout;
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
            'layout' => $this->layout,
            'options' => $this->options,
        ], parent::jsonSerialize());
    }
}
