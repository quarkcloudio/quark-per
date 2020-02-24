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
        $form->text('username','用户名')->width(200);
        $form->text('nickname','昵称')->width(200);
        $form->text('email','邮箱')->width(200);
        $form->text('phone','手机号')->width(200);
        $form->text('password','密码')->width(200);

        //保存前回调
        $form->saving(function ($form) {
            $form->request['password'] = bcrypt($form->request['password']);
        });

        return $form;
    }
}
