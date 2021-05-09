<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;
use Spatie\Permission\Models\Role;
use QuarkCMS\QuarkAdmin\Models\Admin;
use QuarkCMS\QuarkAdmin\Http\Resources\AdminIndexResource;
use QuarkCMS\QuarkAdmin\Http\Resources\AdminCreateResource;
use QuarkCMS\QuarkAdmin\Http\Resources\AdminEditResource;

class AdminController extends Controller
{
    /**
     * 列表页面
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        $list = Admin::withQuerys()->paginate(request('pageSize',10));

        return AdminIndexResource::view($list);
    }

    /**
     * 创建页面
     *
     * @param  Request  $request
     * @return Response
     */
    public function create(Request $request)
    {
        // 查询角色
        $getRoles = Role::where('guard_name','admin')->get()->toArray();

        $roles = [];

        foreach ($getRoles as $key => $role) {
            $roles[$role['id']] = $role['name'];
        }

        $data['roles'] = $roles;

        return AdminCreateResource::view($data);
    }

    /**
     * 创建操作
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();
        
        $rules = [
            'username' => [
                'required',
                'unique:admins'
            ],
            'nickname' => [
                'required'
            ],
            'email' => [
                'required',
                'unique:admins'
            ],
            'phone' => [
                'required',
                'unique:admins'
            ],
            'password' => [
                'required'
            ],
        ];

        $ruleMessages = [
            'username.required' => '用户名不能为空！',
            'username.unique' => '用户名已存在！',
            'email.required' => '邮箱不能为空！',
            'email.unique' => '邮箱已存在！',
            'phone.required' => '手机号不能为空！',
            'phone.unique' => '手机号已存在！',
            'password.required' => '密码不能为空！',
        ];

        $validator = Validator::make($data,$rules,$ruleMessages);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return error($error);
        }

        // 将头像转化为数组
        if(isset($data['avatar'])) {
            $data['avatar'] = json_encode($data['avatar']);
        }

        if(isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        // 删除角色
        unset($data['role_ids']);

        $result = Admin::create($data);

        if($result) {

            // 同步角色
            $result->syncRoles(request('role_ids'));

            // 返回结果
            return success('操作成功！','/index?api=admin/admin/index');
        } else {
            return error('操作失败，请重试！');
        }
    }

    /**
     * 编辑页面
     *
     * @param  Request  $request
     * @return Response
     */
    public function edit(Request $request)
    {
        $id = $request->get('id');

        if(empty($id)) {
            return error('参数错误！');
        }

        // 查询角色
        $getRoles = Role::where('guard_name','admin')->get()->toArray();

        $roles = $roleIds = [];
        foreach ($getRoles as $key => $role) {
            $roles[$role['id']] = $role['name'];
        }

        $admin = Admin::find($id);
        foreach ($roles as $key => $role) {
            $hasRole = $admin->hasRole($role['name']);
            if($hasRole) {
                $roleIds[] = $role['id'];
            }
        }

        $data['roles'] = $roles;
        $data['roleIds'] = $roleIds;
        $data['admin'] = $admin;

        return AdminEditResource::view($data);
    }

    /**
     * 更新操作
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request)
    {
        $data = $request->all();
        
        if(empty($data['id'])) {
            return error('参数错误！');
        }

        $rules = [
            'username' => [
                'required',
                'unique:admins,username,'.$data['id']
            ],
            'nickname' => [
                'required'
            ],
            'email' => [
                'required',
                'unique:admins,email,'.$data['id']
            ],
            'phone' => [
                'required',
                'unique:admins,phone,'.$data['id']
            ]
        ];

        $ruleMessages = [
            'username.required' => '用户名不能为空！',
            'username.unique' => '用户名已存在！',
            'email.required' => '邮箱不能为空！',
            'email.unique' => '邮箱已存在！',
            'phone.required' => '手机号不能为空！',
            'phone.unique' => '手机号已存在！'
        ];

        $validator = Validator::make($data,$rules,$ruleMessages);

        if ($validator->fails()) {
            $error = $validator->errors()->first();
            return error($error);
        }

        // 将头像转化为数组
        if(isset($data['avatar'])) {
            $data['avatar'] = json_encode($data['avatar']);
        }

        if(isset($data['password'])) {
            $data['password'] = bcrypt($data['password']);
        }

        // 删除角色
        unset($data['role_ids']);

        $result = Admin::where('id',$data['id'])->update($data);

        if($result) {

            // 同步角色
            Admin::where('id',$data['id'])->first()->syncRoles(request('role_ids'));

            // 返回结果
            return success('操作成功！','/index?api=admin/admin/index');
        } else {
            return error('操作失败，请重试！');
        }
    }

    /**
     * 删除操作
     *
     * @param  Request  $request
     * @return Response
     */
    public function destroy(Request $request)
    {
        $ids = explode(',',$request->get('id'));

        if(empty($ids)) {
            return error('请选择要删除的数据！');
        }

        $result = Admin::whereIn('id',$ids)->delete();

        if($result) {
            return success('操作成功！');
        } else {
            return error('操作失败，请重试！');
        }
    }

    /**
     * 改变状态
     *
     * @param  Request  $request
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        $ids = explode(',',$request->get('id'));
        $status = $request->get('status');

        if(empty($ids)) {
            return error('请选择要操作的数据！');
        }

        $result = Admin::whereIn('id',$ids)->update([
            'status' => $status ? 0 : 1
        ]);

        if($result) {
            return success('操作成功！');
        } else {
            return error('操作失败，请重试！');
        }
    }
}
