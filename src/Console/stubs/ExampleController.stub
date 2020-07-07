<?php

namespace App\Http\Controllers\Admin;

use QuarkCMS\QuarkAdmin\Models\Admin;
use QuarkCMS\QuarkAdmin\Controllers\QuarkController;
use Quark;

class ExampleController extends QuarkController
{
    /**
     * Title for current resource.
     *
     * @var string
     */
    public $title = 'Example controller';

    /**
     * 列表页面
     *
     * @param  Request  $request
     * @return Response
     */
    protected function table()
    {
        $grid = Quark::grid(new Admin)->title($this->title);
        $grid->column('picture.path','头像')->image();
        $grid->column('username','用户名')->link();
        $grid->column('nickname','昵称')->editable()->width('12%');
        $grid->column('email','邮箱')->editable()->width('12%');
        $grid->column('sex','性别')->using(['1'=>'男','2'=>'女'])->filters(['1'=>'男','2'=>'女'])->width(80);
        $grid->column('phone','手机号')->sorter()->width(100);
        $grid->column('last_login_time','最后登录时间');
        $grid->column('status','状态')->editable('switch',[
            'on'  => ['value' => 1, 'text' => '正常'],
            'off' => ['value' => 2, 'text' => '禁用']
        ])->width(100);

        $grid->column('actions','操作')->width(100)->rowActions(function($rowAction) {
            $rowAction->menu('edit', '编辑');
            $rowAction->menu('show', '显示');
            $rowAction->menu('delete', '删除')->model(function($model) {
                $model->delete();
            })->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！');
        });

        // 头部操作
        $grid->actions(function($action) {
            $action->button('create', '新增');
            $action->button('refresh', '刷新');
        });

        // select样式的批量操作
        $grid->batchActions(function($batch) {
            $batch->option('', '批量操作');
            $batch->option('resume', '启用')->model(function($model) {
                $model->update(['status'=>1]);
            });
            $batch->option('forbid', '禁用')->model(function($model) {
                $model->update(['status'=>2]);
            });
            $batch->option('delete', '删除')->model(function($model) {
                $model->delete();
            })->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！');
        })->style('select',['width'=>120]);

        $grid->search(function($search) {
            $search->equal('status', '所选状态')->select([''=>'全部',1=>'正常',2=>'已禁用'])->placeholder('选择状态')->width(110);
            $search->where('usernameOrNickname', '搜索内容',function ($query) {
                $query->where('username', 'like', "%{input}%")->orWhere('nickname', 'like', "%{input}%")->orWhere('phone', 'like', "%{input}%");
            })->placeholder('用户名/手机号/昵称');
            $search->between('last_login_time', '登录时间')->datetime()->advanced();
        })->expand(false);

        $grid->model()
        ->select('id as key','admins.*')
        ->paginate(10);

        return $grid;
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

        $form = Quark::form(new Admin);

        $title = $form->isCreating() ? '创建'.$this->title : '编辑'.$this->title;
        $form->title($title);
        
        $form->id('id','ID');

        $form->editor('content','内容');

        $form->image('avatar','头像')->button('上传头像');

        $form->text('username','用户名')
        ->rules(['required','min:6','max:20'],['required'=>'用户名必须填写','min'=>'用户名不能少于6个字符','max'=>'用户名不能超过20个字符'])
        ->creationRules(["unique:admins"],['unique'=>'用户名已经存在'])
        ->updateRules(["unique:admins,username,{{id}}"],['unique'=>'用户名已经存在']);

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
        ->updateRules(["unique:admins,email,{{id}}"],['unique'=>'邮箱已经存在']);

        $form->text('phone','手机号')
        ->rules(['required','max:11'],['required'=>'手机号必须填写','max'=>'手机号不能超过11个字符'])
        ->creationRules(["unique:admins"],['unique'=>'手机号已经存在'])
        ->updateRules(["unique:admins,phone,{{id}}"],['unique'=>'手机号已经存在']);

        $form->text('password','密码')
        ->creationRules(["required"],['required'=>'密码不能为空']);

        $form->switch('status','状态')->options([
            'on'  => '正常',
            'off' => '禁用'
        ])->default(true);

        return $form;
    }


    /**
     * 保存方法
     * 
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $requestJson    =   $request->getContent();
        $requestData    =   json_decode($requestJson,true);

        if(!isset($requestData['role_ids'])) {
            return $this->error('请选择角色');
        }

        $roleIds =   $requestData['role_ids'];

        if(isset($requestData['avatar']['id'])) {
            $requestData['avatar'] = $requestData['avatar']['id'];
        } else {
            unset($requestData['avatar']);
        }

        // 删除modelName
        unset($requestData['id']);
        unset($requestData['actionUrl']);
        unset($requestData['role_ids']);

        // 表单验证错误提示信息
        $messages = [
            'unique' => '已经存在',
        ];

        // 表单验证规则
        $rules = [
            'username' => [Rule::unique('admins')],
            'email' =>  [Rule::unique('admins')],
            'phone' =>  [Rule::unique('admins')],
        ];

        // 进行验证
        $validator = Validator::make($requestData,$rules,$messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();

            foreach($errors as $key => $value) {
                if($key === 'username') {
                    $errorMsg = '用户名'.$value[0];
                }

                if($key === 'email') {
                    $errorMsg = '邮箱'.$value[0];
                }

                if($key === 'phone') {
                    $errorMsg = '手机号'.$value[0];
                }
            }

            return $this->error($errorMsg);
        }

        if (!empty($requestData['password'])) {
            $requestData['password'] = bcrypt($requestData['password']);
        }

        if ($requestData['status'] == true) {
            $requestData['status'] = 1;
        } else {
            $requestData['status'] = 2;
        }

        $admin = Admin::create($requestData);

        // You may also pass an array
        $result = $admin->syncRoles($roleIds);

        if ($result) {
            return success('操作成功！','/quark/engine?api=admin/admin/index&component=table');
        } else {
            return error('操作失败！');
        }
    }

    /**
     * 保存编辑数据
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request)
    {
        $requestJson    =   $request->getContent();
        $requestData    =   json_decode($requestJson,true);

        if(!isset($requestData['role_ids'])) {
            return error('请选择角色');
        }

        $roleIds = $requestData['role_ids'];

        if(isset($requestData['avatar']['id'])) {
            $requestData['avatar'] = $requestData['avatar']['id'];
        } else {
            unset($requestData['avatar']);
        }

        // 删除modelName
        unset($requestData['actionUrl']);
        unset($requestData['role_ids']);

        // 表单验证错误提示信息
        $messages = [
            'unique' => '已经存在',
        ];

        // 表单验证规则
        $rules = [
            'username' => [Rule::unique('admins')->ignore($requestData['id'])],
            'email' =>  [Rule::unique('admins')->ignore($requestData['id'])],
            'phone' =>  [Rule::unique('admins')->ignore($requestData['id'])],
        ];

        // 进行验证
        $validator = Validator::make($requestData,$rules,$messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();

            foreach($errors as $key => $value) {
                if($key === 'username') {
                    $errorMsg = '用户名'.$value[0];
                }

                if($key === 'email') {
                    $errorMsg = '邮箱'.$value[0];
                }

                if($key === 'phone') {
                    $errorMsg = '手机号'.$value[0];
                }
            }

            return error($errorMsg);
        }

        if (!empty($requestData['password'])) {
            $requestData['password'] = bcrypt($requestData['password']);
        }

        if ($requestData['status'] == true) {
            $requestData['status'] = 1;
        } else {
            $requestData['status'] = 2;
        }

        $result = Admin::where('id',$requestData['id'])->update($requestData);

        // 同步角色
        $result1 = Admin::where('id',$requestData['id'])->first()->syncRoles($roleIds);

        if ($result) {
            return success('操作成功！','/quark/engine?api=admin/admin/index&component=table');
        } else {
            return error('操作失败！');
        }
    }

    /**
     * 详情页面
     * 
     * @param  Request  $request
     * @return Response
     */
    protected function detail($id)
    {
        $show = Quark::show(Admin::findOrFail($id)->toArray())->title('详情页');

        $show->field('id','ID');
        $show->field('avatar','头像')->image();
        $show->field('username','用户名');
        $show->field('nickname','昵称');
        $show->field('sex','性别');
        $show->field('created_at','注册时间');
        $show->field('last_login_time','登录时间');
        $show->field('last_login_ip','登录IP');
        $show->field('status','状态');

        //渲染前回调
        $show->rendering(function ($show) {
            $show->data['avatar'] = get_picture($show->data['avatar']);
            $show->data['sex'] == 1 ? $show->data['sex'] = '男' : $show->data['sex'] = '女';

            if(empty($show->data['last_login_time'])) {
                $show->data['last_login_time'] = '暂无';
            }

            if(empty($show->data['last_login_ip'])) {
                $show->data['last_login_ip'] = '暂无';
            }

            $show->data['status'] == 1 ? $show->data['status'] = '正常' : $show->data['status'] = '已禁用';
        });

        return $show;
    }
}
