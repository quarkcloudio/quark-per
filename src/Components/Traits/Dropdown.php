<?php

namespace QuarkCMS\QuarkAdmin\Components\Traits;

use Closure;

trait Dropdown
{
    /**
     * 下拉菜单名称
     *
     * @var string
     */
    public $name;

    /**
     * 显示模式
     *
     * @var string
     */
    public $mode = 'a';

    /**
     * 下拉框箭头是否显示
     *
     * @var bool
     */
    public $arrow = false;

    /**
     * 菜单是否禁用
     *
     * @var bool
     */
    public $disabled = false;

    /**
     * 菜单
     *
     * @var array
     */
    public $overlay = null;

    /**
     * 菜单弹出位置：bottomLeft bottomCenter bottomRight topLeft topCenter topRight
     *
     * @var string
     */
    public $placement = 'bottomLeft';

    /**
     * 触发下拉的行为, 移动端不支持 hover
     *
     * @var Array<click|hover|contextMenu>
     */
    public $trigger = ['click'];

    /**
     * 下拉框显示模式,a|button
     *
     * @param  string  $mode
     * @return $this
     */
    public function mode($mode)
    {
        $this->mode = $mode;
        return $this;
    }

    /**
     * 下拉框箭头是否显示
     *
     * @param  bool  $arrow
     * @return $this
     */
    public function arrow($arrow = true)
    {
        $this->arrow = $arrow;
        return $this;
    }

    /**
     * 菜单是否禁用
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
     * 菜单
     *
     * @param  array  $overlay
     * @return $this
     */
    public function overlay($overlay)
    {
        $this->overlay = $overlay;
        return $this;
    }

    /**
     * 菜单弹出位置：bottomLeft bottomCenter bottomRight topLeft topCenter topRight
     *
     * @param  string  $placement
     * @return $this
     */
    public function placement($placement)
    {
        if(!in_array($placement,['bottomLeft', 'bottomCenter', 'bottomRight', 'topLeft', 'topCenter', 'topRight'])) {
            throw new \Exception("Argument must be in 'bottomLeft', 'bottomCenter', 'bottomRight', 'topLeft', 'topCenter', 'topRight'!");
        }

        $this->placement = $placement;
        return $this;
    }

    /**
     * 触发下拉的行为, 移动端不支持 hover
     *
     * @param  array  $trigger
     * @return $this
     */
    public function trigger($trigger)
    {
        $this->trigger = $trigger;
        return $this;
    }
}
