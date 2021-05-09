<?php

namespace QuarkCMS\QuarkAdmin\Http\Resources;

use QuarkCMS\Quark\Facades\FormItem;
use QuarkCMS\QuarkAdmin\Http\Resources\FormResource;

class AdminCreateResource extends FormResource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public $title = '创建管理员';

    /**
     * 表单提交接口
     *
     * @var string
     */
    public $api = 'admin/admin/store';

    /**
     * 表单项
     *
     * @param  array $data
     * @return array
     */
    public function items($data)
    {
        return [
            FormItem::image('avatar','头像'),
            FormItem::text('username','用户名')->rules(
                [
                    'required',
                    'min:6',
                    'max:20'
                ],
                [
                    'required' => '用户名必须填写',
                    'min' => '用户名不能少于6个字符',
                    'max' => '用户名不能超过20个字符'
                ]
            ),
            FormItem::checkbox('role_ids','角色')->options($data['roles']),
            FormItem::text('nickname','昵称')->rules(['required'], ['required' => '昵称必须填写']),
            FormItem::text('email','邮箱')->rules(['required'], ['required' => '邮箱必须填写']),
            FormItem::text('phone','手机号')->rules(['required'], ['required' => '手机号必须填写']),
            FormItem::radio('sex','性别')->options([1 => '男', 2 => '女'])->value(1),
            FormItem::password('password','密码')->rules(['required'], ['required'=>'密码必须填写']),
            FormItem::switch('status','状态')->options(['on'  => '正常','off' => '禁用'])->value(true)
        ];
    }
}