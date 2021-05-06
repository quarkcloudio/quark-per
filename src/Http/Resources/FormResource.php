<?php

namespace QuarkCMS\QuarkAdmin\Http\Resources;

use QuarkCMS\Quark\Facades\Page;
use QuarkCMS\Quark\Facades\Layout;
use QuarkCMS\Quark\Facades\PageContainer;
use QuarkCMS\Quark\Facades\Form;
use QuarkCMS\QuarkAdmin\Http\Resources\LayoutResource;

class FormResource extends LayoutResource
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
     * 获取表单项
     *
     * @param  void
     * @return array
     */
    public function getItems()
    {
        return $this->items($this->data);
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

        // 获取表单项
        $toolBar = $self->getItems();

        // 表格
        $table = Form::key('table')
        ->api($self->api)
        ->apiType($self->apiType)
        ->title($self->title)
        ->items($this->items())
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