<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use QuarkCMS\QuarkAdmin\Http\Resources\PermissionIndexResource;
use Route;

class PermissionController extends Controller
{
    /**
     * 列表页面
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        $name = request('name');

        $query = Permission::query();

        if(!empty($name)) {
            $query = $query->where('name', 'like', "%$name%");
        }

        $list = $query->paginate(request('pageSize',10));

        return PermissionIndexResource::view($list);
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

    /**
     * 清空权限
     *
     * @param  Request  $request
     * @return Response
     */
    public function clear(Request $request)
    {
        $result = Permission::where('guard_name', 'admin')->delete();

        if($result) {
            return success('操作成功！');
        } else {
            return error('操作失败！');
        }
    }
}
