<?php

namespace QuarkCMS\QuarkAdmin\Actions;

use QuarkCMS\QuarkAdmin\Element;
use QuarkCMS\QuarkAdmin\Components\Modal;

class BaseAction extends Element
{
    /**
     * 触发行为跳转链接
     *
     * @var string
     */
    public $link;

    /**
     * 触发行为打开弹窗
     *
     * @var array
     */
    public $modal;

    /**
     * 触发行为对数据模型的操作
     *
     * @var array
     */
    public $model;

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
     * 初始化容器
     *
     * @param  void
     * @return void
     */
    public function __construct()
    {
        $this->model = new Model;
        return $this;
    }

    /**
     * 设置跳转链接
     *
     * @param  string  $link
     * @return $this
     */
    public function link($link = null)
    {
        $this->link = $link;
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
     *  触发行为对数据模型的操作
     *
     * @param  void
     * @return model
     */
    public function model()
    {
        return $this->model;
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
}
