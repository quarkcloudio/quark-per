<?php

namespace QuarkCMS\QuarkAdmin\Controllers;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Models\ActionLog;
use QuarkCMS\QuarkAdmin\Models\Admin;
use App\User;
use Quark;
use Route;

class ActionLogController extends QuarkController
{
    public $title = '日志';

    /**
     * 列表页面
     *
     * @param  Request  $request
     * @return Response
     */
    protected function table()
    {
        $grid = Quark::grid(new ActionLog)
        ->title($this->title);

        $grid->column('admin.username','用户')->width(200);
        $grid->column('url','行为');
        $grid->column('ip','IP');
        $grid->column('created_at','发生时间');
        $grid->column('actions','操作')->width(100)->rowActions(function($rowAction) {
            $rowAction->menu('show', '显示');
            $rowAction->menu('delete', '删除')->model(function($model) {
                $model->delete();
            })->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！');
        });

        // 头部操作
        $grid->actions(function($action) {
            $action->button('refresh', '刷新');
        });

        // select样式的批量操作
        $grid->batchActions(function($batch) {
            $batch->option('', '批量操作');
            $batch->option('delete', '删除')->model(function($model) {
                $model->delete();
            })->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！');
        })->style('select',['width'=>120]);

        $grid->search(function($search) {
            $search->where('name', '搜索内容',function ($query) {
                $query->where('name', 'like', "%{input}%");
            })->placeholder('名称');
        })->expand(false);

        $grid->model()
        ->where('type','ADMIN')
        ->paginate(10);

        return $grid;
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
