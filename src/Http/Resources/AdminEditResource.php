<?php

namespace QuarkCMS\QuarkAdmin\Http\Resources;

use QuarkCMS\Quark\Facades\FormItem;
use QuarkCMS\QuarkAdmin\Http\Resources\FormResource;

class AdminEditResource extends FormResource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public $title = '编辑管理员';

    /**
     * 表单提交接口
     *
     * @var string
     */
    public $api = 'admin/admin/update';

    /**
     * 表单项
     *
     * @param  array $data
     * @return array
     */
    public function items($data)
    {
        $roles = $data['roles'];
        $roleIds = $data['roleIds'];
        $admin = $data['admin'];

        $formItems[] = FormItem::hidden('id','ID')->value($admin['id']);
        $formItems[] = FormItem::image('avatar','头像')->value(json_decode($admin['avatar']));
        $formItems[] = FormItem::text('username','用户名')->rules(
            ['required','min:6','max:20'],
            ['required'=>'用户名必须填写','min'=>'用户名不能少于6个字符','max'=>'用户名不能超过20个字符']
        )->value($admin['username']);
        $formItems[] = FormItem::checkbox('role_ids','角色')->options($roles)->value($roleIds);
        $formItems[] = FormItem::text('nickname','昵称')->rules(
            ['required'],
            ['required'=>'昵称必须填写']
        )->value($admin['nickname']);
        $formItems[] = FormItem::text('email','邮箱')->rules(
            ['required'],
            ['required'=>'邮箱必须填写']
        )->value($admin['email']);
        $formItems[] = FormItem::text('phone','手机号')->rules(
            ['required'],
            ['required'=>'手机号必须填写']
        )->value($admin['phone']);
        $formItems[] = FormItem::radio('sex','性别')->options([1 => '男', 2 => '女'])->value($admin['sex']);
        $formItems[] = FormItem::password('password','密码');
        $formItems[] = FormItem::switch('status','状态')->options([
            'on'  => '正常',
            'off' => '禁用'
        ])->value($admin['status']);

        return $formItems;
    }
}