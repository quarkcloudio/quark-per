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

        $detail = $this->detail();

        $content = Quark::content()
        ->title($this->title())
        ->subTitle($this->subTitle())
        ->description($this->description())
        ->breadcrumb($this->breadcrumb())
        ->body($detail->render());

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
        ->body($form->render());

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
        ->body($form->render());

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
     * 启用数据方法
     *
     * @param  Request  $request
     * @return Response
     */
    public function resume(Request $request)
    {
        $id = $request->json('id');
        $status = $request->json('status');

        if(empty($id) || empty($status)) {
            return $this->error('参数错误！');
        }

        // 定义对象
        $query = Menu::query();

        if(is_array($id)) {
            $query->whereIn('id',$id);
        } else {
            $query->where('id',$id);
        }

        $result = $query->update(['status'=>$status]);

        if ($result) {
            return $this->success('操作成功！');
        } else {
            return $this->error('操作失败！');
        }
    }

    /**
     * 禁用数据方法
     *
     * @param  Request  $request
     * @return Response
     */
    public function forbid(Request $request)
    {
        $id = $request->json('id');
        $status = $request->json('status');

        if(empty($id) || empty($status)) {
            return $this->error('参数错误！');
        }

        // 定义对象
        $query = Menu::query();

        if(is_array($id)) {
            $query->whereIn('id',$id);
        } else {
            $query->where('id',$id);
        }

        $result = $query->update(['status'=>$status]);

        if ($result) {
            return $this->success('操作成功！');
        } else {
            return $this->error('操作失败！');
        }
    }

    /**
     * 删除数据方法
     *
     * @param int $id
     *
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        return $this->form()->destroy($id);
    }
}
