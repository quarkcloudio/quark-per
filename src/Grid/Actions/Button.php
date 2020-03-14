<?php

namespace QuarkCMS\QuarkAdmin\Grid\Actions;

use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use QuarkCMS\QuarkAdmin\Grid\Actions\Modal;
use Exception;

class Button
{
    public  $label,
            $name,
            $type,
            $danger,
            $size,
            $icon,
            $url,
            $modal,
            $model,
            $confirm,
            $popconfirm,
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

        if($name == 'create') {
            $this->icon = 'plusCircle';
            $this->type = 'primary';
        }

        if($name == 'refresh') {
            $this->icon = 'redo';
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

    public function icon($icon = null)
    {
        $this->icon = $icon;
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

    public function withPopconfirm($title = null)
    {
        $this->popconfirm['title'] = $title;
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
