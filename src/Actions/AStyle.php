<?php

namespace QuarkCMS\QuarkAdmin\Actions;

use Illuminate\Support\Str;
use QuarkCMS\QuarkAdmin\Components\Traits\A;

class AStyle extends BaseAction
{
    use A;

    /**
     * 初始化
     *
     * @param  string  $name
     * @return void
     */
    function __construct($name) {
        $this->name = $name;
        $this->style = ['padding'=>'0px 5px'];
        $this->component = 'aStyle';
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key(__CLASS__.json_encode($this->name));

        $api = \request()->route()->getName();
        $api = Str::replaceFirst('api/','',$api);
        $api = Str::replaceLast('/index','/action',$api);

        $this->api = $api.'?id={id}&key='.$this->key;

        return array_merge([
            'name' => $this->name,
            'href' => $this->href,
            'target' => $this->target,
            'api' => $this->api,
            'confirm' => $this->confirm,
            'popconfirm' => $this->popconfirm
        ], parent::jsonSerialize());
    }
}
