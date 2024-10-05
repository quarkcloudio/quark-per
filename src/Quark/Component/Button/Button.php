<?php

namespace QuarkCloudIO\QuarkAdmin\Components;

use QuarkCloudIO\QuarkAdmin\Element;
use QuarkCloudIO\QuarkAdmin\Components\Traits\Button as BaseButton;

class Button extends Element
{
    use BaseButton;

    /**
     * 初始化
     *
     * @param  string  $name
     * @return void
     */
    public function __construct($name) {
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
        if(empty($this->key)) {
            $this->key(json_encode($this), true);
        }

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
