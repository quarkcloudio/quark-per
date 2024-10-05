<?php

namespace QuarkCloudIO\Quark\Component\Form\Fields;

use QuarkCloudIO\Quark\Component\Form\Fields\Item;
use Exception;

class Text extends Item
{
    /**
     * 组件类型
     *
     * @var string
     */
    public $component = 'textField';

    /**
     * 带标签的 input，设置后置标签。例如：'http://'
     *
     * @var string
     */
    public $addonAfter = null;

    /**
     * 带标签的 input，设置前置标签。例如：'.com'
     *
     * @var string
     */
    public $addonBefore = null;

    /**
     * 最大长度
     *
     * @var bumber
     */
    public $maxLength = null;

    /**
     * 带有前缀图标的 input
     *
     * @var string
     */
    public $prefix = null;

    /**
     * 控件大小。注：标准表单内的输入框大小限制为 large。可选 large default small
     *
     * @var string
     */
    public $size = null;

    /**
     * 带有后缀图标的 input
     *
     * @var string
     */
    public $suffix = null;

    /**
     * 控件占位符
     *
     * @var string
     */
    public $placeholder = null;

    /**
     * 可以点击清除图标删除内容
     *
     * @var bool
     */
    public $allowClear = false;

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
    public function placeholder($placeholder = '')
    {
        $this->placeholder = $placeholder;
        return $this;
    }

    /**
     * 带标签的 input，设置后置标签。例如：'http://'
     *
     * @param  string $addonAfter
     * @return $this
     */
    public function addonAfter($addonAfter = '')
    {
        $this->addonAfter = $addonAfter;
        return $this;
    }

    /**
     * 带标签的 input，设置前置标签。例如：'.com'
     *
     * @param  string $addonBefore
     * @return $this
     */
    public function addonBefore($addonBefore = '')
    {
        $this->addonBefore = $addonBefore;
        return $this;
    }

    /**
     * 最大长度
     * 
     * @param  number $length
     * @return $this
     */
    public function maxLength($length = 0)
    {
        if(!is_numeric($length)) {
            throw new Exception('argument must be of numeric type!');
        }

        $this->maxLength = $length;
        return $this;
    }

    /**
     * 带有前缀图标的 input
     * 
     * @param  string $prefix
     * @return $this
     */
    public function prefix($prefix = '')
    {
        $this->prefix = $prefix;
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
     * 带有后缀图标的 input
     * 
     * @param  string $suffix
     * @return $this
     */
    public function suffix($suffix = '')
    {
        $this->suffix = $suffix;
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
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        return array_merge([
            'placeholder' => $this->placeholder ?? '请输入' . $this->label,
            'maxLength' => $this->maxLength,
            'addonAfter' => $this->addonAfter,
            'allowClear' => $this->allowClear,
            'size' => $this->size,
            'prefix' => $this->prefix,
            'suffix' => $this->suffix
        ], parent::jsonSerialize());
    }
}
