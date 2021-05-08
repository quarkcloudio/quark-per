<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Models\Admin;
use Spatie\Permission\Models\Role;
use QuarkCMS\QuarkAdmin\Http\Resources\AdminIndexResource;
use QuarkCMS\QuarkAdmin\Http\Resources\AdminCreateResource;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Validator;

class AdminController extends Controller
{
    /**
     * 列表页面
     *
     * @param  Request  $request
     * @return Response
     */
    public function index(Request $request)
    {
        $list = Admin::withQuerys()->paginate(request('pageSize',10));

        return AdminIndexResource::view($list);
    }

    /**
     * 创建页面
     *
     * @param  Request  $request
     * @return Response
     */
    public function create(Request $request)
    {
        return AdminCreateResource::view();
    }

    /**
     * 创建操作
     *
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $data = $request->all();

        if(empty($data['username'])) {
            return error('用户名不能为空！');
        }

        if(empty($data['password'])) {
            return error('密码不能为空！');
        }

        Validator::make($data, [
            'username' => [
                'required'
            ],
            'email' => [
                'required',
                Rule::exists('staff')->where(function ($query) {
                    $query->where('account_id', 1);
                }),
            ],
        ]);
        
        // 将头像转化为数组
        $data['avatar'] = json_encode($data['avatar']);

        $result = Admin::create($data);

        if($result) {
            return success('操作成功！');
        } else {
            return error('操作失败，请重试！');
        }
    }

    /**
     * 删除操作
     *
     * @param  Request  $request
     * @return Response
     */
    public function delete(Request $request)
    {
        $id = $request->get('id');

        if(empty($id)) {
            return error('请选择要删除的数据！');
        }

        $result = Admin::where('id',$id)->delete();

        if($result) {
            return success('操作成功！');
        } else {
            return error('操作失败，请重试！');
        }
    }

    /**
     * 改变状态
     *
     * @param  Request  $request
     * @return Response
     */
    public function changeStatus(Request $request)
    {
        $id = $request->get('id');
        $status = $request->get('status');

        if(empty($id)) {
            return error('请选择要删除的数据！');
        }

        if($status == 1) {
            $data['status'] = 0;
        } else {
            $data['status'] = 1;
        }

        $result = Admin::where('id',$id)->update($data);

        if($result) {
            return success('操作成功！');
        } else {
            return error('操作失败，请重试！');
        }
    }
}
