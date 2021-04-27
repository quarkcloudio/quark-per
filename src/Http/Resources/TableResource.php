<?php

namespace QuarkCMS\QuarkAdmin\Http\Resources;

use QuarkCMS\Quark\Facades\Table;
use QuarkCMS\Quark\Facades\Page;
use QuarkCMS\Quark\Facades\Layout;
use QuarkCMS\Quark\Facades\PageContainer;
use QuarkCMS\Quark\Facades\Column;
use QuarkCMS\QuarkAdmin\Http\Resources\LayoutResource;

class TableResource extends LayoutResource
{
    /**
     * 接口
     *
     * @var string
     */
    public $api = null;

    /**
     * 接口类型
     *
     * @var string
     */
    public $apiType = 'GET';

    /**
     * 渲染页面
     *
     * @param  void
     * @return void
     */
    public static function view($data = null)
    {
        $self = new static;

        $self->data = $data;
        $pagination = $self->pagination();

        // 表格
        $table = Table::api($self->api)
        ->apiType($self->apiType)
        ->title($self->title)
        ->columns($self->column(new Column))
        ->datasource($self->datasource())
        ->pagination($pagination['current'], $pagination['pageSize'], $pagination['total'])
        ->render();

        // 页面内容
        $pageContainer = PageContainer::title($self->title)->body($table);

        // 布局
        $layout = Layout::title($self->layoutTitle)->menu($self->menu)->body($pageContainer);

        // 页面
        $page = Page::style(['height' => '100vh'])->body($layout);

        return $page;
    }
}