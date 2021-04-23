<?php

namespace QuarkCMS\QuarkAdmin\Http\Resources;

use QuarkCMS\Quark\Facades\Layout;
use QuarkCMS\Quark\Facades\Card;
use QuarkCMS\Quark\Facades\Row;
use QuarkCMS\Quark\Facades\StatisticCard;
use QuarkCMS\Quark\Facades\Descriptions;
use QuarkCMS\QuarkAdmin\Http\Resources\LayoutResource;

class TableResource extends LayoutResource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public $title = '管理员列表';

    /**
     * 页面标题
     *
     * @var string
     */
    public $initApi = '管理员列表';

    /**
     * 页面内容
     *
     * @param  void
     * @return void
     */
    public function column($column)
    {
        $column->item('name','名称')->width(200);
        $column->item('sort','排序')->width(60);
        $column->item('icon','图标');
        $column->item('path','路由')->width('18%');
        $column->item('show','显示')->using(['1'=>'显示','0'=>'隐藏'])->width(100);
        $column->item('status','状态')->using(['1'=>'正常','0'=>'禁用'])->width(60);
        $column->item('actions','操作')->width(200)->actions(function($action,$row) {

            // 根据不同的条件定义不同的A标签形式行为
            if($row['status'] === 1) {
                $action->a('禁用')
                ->withPopconfirm('确认要禁用数据吗？');
            } else {
                $action->a('启用')
                ->withPopconfirm('确认要启用数据吗？');
            }

            // 跳转默认编辑页面
            $action->a('编辑')->drawerForm(backend_url('api/admin/menu/edit?id='.$row['id']));

            if($row['show'] === 1) {
                $action->a('隐藏')
                ->withPopconfirm('确认要隐藏菜单吗？');
            } else {
                $action->a('显示')
                ->withPopconfirm('确认要显示菜单吗？');
            }

            $action->a('删除')
            ->withPopconfirm('确认要删除吗？');
        });

        return $column;
    }

    /**
     * 页面内容
     *
     * @param  void
     * @return void
     */
    public function toolBar($action)
    {
        // 跳转默认创建页面
        $action->button('创建菜单')
        ->type('primary')
        ->icon('plus-circle')
        ->drawerForm(backend_url('api/admin/menu/create'));
    }

    /**
     * 页面内容
     *
     * @param  void
     * @return void
     */
    public function batchActions($action)
    {
        // 跳转默认编辑页面
        $action->a('批量删除')
        ->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！');

        // 下拉菜单形式的行为
        $action->dropdown('更多')->overlay(function($action) {
            $action->item('禁用菜单')
            ->withConfirm('确认要禁用吗？','禁用后菜单将无法使用，请谨慎操作！');

            $action->item('启用菜单')
            ->withConfirm('确认要启用吗？','启用后菜单可以正常使用！');
        });
    }

    /**
     * 页面内容
     *
     * @param  void
     * @return void
     */
    public function search($column)
    {
        // 跳转默认编辑页面
        $action->a('批量删除')
        ->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！');

        // 下拉菜单形式的行为
        $action->dropdown('更多')->overlay(function($action) {
            $action->item('禁用菜单')
            ->withConfirm('确认要禁用吗？','禁用后菜单将无法使用，请谨慎操作！');

            $action->item('启用菜单')
            ->withConfirm('确认要启用吗？','启用后菜单可以正常使用！');
        });
    }
}