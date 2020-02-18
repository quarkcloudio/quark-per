<?php

namespace QuarkCMS\QuarkAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Support\Facades\Input;
use Quark;

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

    public function index(Request $request)
    {
        $table = $this->table();

        $content = Quark::content()
        ->title($this->title())
        ->subTitle($this->subTitle())
        ->description($this->description())
        ->breadcrumb($this->breadcrumb())
        ->body($table);

        return $this->success('获取成功！','',$content);
    }

    public function show(Request $request)
    {
        $id = $request->get('id');

        $detail = $this->detail();

        $content = Quark::content()
        ->title($this->title())
        ->subTitle($this->subTitle())
        ->description($this->description())
        ->breadcrumb($this->breadcrumb())
        ->body($detail);

        return $this->success('获取成功！','',$content);
    }

    public function create(Request $request)
    {
        $form = $this->form();

        $content = Quark::content()
        ->title($this->title())
        ->subTitle($this->subTitle())
        ->description($this->description())
        ->breadcrumb($this->breadcrumb())
        ->body($form);

        return $this->success('获取成功！','',$content);
    }

    public function store(Request $request)
    {
        $result = $this->form()->store();

        if($result) {
            return $this->success('操作成功！');
        } else {
            return $this->error('操作失败！');
        }
    }

    public function edit(Request $request)
    {
        $id = $request->get('id');

        if(empty($id)) {
            return $this->error('参数错误！');
        }
        $content = $this->form()->edit($id);

        return $this->success('获取成功！','',$content);
    }

    public function save(Request $request)
    {
        $result = $this->form()->save();

        if($result) {
            return $this->success('操作成功！');
        } else {
            return $this->error('操作失败！');
        }
    }

    /**
     * Remove the specified resource from storage.
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
