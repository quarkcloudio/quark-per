<?php

namespace QuarkCMS\QuarkAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use QuarkCMS\QuarkAdmin\Helper;
use QuarkCMS\QuarkAdmin\Models\Admin;
use QuarkCMS\QuarkAdmin\Form;
use Spatie\Permission\Models\Role;
use Quark;
use Validator;
use DB;

class AdminController extends QuarkController
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
        $grid = Quark::grid(new Admin)->title($this->title);
        $grid->column('username','用户名');
        $grid->column('nickname','昵称');
        $grid->column('email','邮箱');
        $grid->column('actions','操作')->width(100);
        $grid->model()
        ->where('status',1)
        ->select('id as key','id','username','nickname','email')
        ->orderBy('id','desc')
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
        $form = Quark::form(new Admin);

        $title = $form->isCreating() ? '创建'.$this->title : '编辑'.$this->title;
        $form->title($title);
        
        $form->id('id','ID');

        $form->text('username','用户名')
        ->width(200)
        ->rules(['required','min:6','max:20'],['required'=>'用户名必须填写','min'=>'用户名不能少于6个字符','max'=>'用户名不能超过20个字符'])
        ->creationRules(["unique:admins"],['unique'=>'用户名已经存在'])
        ->updateRules(["unique:admins,username,{{id}}"],['unique'=>'用户名已经存在']);

        $form->text('nickname','昵称')
        ->width(200)
        ->rules(['required','max:20'],['required'=>'昵称必须填写','max'=>'昵称不能超过20个字符']);

        $form->radio('sex','性别')->options(['1' => '男', '2'=> '女'])->default('1');

        $form->text('email','邮箱')
        ->width(200)
        ->rules(['required','email','max:255'],['required'=>'邮箱必须填写','email'=>'邮箱格式错误','max'=>'邮箱不能超过255个字符'])
        ->creationRules(["unique:admins"],['unique'=>'邮箱已经存在',])
        ->updateRules(["unique:admins,email,{{id}}"],['unique'=>'邮箱已经存在']);

        $form->text('phone','手机号')
        ->width(200)
        ->rules(['required','max:11'],['required'=>'手机号必须填写','max'=>'手机号不能超过11个字符'])
        ->creationRules(["unique:admins"],['unique'=>'手机号已经存在'])
        ->updateRules(["unique:admins,phone,{{id}}"],['unique'=>'手机号已经存在']);

        $form->text('password','密码')
        ->width(200)
        ->rules(['min:6','max:255'],['min'=>'密码不能少于6个字符','max'=>'密码不能超过255个字符'])
        ->creationRules(["required"],['required'=>'密码不能为空']);

        //保存前回调
        $form->saving(function ($form) {
            if(isset($form->request['password'])) {
                $form->request['password'] = bcrypt($form->request['password']);
            }
        });

        return $form;
    }
}
