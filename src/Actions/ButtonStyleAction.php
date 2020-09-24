<?php

namespace QuarkCMS\QuarkAdmin\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use QuarkCMS\QuarkAdmin\Components\Traits\Button;

class ButtonStyleAction extends BaseAction
{
    use Button;

    function __construct($name) {
        $this->component = 'buttonStyleAction';
        $this->name = $name;

        $api = \request()->route()->getName();
        $api = Str::replaceFirst('api/','',$api);
        $api = Str::replaceLast('/index','/action',$api);
        $this->api = $api;
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
            'name' => $this->name
        ], parent::jsonSerialize());
    }
}
