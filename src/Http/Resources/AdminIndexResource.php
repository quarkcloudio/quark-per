<?php

namespace QuarkCMS\QuarkAdmin\Http\Resources;

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
     * 表格列
     *
     * @param  Column  $column
     * @return array
     */
    public function column(Column $column)
    {
        $columns[] = $column::make('avatar','头像')->hideInSearch()->render();
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
        $actions[] = $action::make('禁用|启用')->showStyle('link')->size('small')->style(['padding' => '4px 5px'])->api('www.baidu.com');
        $actions[] = $action::make('编辑')
        ->showStyle('link')
        ->size('small')
        ->style(['padding' => '4px 5px'])
        ->link('https://www.baidu.com','_blank');
        
        $actions[] = $action::make('删除')
        ->reload('table')
        ->showStyle('link')
        ->size('small')
        ->style(['padding' => '4px 5px'])
        ->withConfirm('确定要删除吗？', null, 'pop')
        ->api('admin/admin/delete?id={id}');

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
        $actions[] = $action::make('禁用|启用')->api('www.baidu.com');
        $actions[] = $action::make('编辑')->api('www.baidu.com');
        $actions[] = $action::make('删除')->api('www.baidu.com');

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