<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Models\Admin;
use QuarkCMS\QuarkAdmin\Table;
use QuarkCMS\QuarkAdmin\Action;

class AdminController extends Controller
{
    public $title = '管理员列表';

    /**
     * 列表页面
     *
     * @param  Request  $request
     * @return Response
     */
    protected function table()
    {
        $table = new Table(new Admin);
        $table->headerTitle($this->title);
        
        $table->column('id','ID');
        $table->column('avatar','头像')->image();
        $table->column('username','用户名')->qrcode();
        $table->column('nickname','昵称')->editable()->width('12%');
        $table->column('email','邮箱')->editable()->width('12%');
        $table->column('sex','性别')->using(['1'=>'男','2'=>'女'])->filters(['1'=>'男','2'=>'女'])->width(80);
        $table->column('phone','手机号')->sorter()->width(100);
        $table->column('last_login_time','最后登录时间');
        $table->column('status','状态')->editable('switch',[
            'on'  => ['value' => 1, 'text' => '正常'],
            'off' => ['value' => 0, 'text' => '禁用']
        ])->width(100);

        $table->column('actions','操作')
        ->width(100)
        ->actions(function($row) {
            $action = new Action();

            if($row['status'] == 1) {
                $action->button('禁用')->model()->update(['status'=>2]);
            } else {
                $action->button('启用')->model()->update(['status'=>1]);
            }

            $action->button('编辑')->link();
            $action->button('审核')->model()->update(['status'=>1]);

            // $action->menu('更多')->options(function($option) {
            //     $option->name('下载')->download();
            //     $option->name('删除')->model()
            //     ->delete()
            //     ->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！');
            // });

            // $action->button('查看')->modalForm('查看详情')->api('admin/menu/edit?id={id}');

            return $action;
        });

        $table->model()->paginate(request('pageSize',10));

        return $table;
    }
}
