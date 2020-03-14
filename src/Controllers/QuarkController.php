<?php

namespace QuarkCMS\QuarkAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Input;
use Quark;
use Str;

class QuarkController extends Controller
{
    protected $title = '默认页面';

    protected $subTitle = false;

    protected $description = false;

    protected $breadcrumb = false;

    /**
     * Get content title.
     *
     * @return string
     */
    protected function title()
    {
        return $this->title;
    }

    /**
     * Get content subTitle.
     *
     * @return string
     */
    protected function subTitle()
    {
        return $this->subTitle;
    }

    /**
     * Get content description.
     *
     * @return string
     */
    protected function description()
    {
        return $this->description;
    }

    /**
     * Get content breadcrumb.
     *
     * @return string
     */
    protected function breadcrumb()
    {
        return $this->breadcrumb;
    }

    /**
     * 列表页面
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $table = $this->table();

        $content = Quark::content()
        ->title($this->title())
        ->subTitle($this->subTitle())
        ->description($this->description())
        ->breadcrumb($this->breadcrumb())
        ->body($table->render());

        return $this->success('获取成功！','',$content);
    }

    /**
     * 详情页面
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request)
    {
        $id = $request->get('id');

        if(empty($id)) {
            return $this->error('参数错误！');
        }

        $show = $this->detail($id);

        $content = Quark::content()
        ->title($this->title())
        ->subTitle($this->subTitle())
        ->description($this->description())
        ->breadcrumb($this->breadcrumb())
        ->body($show->render());

        return $this->success('获取成功！','',$content);

    }

    /**
     * 创建页面
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $form = $this->form();

        $content = Quark::content()
        ->title($this->title())
        ->subTitle($this->subTitle())
        ->description($this->description())
        ->breadcrumb($this->breadcrumb())
        ->body(['form'=>$form->render()]);

        return $this->success('获取成功！','',$content);
    }

    /**
     * 创建数据方法
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $result = $this->form()->store();

        $action = \request()->route()->getName();
        $action = Str::replaceFirst('api/','',$action);
        $action = Str::replaceLast('/store','/index',$action);

        $url = "/quark/engine?api=".$action."&component=table";

        if($result['status'] == 'success') {
            return $this->success('操作成功！',$url);
        } else {
            return $this->error($result['msg']);
        }
    }

    /**
     * 编辑页面
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request)
    {
        $id = $request->get('id');

        if(empty($id)) {
            return $this->error('参数错误！');
        }

        $form = $this->form()->edit($id);

        $content = Quark::content()
        ->title($this->title())
        ->subTitle($this->subTitle())
        ->description($this->description())
        ->breadcrumb($this->breadcrumb())
        ->body(['form'=>$form->render()]);

        return $this->success('获取成功！','',$content);
    }

    /**
     * 更新数据方法
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $result = $this->form()->update();

        $action = \request()->route()->getName();
        $action = Str::replaceFirst('api/','',$action);
        $action = Str::replaceLast('/update','/index',$action);

        $url = "/quark/engine?api=".$action."&component=table";

        if($result['status'] == 'success') {
            return $this->success('操作成功！',$url);
        } else {
            return $this->error($result['msg']);
        }
    }

    /**
     * 列表action方法
     *
     * @param  Request  $request
     * @return Response
     */
    public function action(Request $request)
    {
        // 定义对象
        $result = $this->table()->action();

        if($result['status'] == 'success') {
            return $this->success('操作成功！');
        } else {
            return $this->error($result['msg']);
        }
    }

    /**
     * 删除数据方法
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $result = $this->form()->destroy($id);

        if($result['status'] == 'success') {
            return $this->success('删除成功！');
        } else {
            return $this->error($result['msg']);
        }
    }
}
