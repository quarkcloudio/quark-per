<?php

namespace App\Admin\Resources;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Resource;
use QuarkCMS\QuarkAdmin\Field;
use Spatie\Permission\Models\Role;

class Admin extends Resource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public $title = '管理员';

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
    public static $pagination = 10;

    /**
     * 字段
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        $roles = $this->getRoles();

        return [
            Field::image('avatar','头像')->onlyOnForms(),
            Field::text('username','用户名')->rules(
                ['required','min:6','max:20'],
                ['required' => '用户名必须填写','min' => '用户名不能少于6个字符','max' => '用户名不能超过20个字符']
            ),
            Field::checkbox('role_ids','角色')->options($roles)->onlyOnForms(),
            Field::text('nickname','昵称')->rules(['required'], ['required' => '昵称必须填写']),
            Field::text('email','邮箱')->rules(['required'], ['required' => '邮箱必须填写']),
            Field::text('phone','手机号')->rules(['required'], ['required' => '手机号必须填写']),
            Field::radio('sex','性别')->options([1 => '男', 2 => '女'])->default(1),
            Field::password('password','密码')->rules(['required'], ['required'=>'密码必须填写'])->onlyOnForms(),
            Field::datetime('last_login_time','最后登录时间')->onlyOnIndex(),
            Field::switch('status','状态')->options(['on'  => '正常','off' => '禁用'])->default(true),
        ];
    }

    /**
     * 获取角色
     *
     * @param  void
     * @return array
     */
    protected function getRoles()
    {
        $getRoles = Role::where('guard_name','admin')->get()->toArray();
        $roles = [];

        foreach ($getRoles as $key => $role) {
            $roles[$role['id']] = $role['name'];
        }

        return $roles;
    }

    /**
     * 过滤器
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function filters(Request $request)
    {
        return [
            new Filters\InputFilter('username'),
            new Filters\InputFilter('nickname')
        ];
    }

    /**
     * 查询
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Laravel\Scout\Builder  $query
     * @return \Laravel\Scout\Builder
     */
    public static function scoutQuery(Request $request, $query)
    {
        return $query;
    }

    /**
     * 行为
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [

        ];
    }
}