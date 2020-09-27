<?php

namespace QuarkCMS\QuarkAdmin\Components\Dropdown;

use QuarkCMS\QuarkAdmin\Element;
use QuarkCMS\QuarkAdmin\Components\Traits\A as BaseA;
use QuarkCMS\QuarkAdmin\Actions\BaseAction;
use Illuminate\Support\Str;

class Option extends BaseAction
{
    use BaseA;

    /**
     * 初始化
     *
     * @param  string  $name
     * @return void
     */
    function __construct($name = null) {
        $this->component = 'option';
        $this->name = $name;
    }

    /**
     * 设置name
     *
     * @param  string  $name
     * @return $this
     */
    public function name($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key(json_encode($this->name));

        $api = \request()->route()->getName();
        $api = Str::replaceFirst('api/','',$api);
        $api = Str::replaceLast('/index','/action',$api);

        $this->api = $api.'?id={id}&key='.$this->key;

        return array_merge([
            'name' => $this->name,
            'href' => $this->href,
            'target' => $this->target,
            'api' => $this->api
        ], parent::jsonSerialize());
    }
}
