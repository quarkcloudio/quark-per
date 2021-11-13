<?php

namespace App\Admin\Resources;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Field;
use QuarkCMS\QuarkAdmin\Resource;
use Spatie\Permission\Models\Role;

class Admin extends Resource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public static $title = '管理员';

    /**
     * 模型
     *
     * @var string
     */
    public static $model = 'QuarkCMS\QuarkAdmin\Models\Admin';

    /**
     * 分页
     *
     * @var int|bool
     */
    public static $perPage = 10;

    /**
     * 字段
     *
     * @param  Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $getRoles = Role::where('guard_name','admin')->get()->toArray();
        $roles = [];

        foreach ($getRoles as $role) {
            $roles[$role['id']] = $role['name'];
        }

        return [
            Field::hidden('id','ID')
            ->onlyOnForms(),

            Field::image('avatar','头像')
            ->onlyOnForms(),

            Field::text('username','用户名', function() {
                return "<a href='#/index?api=admin/admin/edit&id=" . $this->id . "'>" . $this->username . "</a>";
            })
            ->rules(
                ['required','min:6','max:20'],
                ['required' => '用户名必须填写','min' => '用户名不能少于6个字符','max' => '用户名不能超过20个字符']
            )->creationRules(
                ['unique:admins,username'],
                ['unique'=>'用户名已存在']
            )->updateRules(
                ['unique:admins,username,{id}'],
                ['unique'=>'用户名已存在']
            ),

            Field::checkbox('role_ids','角色')
            ->options($roles)
            ->onlyOnForms(),

            Field::text('nickname','昵称')
            ->editable()
            ->rules(['required'], ['required' => '昵称必须填写']),
            
            Field::text('email','邮箱')
            ->rules(
                ['required'],
                ['required'=>'邮箱必须填写']
            )->creationRules(
                ['unique:admins,email'],
                ['unique'=>'邮箱已存在']
            )->updateRules(
                ['unique:admins,email,{id}'],
                ['unique'=>'邮箱已存在']
            ),

            Field::text('phone','手机号')
            ->rules(
                ['required'],
                ['required' => '手机号必须填写']
            )->creationRules(
                ['unique:admins,phone'],
                ['unique'=>'手机号已存在']
            )->updateRules(
                ['unique:admins,phone,{id}'],
                ['unique'=>'手机号已存在']
            ),

            Field::radio('sex','性别')
            ->options([1 => '男', 2 => '女'])
            ->default(1),

            Field::password('password','密码')
            ->creationRules(['required'], ['required'=>'密码必须填写'])
            ->onlyOnForms(),
            
            Field::datetime('last_login_time','最后登录时间')
            ->onlyOnIndex(),

            Field::switch('status','状态')
            ->editable()
            ->trueValue('正常')
            ->falseValue('禁用')
            ->default(true),
        ];
    }

    /**
     * 搜索表单
     *
     * @param  Request  $request
     * @return object
     */
    public function searches(Request $request)
    {
        return [
            new \App\Admin\Searches\Input('username', '用户名'),
            new \App\Admin\Searches\Input('nickname', '昵称'),
            new \App\Admin\Searches\Status,
            new \App\Admin\Searches\DateTimeRange('last_login_time', '登录时间')
        ];
    }

    /**
     * 行为
     *
     * @param  Request  $request
     * @return object
     */
    public function actions(Request $request)
    {
        return [
            (new \App\Admin\Actions\CreateLink($this->title()))->onlyOnIndex(),
            (new \App\Admin\Actions\Delete('批量删除'))->onlyOnIndexTableAlert(),
            (new \App\Admin\Actions\Disable('批量禁用'))->onlyOnIndexTableAlert(),
            (new \App\Admin\Actions\Enable('批量启用'))->onlyOnIndexTableAlert(),
            (new \App\Admin\Actions\ChangeStatus)->onlyOnIndexTableRow(),
            (new \App\Admin\Actions\EditLink('编辑'))->onlyOnIndexTableRow(),
            (new \App\Admin\Actions\Delete('删除'))->onlyOnIndexTableRow(),
            new \App\Admin\Actions\FormSubmit,
            new \App\Admin\Actions\FormReset,
            new \App\Admin\Actions\FormBack,
            new \App\Admin\Actions\FormExtraBack
        ];
    }

    /**
     * 保存前回调
     *
     * @param  Request  $request
     * @param  array $data
     * @return object
     */
    public function beforeEditing(Request $request, $data)
    {
        // 查询角色
        $roles = Role::where('guard_name','admin')->get()->toArray();
        $admin = static::newModel()->find($request->id);

        foreach ($roles as $role) {
            $hasRole = $admin->hasRole($role['name']);
            if($hasRole) {
                $roleIds[] = $role['id'];
            }
        }

        $data['role_ids'] = $roleIds ?? [];

        // 编辑的时候，不显示密码
        unset($data['password']);

        return $data;
    }

    /**
     * 保存前回调
     *
     * @param  Request  $request
     * @param  array $submitData
     * @return object
     */
    public function beforeSaving(Request $request, $submitData)
    {
        unset($submitData['role_ids']);

        if(isset($submitData['password'])) {
            $submitData['password'] = bcrypt($submitData['password']);
        }

        return $submitData;
    }

    /**
     * 保存后回调
     *
     * @param  Request  $request
     * @return object
     */
    public function afterSaved(Request $request, $model)
    {
        return $model->syncRoles($request->role_ids);
    }
}