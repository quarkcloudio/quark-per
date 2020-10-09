<?php

namespace QuarkCMS\QuarkAdmin\Components;

use QuarkCMS\QuarkAdmin\Element;
use QuarkCMS\QuarkAdmin\Components\Traits\Button as BaseButton;

class Button extends Element
{
    use BaseButton;

    /**
     * 初始化
     *
     * @param  string  $name
     * @return void
     */
    function __construct($name) {
        $this->component = 'button';
        $this->name = $name;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key(json_encode($this));

        return array_merge([
            'name' => $this->name,
            'block' => $this->block,
            'danger' => $this->danger,
            'disabled' => $this->disabled,
            'ghost' => $this->ghost,
            'href' => $this->href,
            'icon' => $this->icon,
            'shape' => $this->shape,
            'size' => $this->size,
            'target' => $this->target,
            'type' => $this->type,
            'api' => $this->api
        ], parent::jsonSerialize());
    }
}
