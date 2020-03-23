<?php

namespace QuarkCMS\QuarkAdmin\Controllers;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Helper;
use Spatie\Permission\Models\Permission;
use Quark;
use Route;

class PermissionController extends QuarkController
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
        $grid = Quark::grid(new Permission)->title($this->title);
        $grid->column('name','名称');
        $grid->column('guard_name','guard名称');
        $grid->column('created_at','创建时间');

        $grid->column('actions','操作')->width(100)->rowActions(function($rowAction) {
            $rowAction->menu('delete', '删除')->model(function($model) {
                $model->delete();
            })->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！');
        });

        // 头部操作
        $grid->actions(function($action) {
            $action->button('sync', '同步')->type('primary')->setAction(url('api/admin/permission/sync'));
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

        $grid->disableAdvancedSearch();

        $grid->model()->paginate(10);

        return $grid;
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
                    $data['name'] = $route->uri;
                    $data['guard_name'] = 'admin';
                    $result = Permission::create($data);
                }
                $urls[] = $route->uri;
            }
        }

        // 清除废弃url
        Permission::whereNotIn('name',$urls)->delete();

        return $this->success('操作成功！');
    }
}
