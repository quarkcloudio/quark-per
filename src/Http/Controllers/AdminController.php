<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Models\Admin;
use QuarkCMS\QuarkAdmin\Table;
use QuarkCMS\QuarkAdmin\Action;
use QuarkCMS\QuarkAdmin\Form;
use Spatie\Permission\Models\Role;

class AdminController extends Controller
{
    public $title = '管理员';

    /**
     * 列表页面
     *
     * @param  Request  $request
     * @return Response
     */
    protected function table()
    {
        $table = new Table(new Admin);
        $table->headerTitle($this->title.'列表');
        
        $table->column('id','序号');
        $table->column('avatar','头像')->image()->width(60);
        $table->column('username','用户名')->editLink()->width(120);
        $table->column('nickname','昵称')->editable()->width(120);
        $table->column('email','邮箱')->width(160);
        $table->column('sex','性别')
        ->using(['1'=>'男','2'=>'女'])
        ->filters(['1'=>'男','2'=>'女'])
        ->width(80);
        $table->column('phone','手机号')->sorter()->width(100);
        $table->column('last_login_time','最后登录时间')->width(160);
        $table->column('status','状态')->editable('switch',[
            'on'  => ['value' => 1, 'text' => '正常'],
            'off' => ['value' => 0, 'text' => '禁用']
        ])->width(100);
        $table->column('actions','操作')->width(120)->actions(function($row) {

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

            $action->a('删除')
            ->withPopconfirm('确认要删除吗？')
            ->model()
            ->where('id','{id}')
            ->delete();

            // 下拉菜单形式的行为
            // $action->dropdown('更多')->overlay(function($action) {
            //     $action->item('详情')->editLink();
            //     $action->item('删除')
            //     ->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！')
            //     ->model()
            //     ->where('id','{id}')
            //     ->delete();
            //     return $action;
            // });

            return $action;
        });

        $table->toolBar()->actions(function($action) {

            // 跳转默认创建页面
            $action->button('创建管理员')->type('primary')->icon('plus-circle')->createLink();

            // 下拉菜单形式的行为
            // $action->dropdown('更多操作')->mode('button')->overlay(function($action) {
            //     $action->item('详情')->createLink();
            //     $action->item('清空数据')
            //     ->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！')
            //     ->model()
            //     ->where('status',1)
            //     ->delete();
            //     return $action;
            // });

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
            $action->dropdown('更多')->overlay(function($action) {
                $action->item('禁用用户')
                ->withConfirm('确认要禁用吗？','禁用后管理员将无法登陆后台，请谨慎操作！')
                ->model()
                ->whereIn('id','{ids}')
                ->update(['status'=>0]);

                $action->item('启用用户')
                ->withConfirm('确认要启用吗？','启用后管理员将可以正常登录后台！')
                ->model()
                ->whereIn('id','{ids}')
                ->update(['status'=>1]);

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

        $table->model()->orderBy('id','desc')->paginate(request('pageSize',10));

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

        if($form->isCreating()) {
            $title = '新增'.$this->title;
        } else {
            $title = '编辑'.$this->title;
        }

        $form->title($title);

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

        // 编辑页面展示前回调
        $form->editing(function ($form) {
            if(isset($form->initialValues['avatar'])) {
                $form->initialValues['avatar'] = get_picture($form->initialValues['avatar'],0,'all');
            }
        });

        // 保存数据前回调
        $form->saving(function ($form) {
            if(isset($form->data['role_ids'])) {
                $data = $form->data;
                unset($data['role_ids']);
                $form->data = $data;
            }

            if(isset($form->data['password'])) {
                $form->data['password'] = bcrypt($form->data['password']);
            }

            if(isset($form->data['avatar'])) {
                $form->data['avatar'] = $form->data['avatar']['id'];
            }
        });

        // 保存数据后回调
        $form->saved(function ($form) {
            if($form->model()) {
                if($form->isCreating()) {
                    $form->model()->syncRoles(request('role_ids'));
                } else {
                    Admin::where('id',request('id'))->first()->syncRoles(request('role_ids'));
                }
                return success('操作成功！',frontend_url('admin/admin/index'));
            } else {
                return error('操作失败，请重试！');
            }
        });

        return $form;
    }
}
