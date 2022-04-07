<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Layout;
use QuarkCMS\Quark\Facades\Page;

class PagesController extends Controller
{
    use Layout;

    /**
     * 直接加载前端组件
     *
     * @return array
     */
    public function handle(Request $request)
    {
        $data["component"] = request()->route('component');
        $layout = $this->layoutComponentRender($request,$data);

        // 页面
        return Page::style(['height' => '100vh'])->body($layout);
    }
}