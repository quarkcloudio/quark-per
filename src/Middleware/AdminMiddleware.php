<?php

namespace QuarkCMS\QuarkAdmin\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use QuarkCMS\QuarkAdmin\Models\Admin;
use Illuminate\Support\Facades\Route;
use QuarkCMS\QuarkAdmin\Helper;

class AdminMiddleware
{
    public function handle($request, Closure $next, $guard = '')
    {

        if ($this->shouldPassThrough($request)) {
            return $next($request);
        }

        // get方式获取token
        $token = $request->get('token');

        // 得到认证凭据
        $authorization = $request->header('Authorization');

        if(empty($token)) {
            if(empty($authorization)) {
                return response('Unauthorized.', 401);
            }
            $authorizations = explode(' ',$authorization);
            $token = $authorizations[1];
        }

        if (empty($token)) {
            return response('Unauthorized.', 401);
        }

        // 根据token获取登录用户信息
        $admin = cache($token);

        // 获取不到则重新登录
        if (empty($admin)) {
            return response('Unauthorized.', 401);
        }

        define('ADMINID',$admin['id']);

        // 记录所有行为日志
        Helper::actionLog($admin['id'],'','ADMIN');

        if($admin['id'] !== 1) {

            $getPermissions = Admin::where('id',$admin['id'])->first()->getPermissionsViaRoles();
            $hasPermission = false;
            foreach ($getPermissions as $key => $value) {
                if ($value->name == Route::currentRouteName()) {
                    $hasPermission = true;
                }
            }

            if(!$hasPermission) {
                return response('无权限！', 403);
            }
        }

        return $next($request);
    }

    /**
     * Determine if the request has a URI that should pass through verification.
     *
     * @param \Illuminate\Http\Request $request
     *
     * @return bool
     */
    protected function shouldPassThrough($request)
    {
        $excepts = config('quark.auth.excepts', [
            'admin/login',
            'admin/logout',
        ]);

        $result = false;
        foreach ($excepts as $key => $except) {
            if($request->is($except)) {
                $result = true;
            }
        }

        return $result;
    }
}