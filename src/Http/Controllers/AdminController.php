<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Models\Admin;
use QuarkCMS\QuarkAdmin\Table;
use QuarkCMS\QuarkAdmin\Form;
use Spatie\Permission\Models\Role;
use QuarkCMS\QuarkAdmin\Http\Resources\AdminResource;
use Illuminate\Routing\Controller;

class AdminController extends Controller
{
    /**
     * 列表页面
     *
     * @param  Request  $request
     * @return Response
     */
    public function index()
    {
        return AdminResource::view();
    }
}
