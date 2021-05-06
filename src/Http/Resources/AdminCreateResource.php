<?php

namespace QuarkCMS\QuarkAdmin\Http\Resources;

use QuarkCMS\Quark\Facades\ToolBar;
use QuarkCMS\Quark\Facades\Column;
use QuarkCMS\Quark\Facades\Action;
use QuarkCMS\QuarkAdmin\Http\Resources\TableResource;

class AdminIndexResource extends TableResource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public $title = '创建管理员';

    /**
     * 表单项
     *
     * @param  array $data
     * @return array
     */
    public function items($data)
    {
        return $data->items();
    }
}