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
    function __construct($name) {
        $this->component = 'dropdown';
        $this->name = $name;
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key(json_encode($this->name));

        return array_merge([
            'name' => $this->name,
            'arrow' => $this->arrow,
            'disabled' => $this->disabled,
            'overlay' => $this->overlay,
            'placement' => $this->placement,
            'trigger' => $this->trigger,
        ], parent::jsonSerialize());
    }
}
