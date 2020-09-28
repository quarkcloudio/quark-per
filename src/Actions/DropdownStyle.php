<?php

namespace QuarkCMS\QuarkAdmin\Actions;

use QuarkCMS\QuarkAdmin\Element;
use QuarkCMS\QuarkAdmin\Components\Traits\Dropdown;
use QuarkCMS\QuarkAdmin\Action;

class DropdownStyle extends Element
{
    use Dropdown;

    /**
     * 初始化
     *
     * @param  string  $name
     * @return void
     */
    function __construct($name) {
        $this->component = 'dropdownStyle';
        $this->name = $name;
    }

    /**
     * 下拉菜单属性
     *
     * @param  Closure  $callback
     * @return $this
     */
    public function overlay($callback = null)
    {
        $this->overlay[] = $callback(new Action);
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
