<?php

namespace QuarkCMS\QuarkAdmin\Components\Table;

use Closure;
use Illuminate\Support\Str;
use QuarkCMS\QuarkAdmin\Element;

/**
 * Class Column.
 *
 */
class Column extends Element
{
    /**
     * 列绑定的字段
     *
     * @var string
     */
    public $name;

    /**
     * 列标题
     *
     * @var string
     */
    public $label;

    /**
     * 列宽
     *
     * @var string|number
     */
    public $width;

    /**
     * using规则
     * 
     * @var array
     */
    public $using;

    /**
     * 列以标签形式展示
     * 
     * @var array
     */
    public $tag;

    /**
     * 列以链接形式展示
     * 
     * @var string|bool
     */
    public $link;

    /**
     * 列以图片形式展示
     * 
     * @var string|bool
     */
    public $image;

    /**
     * 列以二维码形式展示
     * 
     * @var string|bool
     */
    public $qrcode;

    /**
     * 可编辑列
     * 
     * @var array|bool
     */
    public $editable = false;

    /**
     * 可排序列
     * 
     * @var array|bool
     */
    public $sorter = false;

    /**
     * 可筛选列
     * 
     * @var array
     */
    public $filters = null;

    /**
     * 数据展示回调
     *
     * @var
     */
    public $displayCallback = null;

    /**
     * 是否为操作列
     * 
     * @var bool
     */
    public $actions = false;

    /**
     * 数据操作回调
     *
     * @var
     */
    public $actionCallback = null;

    /**
     * 初始化
     *
     * @param  string  $name
     * @param  string  $label
     * @return void
     */
    public function __construct($name, $label)
    {
        $this->component = 'column';
        $this->name = $name;
        $this->label = $label;
    }

    /**
     * 设置列宽
     *
     * @param  string|number  $width
     * @return $this
     */
    public function width($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * 设置本列跳转链接
     *
     * @param  string|bool  $link
     * @return $this
     */
    public function link($link=true)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * 设置本列using规则
     *
     * @param  array  $using
     * @return $this
     */
    public function using($using)
    {
        $this->using = $using;
        return $this;
    }

    /**
     * 设置本列tag显示
     *
     * @param  string  $tag
     * @return $this
     */
    public function tag($tag='default')
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * 设置本列图片显示
     *
     * @param  string  $path
     * @param  string|number  $width
     * @param  string|number  $height
     * @return $this
     */
    public function image($path=null,$width=25,$height=25)
    {
        $image['path'] = $path;
        $image['width'] = $width;
        $image['height'] = $height;
        $this->image = $image;
        return $this;
    }

    /**
     * 设置本列二维码显示
     *
     * @param  string  $content
     * @param  string|number  $width
     * @param  string|number  $height
     * @return $this
     */
    public function qrcode($content=null,$width=150,$height=150)
    {
        $qrcode['content'] = $content;
        $qrcode['width'] = $width;
        $qrcode['height'] = $height;
        $this->qrcode = $qrcode;
        return $this;
    }

    /**
     * 设置为可编辑列
     *
     * @param  string  $name
     * @param  array|bool  $options
     * @param  string  $action
     * @return $this
     */
    public function editable($name='text',$options=false,$action='')
    {
        if(empty($action)) {
            $action = \request()->route()->getName();
            $action = Str::replaceFirst('api/','',$action);
            $action = Str::replaceLast('/index','/action',$action);
        }

        if($name == 'select') {
            $getOptions = [];
            foreach ($options as $key => $value) {
                $option['label'] = $value;
                $option['value'] = $key;
                $getOptions[] = $option;
            }

            $options = $getOptions;
        }

        $editable['name'] = $name;
        $editable['options'] = $options;
        $editable['action'] = $action;
        
        $this->editable = $editable;
        return $this;
    }

    /**
     * 设置为可筛选列
     *
     * @param  array  $filters
     * @return $this
     */
    public function filters($filters = [])
    {
        $getFilters = [];
        foreach ($filters as $key => $value) {
            $filter['text'] = $value;
            $filter['value'] = $key;
            $getFilters[] = $filter;
        }

        $this->filters = $getFilters;
        return $this;
    }

    /**
     * 设置为可排序列
     *
     * @param  bool  $sorter
     * @return $this
     */
    public function sorter($sorter = true)
    {
        $this->sorter = $sorter;
        return $this;
    }

    /**
     * 重置列的数据显示
     *
     * @param  Closure  $callback
     * @return $this
     */
    public function display(Closure $callback)
    {
        $this->displayCallback = $callback;
        return $this;
    }

    /**
     * 设置为数据操作列
     *
     * @param  Closure  $callback
     * @return $this
     */
    public function actions(Closure $callback = null)
    {
        $this->actions = true;
        $this->actionCallback = $callback;
        return $this;
    }

    /**
     * Prepare the element for JSON serialization.
     *
     * @return array
     */
    public function jsonSerialize()
    {
        // 设置组件唯一标识
        $this->key(__CLASS__.$this->label.$this->name);

        return array_merge([
            'title' => $this->label,
            'dataIndex' => $this->name,
            'width' => $this->width,
            'link' => $this->link,
            'image' => $this->image,
            'qrcode' => $this->qrcode,
            'actions' => $this->actions
        ], parent::jsonSerialize());
    }
}
