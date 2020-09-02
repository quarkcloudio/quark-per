<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Layout;

class LayoutController extends Controller
{
    /**
     * 获取layout布局
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function layout(Request $request)
    {
        $layout = new Layout;

        $layout->title(config('admin.name'));
        $layout->logo(config('admin.logo'));
        $layout->layout(config('admin.layout'));

        return success('获取成功！',null,$layout);
    }
}
