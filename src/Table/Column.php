<?php

namespace QuarkCMS\QuarkAdmin\Table;

use Illuminate\Support\Arr;
use Exception;

class Column
{
    public  $align,
            $colSpan,
            $dataIndex,
            $fixed,
            $title,
            $width,
            $render,
            $tag,
            $a,
            $actions;

    function __construct($dataIndex,$title = '') {
        $this->component = 'column';
        $this->dataIndex = $dataIndex;
        $this->title = $title;

        if(empty($title) || !count($title)) {
            $this->title = $dataIndex;
        } else {
            $title = Arr::get($title, 0, ''); //[0];
            $this->title = $title;
        }

        $this->align = 'left';
        $this->fixed = false;
        $this->actions = false;
        $this->tag = false;
        $this->image = false;
        $this->icon = false;
    }

    static function make($dataIndex,$title)
    {
        $self = new self();

        $self->title = $title;
        $self->dataIndex = $dataIndex;

        // 删除空属性
        $self->unsetNullProperty();
        return $self;
    }

    public function align($align)
    {
        $this->align = $align;
        return $this;
    }

    public function colSpan($colSpan)
    {
        $this->colSpan = $colSpan;
        return $this;
    }

    public function fixed($fixed)
    {
        $this->fixed = $fixed;
        return $this;
    }

    public function width($width)
    {
        $this->width = $width;
        return $this;
    }

    public function render($render)
    {
        $this->render = $render;
        return $this;
    }

    public function withTag($color)
    {
        if(strpos($color,"'") == false && strpos($color,'"') == false) {
            $color = "'".$color."'";
        }
        $this->tag = $color;
        return $this;
    }

    public function withA($href,$target='_self')
    {
        if(!(strpos($href,'http') !== false)) {
            $href = '#/'.$href;
        }

        $a['href'] = $href;
        $a['target'] = $target;
        $this->a = $a;
        return $this;
    }

    public function icon()
    {
        $this->icon = true;
        return $this;
    }

    public function image()
    {
        $this->image = true;
        return $this;
    }

    public function actions($actions)
    {
        $this->actions = $actions;
        return $this;
    }

    protected function unsetNullProperty()
    {
        foreach ($this as $key => $value) {
            if(empty($value)) {
                unset($this->$key);
            }
        }
    }
}
