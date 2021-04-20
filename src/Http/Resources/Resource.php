<?php

namespace QuarkCMS\QuarkAdmin\Http\Resources;

class Resource
{
    /**
     * 页面标题
     *
     * @var string
     */
    protected $title;

    /**
     * 渲染页面
     *
     * @param  void
     * @return void
     */
    public static function view()
    {
        $self = new static;
        
        return $self->body();
    }
}
