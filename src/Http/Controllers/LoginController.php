<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use QuarkCMS\QuarkAdmin\Login;
use QuarkCMS\QuarkAdmin\Models\Admin;

class LoginController extends Controller
{
    /**
     * 登录展示
     *
     * @param  Login  $login
     * @return array
     */
    public function show(Login $login)
    {
        return $login->resource();
    }

    /**
     * 登录方法
     * @author  tangtanglove <dai_hang_love@126.com>
     */
    public function login(Request $request, Login $login)
    {
        // 账号登录
        $username = $request->json('username');
        $password = $request->json('password');
        $captcha = $request->json('captcha');

        if(config('admin.captchaUrl') !== false && captcha_validate($captcha) === false) {
            return error('验证码错误！');
        }

        if(empty($username)) {
            return error('用户名不能为空！');
        }

        if(empty($password)) {
            return error('密码不能为空！');
        }

        $loginResult = Auth::guard('admin')->attempt(['username' => $username, 'password' => $password]);

        if($loginResult) {

            $user = Auth::guard('admin')->user();

            if(intval($user['status']) !== 1) {
                return error('用户被禁用！');
            }

            // 更新登录信息
            Admin::where('id',$user->id)->first()->updateLastLoginInfo();

            $result['id'] = $user->id;
            $result['username'] = $user->username;
            $result['nickname'] = $user->nickname;
            $result['avatar'] = get_picture($user->avatar);
            $result['token'] = Str::random(950);

            // 将认证信息写入缓存，这里用hack方法做后台api登录认证
            cache([$result['token'] => $result],60*60*3);

            return success('登录成功！','',$result);
        } else {

            if(config('admin.captchaUrl') !== false) {
                // 清除验证码
                clear_captcha();
            }

            return error('用户名或密码错误！');
        }
    }

    /**
     * 用户退出方法
     * @author  tangtanglove <dai_hang_love@126.com>
     */
    public function logout(Request $request)
    {
        $authorization = $request->header('Authorization');
        $token = explode(' ',$authorization);
        $result = Auth::guard('admin')->logout();

        if($result !== false) {

            cache([$token[1] => null]);

            return success('已退出！');
        } else {
            return error('错误！');
        }
    }
}
