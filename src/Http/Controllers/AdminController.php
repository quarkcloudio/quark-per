<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Models\Admin;
use Spatie\Permission\Models\Role;
use QuarkCMS\QuarkAdmin\Http\Resources\AdminIndexResource;
use Illuminate\Routing\Controller;

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
        $pageSize = request('pageSize',1);
        $username = request('username');

        $query = Admin::query();

        if($username) {
            $query->where('username',$username);
        }

        $list = $query->paginate($pageSize);

        return AdminIndexResource::view($list);
    }
}
