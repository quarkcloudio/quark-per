<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use QuarkCMS\QuarkAdmin\Table;
use Route;

class PermissionController extends Controller
{
    public $title = '权限';

    /**
     * 列表页面
     *
     * @param  Request  $request
     * @return Response
     */
    protected function table()
    {
        $table = new Table(new Permission);
        $table->headerTitle($this->title.'列表');
        
        $table->column('id','序号');
        $table->column('name','名称');
        $table->column('guard_name','guard名称');
        $table->column('created_at','创建时间');
        $table->column('actions','操作')->width(180)->actions(function($action,$row) {

            $action->a('删除')
            ->withPopconfirm('确认要删除吗？')
            ->model()
            ->where('id','{id}')
            ->delete();

            return $action;
        });

        $table->toolBar()->actions(function($action) {

            // 跳转默认创建页面
            $action->button('同步权限')->type('primary')->api('admin/permission/sync');

            $action->button('清空权限')
            ->withConfirm('确认要清空吗？','清空后数据将无法恢复，请谨慎操作！')
            ->model()
            ->where('guard_name','admin')
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
        });

        // 搜索
        $table->search(function($search) {

            $search->where('name', '搜索内容',function ($model) {
                $model->where('name', 'like', "%{input}%");
            })->placeholder('名称');

            $search->between('created_at', '创建时间')->datetime();
        });

        $table->model()->orderBy('id','desc')->paginate(request('pageSize',10));

        return $table;
    }

    /**
     * 同步权限
     *
     * @param  Request  $request
     * @return Response
     */
    public function sync(Request $request)
    {
        $routes = Route::getRoutes();

        foreach($routes as $route) {
            // 只处理后台接口
            if(strpos($route->uri,'api/admin') !== false) {
                $hasPermission = Permission::where('name',$route->uri)->first();
                if(empty($hasPermission)) {
                    $data['menu_id'] = 0;
                    $data['name'] = $route->uri;
                    $data['guard_name'] = 'admin';
                    $result = Permission::create($data);
                }
                $urls[] = $route->uri;
            }
        }

        // 清除废弃url
        Permission::whereNotIn('name',$urls)->delete();

        return success('操作成功！');
    }
}
