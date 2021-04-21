<?php

namespace QuarkCMS\QuarkAdmin\Http\Resources;

class Resource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public $title;

    /**
     * 页面数据
     *
     * @var string|number|array
     */
    public $data;

    /**
     * 渲染页面
     *
     * @param  string|number|array $data
     * @return array
     */
    public static function view($data = null)
    {
        $self = new static;
        
        $self->data = $data;

        return $self->body();
    }
}
