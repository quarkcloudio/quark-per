<?php

namespace QuarkCloudIO\Quark\Component\Traits;

trait Button
{
    /**
     * 行为标题
     *
     * @var string
     */
    public $label;

    /**
     * 将按钮宽度调整为其父宽度的选项
     *
     * @var bool
     */
    public $block = false;

    /**
     * 设置危险按钮
     *
     * @var bool
     */
    public $danger = false;

    /**
     * 按钮失效状态
     *
     * @var bool
     */
    public $disabled = false;

    /**
     * 幽灵属性，使按钮背景透明
     *
     * @var bool
     */
    public $ghost = false;

    /**
     * 设置按钮的图标组件
     *
     * @var string
     */
    public $icon = null;

    /**
     * 设置按钮形状，可选值为 circle、 round 或者不设
     *
     * @var string
     */
    public $shape = null;

    /**
     * 设置按钮大小,large | middle | small | default
     *
     * @var string
     */
    public $size = 'default';

    /**
     * 设置按钮类型,primary | ghost | dashed | link | text | default
     *
     * @var string
     */
    public $type = 'default';

    /**
     * 将按钮宽度调整为其父宽度的选项
     *
     * @param  bool  $block
     * @return $this
     */
    public function block($block = true)
    {
        $this->block = $block;

        return $this;
    }

    /**
     * 设置危险按钮
     *
     * @param  bool  $danger
     * @return $this
     */
    public function danger($danger = true)
    {
        $this->danger = $danger;

        return $this;
    }

    /**
     * 按钮失效状态
     *
     * @param  bool  $disabled
     * @return $this
     */
    public function disabled($disabled = true)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * 幽灵属性，使按钮背景透明
     *
     * @param  bool  $ghost
     * @return $this
     */
    public function ghost($ghost = true)
    {
        $this->ghost = $ghost;

        return $this;
    }

    /**
     * 设置按钮图标
     *
     * @param  string  $icon
     * @return $this
     */
    public function icon($icon = null)
    {
        $this->icon = 'icon-'.$icon;

        return $this;
    }

    /**
     * 设置按钮形状，可选值为 circle、 round 或者不设
     *
     * @param  string  $shape
     * @return $this
     */
    public function shape($shape)
    {
        if(!in_array($shape,['circle', 'round'])) {
            throw new \Exception("Argument must be in 'circle', 'round'!");
        }

        $this->shape = $shape;

        return $this;
    }

    /**
     * 设置按钮类型，primary | ghost | dashed | link | text | default
     *
     * @param  string  $type
     * @param  bool  $danger
     * @return $this
     */
    public function type($type = 'default',$danger = false)
    {
        if(!in_array($type,['primary', 'ghost', 'dashed', 'link', 'text', 'default'])) {
            throw new \Exception("Argument must be in 'primary', 'ghost', 'dashed', 'link', 'text', 'default'!");
        }

        $this->type = $type;
        $this->danger = $danger;

        return $this;
    }

    /**
     * 设置按钮大小，large | middle | small | default
     *
     * @param  string  $type
     * @param  bool  $danger
     * @return $this
     */
    public function size($size = 'default')
    {
        if(!in_array($size,['large', 'middle', 'small', 'default'])) {
            throw new \Exception("Argument must be in 'primary', 'large', 'middle', 'small', 'default'!");
        }

        $this->size = $size;

        return $this;
    }
}