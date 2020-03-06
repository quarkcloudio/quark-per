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
        $grid->column('picture.path','头像')->image();
        $grid->column('username','用户名')->link();
        $grid->column('nickname','昵称');
        $grid->column('email','邮箱')->qrcode();
        $grid->column('status','状态')->using(['1' => '正常', '2' => '已禁用'])->tag(['1'=>'default','2'=>'warning']);
        $grid->column('actions','操作')->width(100);

        $grid->search(function($search){
            $search->equal('status', '所选状态')->select([''=>'全部',1=>'正常',2=>'已禁用'])->placeholder('请选择状态')->width(120);
            $search->equal('username', '用户名');
        });

        $grid->advancedSearch(function($search){
            $search->between('created_at', '创建时间')->datetime();
        });

        $grid->model()
        ->select('id as key','admins.*')
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

        $form->radio('sex','性别')->options(['1' => '男', '2'=> '女'])->default(1);

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

    /**
     * 详情页面
     * 
     * @param  Request  $request
     * @return Response
     */
    protected function detail($id)
    {
        $show = Quark::show(Admin::findOrFail($id))->title('详情页');

        $show->field('id','ID');
        $show->field('username','用户名');
        $show->field('nickname','昵称');
        $show->field('sex','性别');
        $show->field('status','状态');

        //渲染前回调
        $show->rendering(function ($show) {
            $show->data['sex'] == 1 ? $show->data['sex'] = '男' : $show->data['sex'] = '女';
            $show->data['status'] == 1 ? $show->data['status'] = '正常' : $show->data['status'] = '已禁用';
        });

        return $show;
    }
}
