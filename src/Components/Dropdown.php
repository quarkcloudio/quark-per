<?php

namespace QuarkCMS\QuarkAdmin\Components;

use QuarkCMS\QuarkAdmin\Element;
use QuarkCMS\QuarkAdmin\Components\Traits\Dropdown as BaseDropdown;

class Dropdown extends Element
{
    use BaseDropdown;

    /**
     * 初始化
     *
     * @param  string  $name
     * @return void
     */
    public function __construct($name) {
        $this->component = 'dropdown';
        $this->name = $name;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key(json_encode($this->name));

        return array_merge([
            'name' => $this->name,
            'mode' => $this->mode,
            'arrow' => $this->arrow,
            'disabled' => $this->disabled,
            'overlay' => $this->overlay,
            'placement' => $this->placement,
            'trigger' => $this->trigger,
        ], parent::jsonSerialize());
    }
}
