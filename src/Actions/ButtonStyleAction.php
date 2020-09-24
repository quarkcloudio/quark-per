<?php

namespace QuarkCMS\QuarkAdmin\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class ButtonStyleAction extends Action
{
    public  $label,
            $name,
            $type,
            $danger,
            $size,
            $icon;
    
    public $methods = [];

    function __construct($name,$label = '') {
        $this->component = 'buttonStyleAction';
        $this->name = $name;

        if(empty($label) || !count($label)) {
            $this->label = $name;
        } else {
            $label = Arr::get($label, 0, ''); //[0];
            $this->label = $label;
        }

        $action = \request()->route()->getName();
        $action = Str::replaceFirst('api/','',$action);
        $action = Str::replaceLast('/index','/action',$action);

        $this->action = $action;
    }

    public function icon($icon = null)
    {
        $this->icon = 'icon-'.$icon;
        return $this;
    }

    public function type($type = 'default',$danger = false)
    {
        $this->type = $type;
        $this->danger = $danger;
        return $this;
    }

    public function size($size = 'default')
    {
        $this->size = $size;
        return $this;
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
            'label' => $this->label
        ], parent::jsonSerialize());
    }
}
