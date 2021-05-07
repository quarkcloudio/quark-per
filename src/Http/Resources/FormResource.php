<?php

namespace QuarkCMS\QuarkAdmin\Http\Resources;

use QuarkCMS\Quark\Facades\Page;
use QuarkCMS\Quark\Facades\Layout;
use QuarkCMS\Quark\Facades\PageContainer;
use QuarkCMS\Quark\Facades\Form;
use QuarkCMS\Quark\Facades\Action;
use QuarkCMS\Quark\Facades\Card;
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
     * 获取表单按钮
     *
     * @param  void
     * @return array
     */
    public function actions()
    {
        $actions[] = Action::make("提交")->showStyle('primary')->actionType('submit');
        $actions[] = Action::make('重置')->actionType('reset');

        return $actions;
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
        $items = $self->getItems();

        // 获取表单按钮
        $actions = $self->actions();

        // 表格
        $form = Form::api($self->api)
        ->title($self->title)
        ->items($items)
        ->actions($actions)
        ->render();

        $card = Card::title($self->title)->body($form);

        // 页面内容
        $pageContainer = PageContainer::title($self->title)->body($card);

        // 布局
        $layout = Layout::title($self->layoutTitle)->menu($self->menu)->body($pageContainer);

        // 页面
        $page = Page::style(['height' => '100vh'])->body($layout);

        return $page;
    }
}