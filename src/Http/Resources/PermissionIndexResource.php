<?php

namespace QuarkCMS\QuarkAdmin\Http\Resources;

use QuarkCMS\Quark\Facades\ToolBar;
use QuarkCMS\Quark\Facades\Column;
use QuarkCMS\Quark\Facades\Action;
use QuarkCMS\Quark\Facades\Space;
use QuarkCMS\QuarkAdmin\Http\Resources\TableResource;

class PermissionIndexResource extends TableResource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public $title = '权限列表';

    /**
     * 工具栏
     *
     * @param  ToolBar $toolBar
     * @return array
     */
    public function toolBar(ToolBar $toolBar)
    {
        $getToolBar = $toolBar::make($this->title)->actions(function ($action) {
            $actions = [
                $action::make('同步权限')->showStyle('primary')->api('admin/permission/sync')->render(),
                $action::make('清空权限')->showStyle('default')->api('admin/permission/clear')->render()
            ];

            return Space::body($actions);
        })->render();

        return $getToolBar;
    }

    /**
     * 表格列
     *
     * @param  Column  $column
     * @return array
     */
    public function column(Column $column)
    {
        return [
            $column::make('name','名称')->render(),
            $column::make('guard_name','guard名称')->hideInSearch()->render(),
            $column::make('created_at','创建时间')->hideInSearch()->render(),
            $column::make('actions','操作')->valueType('option')->actions(
                $this->rowActions(new Action)
            )->render()
        ];
    }

    /**
     * 行操作
     *
     * @param  Action $action
     * @return array
     */
    public function rowActions(Action $action)
    {
        $actions[] = $action::make('删除')
        ->reload('table')
        ->showStyle('link')
        ->size('small')
        ->style(['padding' => '4px 5px'])
        ->withConfirm('确定要删除吗？', null, 'pop')
        ->api('admin/admin/destroy?id={id}');

        return $actions;
    }

    /**
     * 批量操作
     *
     * @param  Action $action
     * @return array
     */
    public function batchActions(Action $action)
    {
        $actions[] = $action::make('批量删除')
        ->reload('table')
        ->showStyle('link')
        ->size('small')
        ->style(['padding' => '4px 5px'])
        ->withConfirm('确定要启用这些数据吗？', '删除后数据将不可恢复！')
        ->api('admin/admin/destroy?id={ids}');

        return $actions;
    }

    /**
     * 表格数据
     *
     * @param  array $data
     * @return array
     */
    public function datasource($data)
    {
        return $data->items();
    }

    /**
     * 分页
     *
     * @param  array $data
     * @return array
     */
    public function pagination($data)
    {
        $pagination['current'] = $data->currentPage();
        $pagination['pageSize'] = $data->perPage();
        $pagination['total'] = $data->total();

        return $pagination;
    }
}