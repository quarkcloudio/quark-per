<?php

namespace QuarkCMS\QuarkAdmin\Http\Middleware;

use Closure;
use QuarkCMS\QuarkAdmin\Models\Admin;
use Illuminate\Support\Facades\Route;
use QuarkCMS\QuarkAdmin\Exceptions\AuthenticationException;

class AdminMiddleware
{
    public function handle($request, Closure $next, $guard = '')
    {
        // get方式获取token
        $token = $request->get('token');

        // 得到认证凭据
        $authorization = $request->header('Authorization');

        if(empty($token)) {
            if(empty($authorization)) {
                throw new AuthenticationException('Unauthenticated.');
            }
            
            $authorizations = explode(' ',$authorization);

            if(!isset($authorizations[1])) {
                throw new AuthenticationException('Unauthenticated.');
            }

            $token = $authorizations[1];
        }

        if (empty($token)) {
            throw new AuthenticationException('Unauthenticated.');
        }

        // 根据token获取登录用户信息
        $admin = cache($token);

        // 获取不到则重新登录
        if (empty($admin)) {
            throw new AuthenticationException('Unauthenticated.');
        }

        define('ADMINID',$admin['id']);

        // 记录所有行为日志
        action_log($admin['id'],'','ADMIN');

        if($admin['id'] !== 1) {

            $getPermissions = Admin::where('id',$admin['id'])->first()->getPermissionsViaRoles();
            $hasPermission = false;
            foreach ($getPermissions as $key => $value) {
                if ($value->name == \request()->path()) {
                    $hasPermission = true;
                }
            }

            if(!$hasPermission) {
                return response('无权限！', 403);
            }
        }

        return $next($request);
    }
}