<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Models\ActionLog;
use QuarkCMS\QuarkAdmin\Table;

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
        $table = new Table(new ActionLog);
        $table->headerTitle($this->title);
        
        $table->column('id','头像');
        $table->column('username','用户名')->link();
        $table->column('nickname','昵称')->editable()->width('12%');
        $table->column('email','邮箱')->editable()->width('12%');
        $table->column('sex','性别')->using(['1'=>'男','2'=>'女'])->filters(['1'=>'男','2'=>'女'])->width(80);
        $table->column('phone','手机号')->sorter()->width(100);
        $table->column('last_login_time','最后登录时间');
        $table->column('status','状态')->editable('switch',[
            'on'  => ['value' => 1, 'text' => '正常'],
            'off' => ['value' => 0, 'text' => '禁用']
        ])->width(100);

        $table->model()->paginate(request('pageSize',10));

        return $table;
    }
}
