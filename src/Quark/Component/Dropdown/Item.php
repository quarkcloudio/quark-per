<?php

namespace QuarkCloudIO\QuarkAdmin\Actions\DropdownStyle;

use QuarkCloudIO\QuarkAdmin\Components\Traits\A as BaseA;
use QuarkCloudIO\QuarkAdmin\Actions\BaseAction;
use Illuminate\Support\Str;

class Item extends BaseAction
{
    use BaseA;

    /**
     * 初始化
     *
     * @param  string  $name
     * @return void
     */
    function __construct($name = null) {
        $this->component = 'itemStyle';
        $this->name = $name;
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        if(empty($this->key)) {
            $this->key(__CLASS__.json_encode($this->name), true);
        }

        if(empty($this->api)) {
            $api = \request()->route()->getName();
            $api = Str::replaceFirst('api/','',$api);
            $api = Str::replaceLast('/index','/action',$api);
            $this->api = $api.'?id={id}&key='.$this->key;
        }

        return array_merge([
            'name' => $this->name,
            'href' => $this->href,
            'target' => $this->target,
            'api' => $this->api,
            'confirm' => $this->confirm,
            'popconfirm' => $this->popconfirm,
            'modal' => $this->modal,
            'drawer' => $this->drawer
        ], parent::jsonSerialize());
    }
}
