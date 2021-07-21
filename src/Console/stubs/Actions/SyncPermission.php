<?php

namespace App\Admin\Actions;

use QuarkCMS\QuarkAdmin\Actions\Action;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Permission;
use Route;

class SyncPermission extends Action
{
    /**
     * 行为名称
     *
     * @var string
     */
    public $name = '同步权限';

    /**
     * 执行行为
     *
     * @param  Fields  $fields
     * @param  Collection  $models
     * @return mixed
     */
    public function handle($fields, $models)
    {
        $routes = Route::getRoutes();
        $permissions = [];

        foreach($routes as $route) {
            // 只处理后台接口
            if(strpos($route->uri,'api/admin') !== false && strpos($route->uri,'-form') === false && strpos($route->uri,'/form') === false) {
                if((strpos($route->uri,'{resource}') !== false) || (strpos($route->uri,'{dashboard}') !== false)) {
                    if(strpos($route->uri,'{resource}') !== false) {
                        $resourceUrls[] = $route->uri;
                    } else {
                        $dashboardUrls[] = $route->uri;
                    }
                } else {
                    $permissions[] = $route->uri;
                }
            }
        }

        $permissions = array_merge($permissions, $this->getResourcePermissions($resourceUrls));
        $permissions = array_merge($permissions, $this->getDashboardPermissions($dashboardUrls));

        $data = [];

        foreach($permissions as $permission) {
            $hasPermission = Permission::where('name', $permission)->first();
            if(empty($hasPermission)) {
                $data[] = [
                    'menu_id' => 0,
                    'name' => $permission,
                    'guard_name' => 'admin',
                    'created_at' => date('Y-m-d H:i:s'),
                    'updated_at' => date('Y-m-d H:i:s')
                ];
            }
        }

        $result = Permission::insert($data);

        // 清除废弃url
        Permission::whereNotIn('name',$permissions)->delete();

        if($result) {
            return success('同步成功！');
        } else {
            return error('同步失败，请重试！');
        }
    }

    /**
     * 获取资源权限
     *
     * @param  array  $resourceUrls
     * @return mixed
     */
    protected function getResourcePermissions($resourceUrls)
    {
        $resourcesPath = app_path('Admin/Resources');
        $resources = get_folder_files($resourcesPath);
        $permissions = [];

        foreach ($resources as $value) {

            $resourceName = Str::replaceLast('.php', '', $value);

            foreach ($resourceUrls as $resourceUrl) {
                $resourceUrl = str_replace('{resource}', lcfirst($resourceName), $resourceUrl);

                // 行为权限
                if(strpos($resourceUrl,'{uriKey}') !== false) {
                    $permissions = array_merge($permissions, $this->getActionPermissions($resourceName, $resourceUrl));
                } else {
                    $permissions[] = $resourceUrl;
                }
            }
        }
        
        return $permissions ?? [];
    }

    /**
     * 获取仪表盘权限
     *
     * @param  array  $dashboardUrls
     * @return mixed
     */
    protected function getDashboardPermissions($dashboardUrls)
    {
        $dashboardsPath = app_path('Admin/Dashboards');
        $dashboards = get_folder_files($dashboardsPath);

        foreach ($dashboards as $key => $value) {

            $dashboardName = Str::replaceLast('.php', '', $value);

            foreach ($dashboardUrls as $dashboardUrl) {
                $permissions[] = str_replace('{dashboard}', lcfirst($dashboardName), $dashboardUrl);
            }
        }
        
        return $permissions ?? [];
    }

    /**
     * 获取行为权限
     *
     * @param  string  $resourceName
     * @param  string  $resourceUrl
     * @return mixed
     */
    protected function getActionPermissions($resourceName, $resourceUrl)
    {
        $resourceClass = 'App\\Admin\\Resources\\' . $resourceName;
        $newResource = new $resourceClass($resourceClass::newModel());

        foreach ($newResource->actions(request()) as $value) {
            $permissions[] = str_replace('{uriKey}', $value->uriKey(), $resourceUrl);
        }

        return $permissions ?? [];
    }
}