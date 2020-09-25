<?php

namespace QuarkCMS\QuarkAdmin\Components;

use QuarkCMS\QuarkAdmin\Element;
use QuarkCMS\QuarkAdmin\Components\Traits\A as BaseA;

class A extends Element
{
    use BaseA;

    /**
     * 初始化
     *
     * @param  string  $name
     * @return void
     */
    function __construct($name) {
        $this->style = ['padding'=>'5px 0px'];
        $this->component = 'a';
        $this->name = $name;
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key(json_encode($this));

        return array_merge([
            'name' => $this->name,
            'href' => $this->href,
            'target' => $this->target,
            'api' => $this->api
        ], parent::jsonSerialize());
    }
}
