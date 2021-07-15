<?php

namespace App\Admin\Resources;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Field;
use QuarkCMS\QuarkAdmin\Resource;

class Account extends Resource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public static $title = '个人设置';

    /**
     * 模型
     *
     * @var string
     */
    public static $model = 'QuarkCMS\QuarkAdmin\Models\Admin';

    /**
     * 表单接口
     *
     * @param  Request  $request
     * @return string
     */
    public function formApi($request)
    {
        return (new \App\Admin\Actions\ChangeAccount)->api();
    }

    /**
     * 字段
     *
     * @param  Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Field::image('avatar','头像'),

            Field::text('username','用户名')
            ->rules(
                [
                    'required',
                    'min:6',
                    'max:20',
                    'unique:admins,username,' . ADMINID
                ],
                [
                    'required' => '用户名必须填写',
                    'min' => '用户名不能少于6个字符',
                    'max' => '用户名不能超过20个字符',
                    'unique'=>'用户名已存在'
                ]
            ),

            Field::text('nickname','昵称')
            ->rules(['required'], ['required' => '昵称必须填写']),
            
            Field::text('email','邮箱')
            ->rules(
                ['required','unique:admins,email,' . ADMINID],
                ['required'=>'邮箱必须填写','unique'=>'邮箱已存在']
            ),

            Field::text('phone','手机号')
            ->rules(
                ['required','unique:admins,phone,' . ADMINID],
                ['required' => '手机号必须填写','unique' => '手机号已存在']
            ),

            Field::radio('sex','性别')
            ->options([1 => '男', 2 => '女'])
            ->default(1),

            Field::password('password','密码')
        ];
    }

    /**
     * 表单显示前回调
     * 
     * @param Request $request
     * @return array
     */
    public function beforeCreating(Request $request)
    {
        $adminInfo = $this->newModel()->where('id', ADMINID)->first();

        unset($adminInfo['password']);

        return $adminInfo;
    }

    /**
     * 注册行为，注册后才能被资源调用
     *
     * @param  Request  $request
     * @return object
     */
    public function actions(Request $request)
    {
        return [
            new \App\Admin\Actions\ChangeAccount
        ];
    }
}