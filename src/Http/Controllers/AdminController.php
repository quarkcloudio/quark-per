<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Models\Admin;
use QuarkCMS\QuarkAdmin\Card;
use QuarkCMS\QuarkAdmin\Table;
use QuarkCMS\QuarkAdmin\Action;
use QuarkCMS\QuarkAdmin\Form;
use Spatie\Permission\Models\Role;

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

        $table->toolBar()->actions(function($action) {

            // 跳转默认创建页面
            $action->button('创建管理员')->type('primary')->icon('plus-circle')->createLink();

            // 下拉菜单形式的行为
            $action->dropdown('更多操作')->mode('button')->overlay(function($action) {
                $action->item('详情')->createLink();
                $action->item('清空数据')
                ->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！')
                ->model()
                ->where('status',1)
                ->delete();
                return $action;
            });

            return $action;
        });

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

    /**
     * 表单页面
     *
     * @param  Request  $request
     * @return Response
     */
    protected function form()
    {
        $id = request('id');

        $form = new Form(new Admin);

        $form->hidden('id');
        
        // 默认单图上传
        $form->image('avatar','头像')->help('只支持jpg、png格式文件')->button('上传头像');

        $form->text('username','用户名')
        ->rules(['required','min:6','max:20'],['required'=>'用户名必须填写','min'=>'用户名不能少于6个字符','max'=>'用户名不能超过20个字符'])
        ->creationRules(["unique:admins"],['unique'=>'用户名已经存在'])
        ->updateRules(["unique:admins,username,{id}"],['unique'=>'用户名已经存在']);

        // 查询列表
        $roles = Role::where('guard_name','admin')->get()->toArray();

        $options = [];
        foreach ($roles as $key => $role) {
            $options[$role['id']] = $role['name'];
        }

        $roleIds = [];
        if($id) {
            $admin = Admin::find($id);
            foreach ($roles as $key => $role) {
                $hasRole = $admin->hasRole($role['name']);
                if($hasRole) {
                    $roleIds[] = $role['id'];
                }
            }
        }

        $form->checkbox('role_ids','角色')
        ->options($options)
        ->value($roleIds);

        $form->text('nickname','昵称')
        ->rules(['required','max:20'],['required'=>'昵称必须填写','max'=>'昵称不能超过20个字符']);

        $form->radio('sex','性别')
        ->options(['1' => '男', '2'=> '女'])
        ->default(1);

        $form->text('email','邮箱')
        ->rules(['required','email','max:255'],['required'=>'邮箱必须填写','email'=>'邮箱格式错误','max'=>'邮箱不能超过255个字符'])
        ->creationRules(["unique:admins"],['unique'=>'邮箱已经存在',])
        ->updateRules(["unique:admins,email,{id}"],['unique'=>'邮箱已经存在']);

        $form->text('phone','手机号')
        ->rules(['required','max:11'],['required'=>'手机号必须填写','max'=>'手机号不能超过11个字符'])
        ->creationRules(["unique:admins"],['unique'=>'手机号已经存在'])
        ->updateRules(["unique:admins,phone,{id}"],['unique'=>'手机号已经存在']);

        $form->text('password','密码')
        ->type('password')
        ->creationRules(["required"],['required'=>'密码不能为空']);

        $form->switch('status','状态')->options([
            'on'  => '正常',
            'off' => '禁用'
        ])->default(true);

        return $form;
    }
}
