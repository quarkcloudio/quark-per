<?php

namespace QuarkCloudIO\Quark\Component\Form\Fields;

use QuarkCloudIO\Quark\Component\Form\Fields\Item;
use Exception;

class Number extends Item
{
    /**
     * 组件类型
     *
     * @var string
     */
    public $component = 'inputNumberField';

    /**
     * 控件大小。注：标准表单内的输入框大小限制为 large。可选 large default small
     *
     * @var string
     */
    public $size = null;

    /**
     * 控件占位符
     *
     * @var string
     */
    public $placeholder = null;

    /**
     * 最小值
     *
     * @var number
     */
    public $min = -100000000;

    /**
     * 最大值
     *
     * @var number
     */
    public $max = 100000000;

    /**
     * 每次改变步数，可以为小数
     *
     * @var number | string
     */
    public $step = 1;

    /**
     * 数值精度
     *
     * @var number
     */
    public $precision = 0;

    /**
     * 组件样式
     *
     * @var array
     */
    public $style = ['width' => 200];
    
    /**
     * 控件占位符
     *
     * @param  string $placeholder
     * @return $this
     */
    public function placeholder($placeholder)
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
     * 最小值
     *
     * @param  number $min
     * @return $this
     */
    public function min($min)
    {
        $this->min = $min;

        return $this;
    }

    /**
     * 最大值
     *
     * @param  number $max
     * @return $this
     */
    public function max($max)
    {
        $this->max = $max;

        return $this;
    }

    /**
     * 每次改变步数，可以为小数
     *
     * @param  string $placeholder
     * @return $this
     */
    public function step($step)
    {
        $this->step = $step;

        return $this;
    }

    /**
     * 数值精度
     *
     * @param  number $precision
     * @return $this
     */
    public function precision($precision)
    {
        $this->precision = $precision;

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
            'placeholder' => $this->placeholder ?? '请输入' . $this->label,
            'size' => $this->size,
            'min' => $this->min,
            'max' => $this->max,
            'step' => $this->step,
            'precision' => $this->precision,
        ], parent::jsonSerialize());
    }
}
