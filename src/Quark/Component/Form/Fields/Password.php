<?php

namespace QuarkCloudIO\Quark\Component\Form\Fields;

class Password extends Text
{
    /**
     * 组件类型
     *
     * @var string
     */
    public $component = 'passwordField';

    /**
     * 组件样式
     *
     * @var array
     */
    public $style = ['width' => 200];

    /**
     * 是否显示切换按钮
     *
     * @var bool
     */
    public $visibilityToggle = true;

    /**
     * 是否显示切换按钮
     *
     * @param  bool $visibilityToggle
     * @return $this
     */
    public function visibilityToggle($visibilityToggle)
    {
        $this->visibilityToggle = $visibilityToggle;

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
            'visibilityToggle' => $this->visibilityToggle
        ], parent::jsonSerialize());
    }
}
