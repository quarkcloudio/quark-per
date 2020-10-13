<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Models\ActionLog;
use QuarkCMS\QuarkAdmin\Models\Admin;
use QuarkCMS\QuarkAdmin\Table;
use QuarkCMS\QuarkAdmin\Action;
use App\User;
use Quark;

class ActionLogController extends Controller
{
    public $title = '日志列表';

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
        
        $table->column('id','序号');
        $table->column('admin.username','用户')->width(200);
        $table->column('url','行为')->width(200);
        $table->column('ip','IP');
        $table->column('created_at','发生时间');
        $table->column('actions','操作')->width(180)->actions(function($row) {

            // 创建行为对象
            $action = new Action();

            $action->a('删除')
            ->withPopconfirm('确认要删除数据吗？')
            ->model()
            ->where('id','{id}')
            ->delete();

            return $action;
        });

        // 批量操作
        $table->batchActions(function($action) {
            // 跳转默认编辑页面
            $action->a('批量删除')
            ->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！')
            ->model()
            ->whereIn('id','{ids}')
            ->delete();

            return $action;
        });

        // 搜索
        $table->search(function($search) {
            $search->where('name', '搜索内容',function ($query) {
                $query->where('name', 'like', "%{input}%");
            })->placeholder('名称');
        });

        $table->model()
        ->where('type','ADMIN')
        ->orderBy('id','desc')
        ->paginate(request('pageSize',10));

        return $table;
    }

    /**
     * 详情页面
     * 
     * @param  Request  $request
     * @return Response
     */
    protected function detail($id)
    {
        $show = Quark::show(ActionLog::findOrFail($id)->toArray())->title('详情页')->disableEdit();

        $show->field('id','ID');
        $show->field('object_id','用户名');
        $show->field('url','行为URL');
        $show->field('ip','IP');
        $show->field('created_at','发生时间');
        $show->field('remark','备注');

        //渲染前回调
        $show->rendering(function ($show) {
            if($show->data['object_id']) {
                if($show->data['type'] == 'ADMIN') {
                    $admin = Admin::where('id',$show->data['object_id'])->first();
                    $show->data['object_id'] = $admin['username'];
                } else {
                    $user = User::where('id',$show->data['object_id'])->first();
                    $show->data['object_id'] = $user['username'];
                }
            } else {
                $show->data['object_id'] = '未知用户';
            }
        });

        return $show;
    }
}
