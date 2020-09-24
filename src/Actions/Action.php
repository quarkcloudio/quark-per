<?php

namespace QuarkCMS\QuarkAdmin\Actions;

use QuarkCMS\QuarkAdmin\Element;
use QuarkCMS\QuarkAdmin\Actions\Modal;

class Action extends Element
{
    public  $url,
            $modal,
            $model,
            $confirm,
            $popconfirm,
            $action;
    
    public $methods = [];

    public function link($url = null)
    {
        $this->url = $url;
        return $this;
    }

    public function withModal($title, $callback = null)
    {
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

    public function model()
    {
        $this->model = new Model;
        return $this->model;
    }

    public function setAction($action)
    {
        $this->action = $action;
        return $this;
    }
}
