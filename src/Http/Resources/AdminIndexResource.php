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
    public $title = '管理员列表';

    /**
     * 工具栏
     *
     * @param  ToolBar $toolBar
     * @return array
     */
    public function toolBar(ToolBar $toolBar)
    {
        $getToolBar = $toolBar::make($this->title)
        ->actions(function ($action) {
            $actions[] = $action::make('新增管理员')
            ->showStyle('primary')
            ->link('#index?api=admin/admin/create')
            ->icon('plus-circle')
            ->render();

            return $actions;
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
        $columns[] = $column::make('username','用户名')->render();
        $columns[] = $column::make('nickname','昵称')->render();
        $columns[] = $column::make('email','邮箱')->render();
        $columns[] = $column::make('sex','性别')->valueEnum(['1'=>'男','2'=>'女'])->render();
        $columns[] = $column::make('phone','手机号')->render();
        $columns[] = $column::make('last_login_time_range','最后登录时间')->hideInTable()->valueType('dateTimeRange')->render();
        $columns[] = $column::make('last_login_time','最后登录时间')->hideInSearch()->valueType('dateTime')->render();
        $columns[] = $column::make('status','状态')
        ->valueEnum([
            '1' => [ 'text' => '正常', 'status' => 'processing' ],
            '0' => [ 'text' => '禁用', 'status' => 'default' ]
        ])
        ->render();
        $columns[] = $column::make('actions','操作')
        ->valueType('option')
        ->actions(
            $this->rowActions(new Action)
        )->render();

        return $columns;
    }

    /**
     * 行操作
     *
     * @param  Action $action
     * @return array
     */
    public function rowActions(Action $action)
    {
        $actions[] = $action::make("<% data.status==1 ? '禁用' : '启用' %>")
        ->reload('table')
        ->showStyle('link')
        ->size('small')
        ->style(['padding' => '4px 5px'])
        ->withConfirm("确定要<% data.status==1 ? '启用' : '禁用' %>数据吗？", null, 'pop')
        ->api('admin/admin/changeStatus?id={id}&status={status}');

        $actions[] = $action::make('编辑')
        ->showStyle('link')
        ->size('small')
        ->style(['padding' => '4px 5px'])
        ->link('#index?api=admin/admin/edit&id={id}');
        
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
        $actions[] = $action::make("批量禁用")
        ->reload('table')
        ->showStyle('link')
        ->size('small')
        ->style(['padding' => '4px 5px'])
        ->withConfirm("确定要禁用这些数据吗？", '禁用后数据将不可使用！')
        ->api('admin/admin/changeStatus?id={ids}&status=1');
        
        $actions[] = $action::make("批量启用")
        ->reload('table')
        ->showStyle('link')
        ->size('small')
        ->style(['padding' => '4px 5px'])
        ->withConfirm("确定要启用这些数据吗？", '启用后数据将可正常使用！')
        ->api('admin/admin/changeStatus?id={ids}&status=0');

        $actions[] = $action::make('批量删除')
        ->reload('table')
        ->showStyle('link')
        ->size('small')
        ->style(['padding' => '4px 5px'])
        ->withConfirm('确定要启用这些数据吗？', '删除后数据将不可恢复！')
        ->api('admin/admin/destroy?id={ids}');

        // 下拉菜单形式的行为
        // $action::dropdown('更多')->overlay(function($action) {
        //     $action->item('禁用')
        //     ->withConfirm('确认要禁用吗？','禁用后数据将无法使用，请谨慎操作！')
        //     ->model()
        //     ->whereIn('id','{ids}')
        //     ->update(['status'=>0]);

        //     $action->item('启用')
        //     ->withConfirm('确认要启用吗？','启用后数据可以正常使用！')
        //     ->model()
        //     ->whereIn('id','{ids}')
        //     ->update(['status'=>1]);
        // });

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