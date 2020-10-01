<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Models\Admin;
use QuarkCMS\QuarkAdmin\Table;
use QuarkCMS\QuarkAdmin\Action;

class AdminController extends Controller
{
    public $title = '管理员列表';

    /**
     * 列表页面
     *
     * @param  Request  $request
     * @return Response
     */
    protected function table()
    {
        $table = new Table(new Admin);
        $table->headerTitle($this->title);
        
        $table->column('id','序号');
        $table->column('avatar','头像')->tooltip('用户头像')->image();
        $table->column('username','用户名')->editLink();
        $table->column('nickname','昵称')->width('12%');
        $table->column('email','邮箱')->width('12%');
        $table->column('sex','性别')
        ->using(['1'=>'男','2'=>'女'])
        ->filters(['1'=>'男','2'=>'女'])
        ->width(80);
        $table->column('phone','手机号')->sorter()->width(100);
        $table->column('last_login_time','最后登录时间');
        $table->column('status','状态')->using(['1'=>'正常','0'=>'禁用'])->width(60);
        $table->column('actions','操作')->width(180)->actions(function($row) {

            // 创建行为对象
            $action = new Action();

            // 根据不同的条件定义不同的A标签形式行为
            if($row['status'] === 1) {
                $action->a('禁用')
                ->withPopconfirm('确认要禁用数据吗？')
                ->model()
                ->where('id','{id}')
                ->update(['status'=>0]);
            } else {
                $action->a('启用')
                ->withPopconfirm('确认要启用数据吗？')
                ->model()
                ->where('id','{id}')
                ->update(['status'=>1]);
            }

            // 跳转默认编辑页面
            $action->a('编辑')->editLink();

            // 下拉菜单形式的行为
            $action->dropdown('更多')->overlay(function($action) {
                $action->item('详情')->editLink();
                $action->item('删除')
                ->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！')
                ->model()
                ->where('id','{id}')
                ->delete();
                return $action;
            });

            return $action;
        });

        // $table->toolBar()->actions(function($action) {

        //     // 跳转默认创建页面
        //     $action->button('创建管理员')->createLink();

        //     $action->button('导出日志')->link();
        //     return $action;
        // });

        // 批量操作
        $table->batchActions(function($action) {
            // 跳转默认编辑页面
            $action->a('批量删除')
            ->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！')
            ->model()
            ->whereIn('id','{ids}')
            ->delete();

            // 下拉菜单形式的行为
            $action->dropdown('批量操作')->overlay(function($action) {
                $action->item('详情')->editLink();
                $action->item('删除')
                ->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！')
                ->model()
                ->whereIn('id','{ids}')
                ->delete();
                return $action;
            });

            return $action;
        });

        // 搜索
        $table->search(function($search) {

            $search->where('username', '搜索内容',function ($model) {
                $model->where('username', 'like', "%{input}%");
            })->placeholder('名称');

            $search->equal('status', '所选状态')
            ->select([''=>'全部', 1=>'正常', 0=>'已禁用'])
            ->placeholder('选择状态')
            ->width(110);

            $search->between('last_login_time', '登录时间')->datetime();
        });

        $table->model()->paginate(request('pageSize',10));

        return $table;
    }
}
