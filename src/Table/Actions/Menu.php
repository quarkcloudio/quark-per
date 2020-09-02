<?php

namespace QuarkCMS\QuarkAdmin\Grid\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use QuarkCMS\QuarkAdmin\Grid\Actions\Modal;
use Exception;

class Menu
{
    public  $label,
            $name,
            $icon,
            $url,
            $modal,
            $model,
            $confirm,
            $action;
    
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

    public function link($url = null)
    {
        $this->url = $url;
        return $this;
    }

    public function withModal($title, $callback = null) {
        $modal = new Modal;
        $modal->title($title);
        $callback($modal);
        $this->modal = $modal->render();
        return $this;
    }

    public function withConfirm($title = null, $content = '')
    {
        $this->confirm['title'] = $title;
        $this->confirm['content'] = $content;
        return $this;
    }

    public function model($callback = null) {
        $this->model = new Model;
        $callback($this->model);
        return $this;
    }

    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }
}
