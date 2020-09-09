<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use QuarkCMS\QuarkAdmin\Models\Admin;
use Validator;
use Hash;

class AccountController extends Controller
{
    /**
     * 获取账号信息
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function info()
    {
        $admin = Admin::where('id',ADMINID)->first();

        // 获取用户信息
        if(!empty($admin)) {
            // 获取用户头像
            if(!empty($admin['avatar'])) {
                $admin['avatar'] = get_picture($admin['avatar']);
            } else {
                $admin['avatar'] = null;
            }
            return success('获取成功！','',$admin);
        } else {
            return error('获取失败！');
        }
    }

    /**
     * 修改账号信息
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function profile(Request $request)
    {
        $requestJson    =   $request->getContent();
        $requestData    =   json_decode($requestJson,true);
        unset($requestData['actionUrl']);

        // 表单验证错误提示信息
        $messages = [
            'required' => '必填',
            'max' => '最大长度不超过255位',
            'email' => '格式无效',
            'unique' => '已经存在',
        ];

        // 表单验证规则
        $rules = [
            'nickname' => ['required','max:255',Rule::unique('admins')->ignore(ADMINID)],
            'email' =>  ['required','email','max:255',Rule::unique('admins')->ignore(ADMINID)],
        ];

        // 进行验证
        $validator = Validator::make($requestData,$rules,$messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();

            foreach($errors as $key => $value) {
                if($key === 'nickname') {
                    $errorMsg = '昵称'.$value[0];
                }
                if($key === 'email') {
                    $errorMsg = '邮箱'.$value[0];
                }
            }

            return error($errorMsg);
        }

        $result = Admin::where('id',ADMINID)->update($requestData);

        if(!empty($result)) {
            return success('操作成功！');
        } else {
            return error('操作失败！');
        }
    }

    /**
     * 修改账号密码
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function password(Request $request)
    {
        $requestJson    =   $request->getContent();
        $requestData    =   json_decode($requestJson,true);
        unset($requestData['actionUrl']);

        // 表单验证错误提示信息
        $messages = [
            'required' => '必填',
            'max' => '最大长度不超过255位',
            'min' => '不得小于6位',
            'same' => '与密码输入的不一致',
        ];

        // 表单验证规则
        $rules = [
            'oldPassword' => ['required','max:255'],
            'password' => ['required','max:255','min:6'],
            'repassword' => ['required','max:255',"same:password"],
        ];

        // 进行验证
        $validator = Validator::make($requestData,$rules,$messages);

        if ($validator->fails()) {
            $errors = $validator->errors()->getMessages();

            foreach($errors as $key => $value) {
                if($key === 'oldPassword') {
                    $errorMsg = '原密码'.$value[0];
                }
                if($key === 'password') {
                    $errorMsg = '密码'.$value[0];
                }
                if($key === 'repassword') {
                    $errorMsg = '确认密码'.$value[0];
                }
            }

            return error($errorMsg);
        }

        $adminInfo = Admin::where('id',ADMINID)->first();

        if(!Hash::check($requestData['oldPassword'], $adminInfo->password)) {
            return error('原密码错误！');
        }

        $result = Admin::where('id',ADMINID)->update(['password' => bcrypt($requestData['password'])]);

        if(!empty($result)) {
            return success('操作成功！');
        } else {
            return error('操作失败！');
        }
    }
}