<?php

namespace QuarkCloudIO\Quark\Component\Dropdown;

use QuarkCloudIO\Quark\Component\Element;
use QuarkCloudIO\Quark\Component\Traits\Button;

class Dropdown extends Element
{
    use Button;

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
     * 关闭后是否销毁 Dropdown
     *
     * @var bool
     */
    public $destroyPopupOnHide = false;

    /**
     * 菜单
     *
     * @var array
     */
    public $overlay = [];

    /**
     * 下拉根元素的类名称
     *
     * @var string
     */
    public $overlayClassName = null;

    /**
     * 下拉根元素的样式
     *
     * @var array
     */
    public $overlayStyle = [];

    /**
     * 菜单弹出位置：bottomLeft bottomCenter bottomRight topLeft topCenter topRight
     *
     * @var string
     */
    public $placement = 'bottomLeft';

    /**
     * 触发下拉的行为, 移动端不支持 hover,Array<click|hover|contextMenu>
     *
     * @var bool
     */
    public $trigger = ['hover'];

    /**
     * 菜单是否显示
     *
     * @var bool
     */
    public $visible = true;

    /**
     * 初始化容器
     *
     * @param  string  $label
     * @param  array  $menu
     * @return $this
     */
    public function __construct($label = '', $menu = [])
    {
        $this->component = 'dropdown';
        $this->label = $label;
        $this->overlay = $menu;

        return $this;
    }

    /**
     * 下拉框箭头是否显示
     *
     * @param  bool  $arrow
     * @return $this
     */
    public function arrow($arrow)
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
    public function disabled($disabled)
    {
        $this->disabled = $disabled;

        return $this;
    }

    /**
     * 关闭后是否销毁 Dropdown
     *
     * @param  bool  $destroyPopupOnHide
     * @return $this
     */
    public function destroyPopupOnHide($destroyPopupOnHide)
    {
        $this->destroyPopupOnHide = $destroyPopupOnHide;

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
     * 下拉根元素的类名称
     *
     * @param  string  $overlayClassName
     * @return $this
     */
    public function overlayClassName($overlayClassName)
    {
        $this->overlayClassName = $overlayClassName;

        return $this;
    }

    /**
     * 下拉根元素的样式
     *
     * @param  array  $overlayStyle
     * @return $this
     */
    public function overlayStyle($overlayStyle)
    {
        $this->overlayStyle = $overlayStyle;

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
        $this->placement = $placement;

        return $this;
    }

    /**
     * 触发下拉的行为, 移动端不支持 hover,Array<click|hover|contextMenu>
     *
     * @param  array  $trigger
     * @return $this
     */
    public function trigger($trigger)
    {
        $this->trigger = $trigger;

        return $this;
    }

    /**
     * 菜单是否显示
     *
     * @param  bool  $visible
     * @return $this
     */
    public function visible($visible = true)
    {
        $this->visible = $visible;

        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        if(empty($this->key)) {
            $this->key(__CLASS__.$this->label.$this->placement.json_encode($this->overlay), true);
        }

        return array_merge([
            'label' => $this->label,
            'block' => $this->block,
            'danger' => $this->danger,
            'disabled' => $this->disabled,
            'ghost' => $this->ghost,
            'icon' => $this->icon,
            'shape' => $this->shape,
            'size' => $this->size,
            'type' => $this->type,
            'arrow' => $this->arrow,
            'disabled' => $this->disabled,
            'destroyPopupOnHide' => $this->destroyPopupOnHide,
            'overlay' => $this->overlay,
            'overlayClassName' => $this->overlayClassName,
            'overlayStyle' => $this->overlayStyle,
            'placement' => $this->placement,
            'trigger' => $this->trigger,
            //'visible' => $this->visible
        ], parent::jsonSerialize());
    }
}
