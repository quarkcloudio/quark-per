<?php

namespace QuarkCMS\QuarkAdmin\Table\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use QuarkCMS\QuarkAdmin\Table\Actions\Modal;
use Exception;

class Option
{
    public  $label,
            $name,
            $model,
            $action,
            $confirm;
    
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

    public function model($callback = null) {
        $this->model = new Model;
        $callback($this->model);
        return $this;
    }

    public function withConfirm($title = null, $content = '')
    {
        $this->confirm['title'] = $title;
        $this->confirm['content'] = $content;
        return $this;
    }

    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }
}
