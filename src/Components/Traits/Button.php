<?php

namespace QuarkCMS\QuarkAdmin\Components\Traits;

use Closure;
use QuarkCMS\QuarkAdmin\Components\Traits\A;

trait Button
{
    use A;

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
     * 点击跳转的地址，指定此属性 button 的行为和 a 链接一致
     *
     * @param  string  $href
     * @return $this
     */
    public function href($href)
    {
        $this->href = $href;
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
     * 点击按钮时的回调
     *
     * @param  Closure  $callback
     * @return $this
     */
    public function onClick(Closure $callback)
    {
        $this->onClick = $callback;
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
     * 相当于 a 链接的 target 属性，href 存在时生效
     *
     * @param  string  $target
     * @return $this
     */
    public function target($target)
    {
        if(!in_array($target,['_blank', '_self', '_parent', '_top'])) {
            throw new \Exception("Argument must be in '_blank', '_self', '_parent', '_top'!");
        }

        $this->target = $target;
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
