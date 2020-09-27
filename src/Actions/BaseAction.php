<?php

namespace QuarkCMS\QuarkAdmin\Actions;

use QuarkCMS\QuarkAdmin\Element;
use QuarkCMS\QuarkAdmin\Components\Modal;
use QuarkCMS\QuarkAdmin\Components\Table\Model;
use Illuminate\Support\Str;

class BaseAction extends Element
{
    /**
     * 触发行为跳转链接
     *
     * @var string
     */
    public $href;

    /**
     * 触发行为打开弹窗
     *
     * @var array
     */
    public $modal;

    /**
     * 执行行为前的确认操作
     *
     * @var array
     */
    public $confirm;

    /**
     * 执行行为前的popconfirm形式确认操作
     *
     * @var array
     */
    public $popconfirm;

    /**
     * 执行行为的接口链接
     *
     * @var string
     */
    public $api;

    /**
     * 行为model
     *
     * @var array
     */
    public $model;

    /**
     * 行为methods
     *
     * @var array
     */
    public $methods;

    /**
     * 设置跳转链接
     *
     * @param  string  $href
     * @param  string  $href
     * @return $this
     */
    public function link($href = null, $target = '_self')
    {
        $this->href($href);
        $this->target($target);
        return $this;
    }

    /**
     * 设置编辑的跳转链接
     *
     * @param  string  $target
     * @return $this
     */
    public function editLink($target='_self')
    {
        $action = \request()->route()->getName();
        $action = Str::replaceFirst('api/','',$action);
        $action = Str::replaceLast('/index','/edit',$action);
        $href = '/quark/engine?api='.$action.'&id={id}';
        $this->link($href, $target);
        
        return $this;
    }

    /**
     * 设置打开弹窗
     *
     * @param  string  $title
     * @param  Closure  $callback
     * @return $this
     */
    public function withModal($title, $callback = null)
    {
        $modal = new Modal;
        $modal->title($title);
        $callback($modal);
        $this->modal = $modal->render();
        return $this;
    }

    /**
     * 设置行为前的确认操作
     *
     * @param  string  $title
     * @param  string  $content
     * @return $this
     */
    public function withConfirm($title = null, $content = '')
    {
        $this->confirm['title'] = $title;
        $this->confirm['content'] = $content;
        return $this;
    }

    /**
     * 执行行为前的popconfirm形式确认操作
     *
     * @param  string  $title
     * @return $this
     */
    public function withPopconfirm($title = null)
    {
        $this->popconfirm['title'] = $title;
        return $this;
    }

    /**
     *  执行行为的接口链接
     *
     * @param  string  $api
     * @return $this
     */
    public function api($api)
    {
        $this->api = $api;
        return $this;
    }

    /**
     *  model
     *
     * @param  string  $api
     * @return $this
     */
    public function model()
    {
        $this->model = new Model();
        return $this->model;
    }

    /**
     * @param string $method
     * @param array  $arguments
     *
     * @return $this
     */
    public function __call($method, $arguments)
    {
        $this->methods[] = [
            'method'    => $method,
            'arguments' => $arguments,
        ];

        return $this;
    }
}
