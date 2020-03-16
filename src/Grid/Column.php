<?php

namespace QuarkCMS\QuarkAdmin\Grid;

use Closure;
use Illuminate\Support\Str;

/**
 * Class Column.
 *
 */
class Column
{
    /**
     * Name of column.
     *
     * @var string
     */
    public $name;

    /**
     * Label of column.
     *
     * @var string
     */
    public $label;

    /**
     * @var width
     */
    public $width;

    /**
     * @var using
     */
    public $using;

    /**
     * @var tag
     */
    public $tag;

    /**
     * @var link
     */
    public $link;

    /**
     * @var image
     */
    public $image;

    /**
     * @var qrcode
     */
    public $qrcode;

    /**
     * @var editable
     */
    public $editable = false;

    /**
     * @var sorter
     */
    public $sorter = false;

    /**
     * @var filters
     */
    public $filters = null;

    /**
     * @param string $name
     * @param string $label
     */
    public function __construct($name, $label)
    {
        $this->name = $name;
        $this->label = $label;
    }

    /**
     * width.
     *
     * @return mixed
     */
    public function width($width)
    {
        $this->width = $width;
        return $this;
    }

    /**
     * link.
     *
     * @return mixed
     */
    public function link($link=true)
    {
        $this->link = $link;
        return $this;
    }

    /**
     * using.
     *
     * @return mixed
     */
    public function using($using)
    {
        $this->using = $using;
        return $this;
    }

    /**
     * tag.
     *
     * @return mixed
     */
    public function tag($tag='default')
    {
        $this->tag = $tag;
        return $this;
    }

    /**
     * image.
     *
     * @return mixed
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
     * qrcode.
     *
     * @return mixed
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
     * editable.
     *
     * @return mixed
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

    public function sorter($sorter = true)
    {
        $this->sorter = $sorter;
        return $this;
    }
}
