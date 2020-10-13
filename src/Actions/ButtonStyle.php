<?php

namespace QuarkCMS\QuarkAdmin\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use QuarkCMS\QuarkAdmin\Components\Traits\Button;

class ButtonStyle extends BaseAction
{
    use Button;

    /**
     * 初始化
     *
     * @param  string  $name
     * @return void
     */
    function __construct($name) {
        $this->component = 'buttonStyle';
        $this->name = $name;
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key(__CLASS__.json_encode($this->name));

        if(empty($this->api)) {
            $api = \request()->route()->getName();
            $api = Str::replaceFirst('api/','',$api);
            $api = Str::replaceLast('/index','/action',$api);
            $this->api = $api.'?id={id}&key='.$this->key;
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
            'api' => $this->api,
            'confirm' => $this->confirm,
            'popconfirm' => $this->popconfirm,
            'modal' => $this->modal
        ], parent::jsonSerialize());
    }
}
