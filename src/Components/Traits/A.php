<?php

namespace QuarkCMS\QuarkAdmin\Components\Traits;

use Closure;

trait A
{
    /**
     * a标签名称
     *
     * @var string
     */
    public $name;

    /**
     * 点击跳转的地址，指定此属性 button 的行为和 a 链接一致
     *
     * @var string
     */
    public $href = null;

    /**
     * 点击按钮时的回调
     *
     * @var array
     */
    public $onClick = null;

    /**
     * 相当于 a 链接的 target 属性，href 存在时生效
     *
     * @var string
     */
    public $target = null;

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
}
