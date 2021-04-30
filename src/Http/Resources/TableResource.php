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
     * 获取分页参数
     *
     * @param  void
     * @return array
     */
    public function getPagination()
    {
        return $this->pagination($this->data);
    }

    /**
     * 获取列
     *
     * @param  void
     * @return array
     */
    public function getColumns()
    {
        return $this->column(new Column);
    }

    /**
     * 获取表格数据
     *
     * @param  void
     * @return array
     */
    public function getDatasource()
    {
        return $this->datasource($this->data);
    }

    /**
     * 渲染页面
     *
     * @param array  $data
     * @return array
     */
    public static function view($data = null)
    {
        $self = new static;

        $self->data = $data;

        // 获取列
        $columns = $self->getColumns();

        // 获取表格数据
        $datasource = $self->getDatasource();

        // 获取分页
        $pagination = $self->getPagination();

        // 表格
        $table = Table::key('table')
        ->api($self->api)
        ->apiType($self->apiType)
        ->title($self->title)
        ->columns($columns)
        ->datasource($datasource)
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