<?php

namespace QuarkCMS\QuarkAdmin\Components\Table;

use Closure;
use Illuminate\Support\Str;
use QuarkCMS\QuarkAdmin\Element;
use QuarkCMS\QuarkAdmin\Action;

class ToolBar extends Element
{
    /**
     * 标题
     *
     * @var string|array
     */
    public $title;

    /**
     * 子标题
     *
     * @var string|array
     */
    public $subTitle = null;

    /**
     * 描述
     *
     * @var string|array
     */
    public $description = null;

    /**
     * 查询区
     *
     * @var array
     */
    public $search = null;

    /**
     * 操作区
     *
     * @var object
     */
    public $action = null;

    /**
     * 设置区
     *
     * @var array
     */
    public $settings = null;

    /**
     * 过滤区，通常配合 LightFilter 使用
     *
     * @var array
     */
    public $filter = null;

    /**
     * 是否多行展示
     *
     * @var bool
     */
    public $multipleLine = false;

    /**
     * 菜单配置
     *
     * @var array
     */
    public $menu = null;

    /**
     * 标签页配置，仅当 multipleLine 为 true 时有效
     *
     * @var array
     */
    public $tabs = null;

    /**
     * 初始化
     *
     * @param  string  $title
     * @param  string  $subTitle
     * @return void
     */
    public function __construct($title = null, $subTitle = null)
    {
        $this->component = 'toolbar';
        $this->action = new Action;
        $this->title = $this->title;
        $this->subTitle = $this->subTitle;
        
        return $this;
    }

    /**
     * 标题
     *
     * @param  string  $title
     * @return $this
     */
    public function title($title)
    {
        $this->title = $title;
        return $this;
    }

    /**
     * 子标题
     *
     * @param  string  $subTitle
     * @return $this
     */
    public function subTitle($subTitle)
    {
        $this->subTitle = $subTitle;
        return $this;
    }

    /**
     * 描述
     *
     * @param  string  $description
     * @return $this
     */
    public function description($description)
    {
        $this->description = $description;
        return $this;
    }

    /**
     * 查询区
     *
     * @param  array  $search
     * @return $this
     */
    public function search($search)
    {
        $this->search = $search;
        return $this;
    }

    /**
     * 操作区
     *
     * @param  Closure  $callback
     * @return $this
     */
    public function actions(Closure $callback = null)
    {
        $callback($this->action);

        return $this;
    }

    /**
     * 设置区
     *
     * @param  array  $settings
     * @return $this
     */
    public function settings($settings)
    {
        $this->settings = $settings;
        return $this;
    }
    
    /**
     * 过滤区，通常配合 LightFilter 使用
     *
     * @param  array  $filter
     * @return $this
     */
    public function filter($filter = true)
    {
        $this->filter = $filter;
        return $this;
    }

    /**
     * 是否多行展示
     *
     * @param  array  $multipleLine
     * @return $this
     */
    public function multipleLine($multipleLine = true)
    {
        $this->multipleLine = $multipleLine;
        return $this;
    }

    /**
     * 菜单配置
     *
     * @param  array  $menu
     * @return $this
     */
    public function menu($menu)
    {
        $this->menu = $menu;
        return $this;
    }

    /**
     * 标签页配置，仅当 multipleLine 为 true 时有效
     *
     * @param  array  $tabs
     * @return $this
     */
    public function tabs($tabs)
    {
        $this->tabs = $tabs;
        return $this;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key();

        return array_merge([
            'title' => $this->title,
            'subTitle' => $this->subTitle,
            'description' => $this->description,
            'multipleLine' => $this->multipleLine
        ], parent::jsonSerialize());
    }
}