<?php

namespace QuarkCMS\QuarkAdmin\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;

class DropdownStyleAction extends Action
{
    public  $label,
            $name,
            $icon;
    
    public $methods = [];

    function __construct($name,$label = '',$prefix = '') {
        $this->name = $prefix.'|'.$name;

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
}
