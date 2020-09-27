<?php

namespace QuarkCMS\QuarkAdmin\Actions;

use QuarkCMS\QuarkAdmin\Element;
use QuarkCMS\QuarkAdmin\Components\Traits\Dropdown;
use QuarkCMS\QuarkAdmin\Components\Dropdown\Option;

class DropdownStyleAction extends Element
{
    use Dropdown;

    /**
     * 初始化
     *
     * @param  string  $name
     * @return void
     */
    function __construct($name) {
        $this->component = 'dropdownStyleAction';
        $this->name = $name;
    }

    /**
     * 下拉菜单属性
     *
     * @param  Closure  $callback
     * @return $this
     */
    public function options($callback = null)
    {
        $option = new Option;
        $this->overlay = $callback($option);
        return $this;
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key(__CLASS__.json_encode($this->name));

        return array_merge([
            'name' => $this->name,
            'overlay' => $this->overlay
        ], parent::jsonSerialize());
    }
}
