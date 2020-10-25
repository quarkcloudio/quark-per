<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use QuarkCMS\QuarkAdmin\Quark;
use QuarkCMS\QuarkAdmin\Models\Admin;
use QuarkCMS\QuarkAdmin\Models\Menu;

class QuarkController extends Controller
{
    /**
     * quark object
     *
     * @var object
     */
    protected $quark;

    /**
     * Create a new instance.
     *
     * @return void
     */
    public function __construct() {
        $this->quark = new Quark;
    }

    /**
     * 后台入口方法
     *
     * @param Request $request
     *
     * @return array
     */
    public function index(Request $request)
    {
        $assetManifest = file_get_contents(public_path('\\admin\\asset-manifest.json'));
        $data = json_decode($assetManifest,true);
        $result['umiCss'] = $data['/umi.css'];
        $result['umiJs'] = $data['/umi.js'];

        return view('admin.index',$result);
    }

    /**
     * 获取系统信息
     *
     * @param Request $request
     *
     * @return array
     */
    public function info(Request $request)
    {
        $info = $this->quark->info();

        if($info) {
            return success('ok','',$info);
        } else {
            return error('failed!');
        }
    }

    /**
     * 获取layout布局
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function layout(Request $request)
    {
        $layout = $this->quark->layout();

        if($layout) {
            return success('ok','',$layout);
        } else {
            return error('failed!');
        }
    }

    /**
     * 获取权限菜单
     *
     * @param  Request  $request
     *
     * @return Response
     */
    public function menus(Request $request)
    {
        // 通过当前url倒推二级菜单和一级菜单

        // id等于1时默认为超级管理员
        if(ADMINID == 1) {

            // 查询列表
            $data = Menu::where('status', 1)
            ->where('guard_name', 'admin')
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();

        } else {
            // 获取当前用户的所有权限
            $getPermissions = Admin::where('id',ADMINID)->first()->getPermissionsViaRoles();

            foreach ($getPermissions as $key => $value) {
                $menuIds[] = $value->menu_id;
            }

            // 三级查询列表
            $lists = Menu::where('status', 1)
            ->where('guard_name', 'admin')
            ->where('pid','<>', 0)
            ->whereIn('id',$menuIds)
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();

            foreach ($lists as $key => $value) {
                if(!empty($value['pid'])) {
                    $pids[] = $value['pid'];
                }
            }

            // 二级菜单查询列表
            $lists1 = Menu::where('status', 1)
            ->where('guard_name', 'admin')
            ->whereIn('id',$pids)
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();

            $pids1 = [];

            foreach ($lists1 as $key1 => $value1) {
                if(!empty($value1['pid'])) {
                    $pids1[] = $value1['pid'];
                }
            }

            // 一级菜单查询列表
            $lists2 = Menu::where('status', 1)
            ->where('guard_name', 'admin')
            ->where('pid', 0)
            ->whereIn('id',$pids1)
            ->orderBy('sort', 'asc')
            ->get()
            ->toArray();

            $data = array_merge($lists,$lists1,$lists2);
        }

        foreach ($data as $key => $value) {

            $data[$key]['key'] = Str::uuid();

            $data[$key]['locale'] = 'menu'.str_replace("/",".",$value['path']);
            if(!$value['show']) {
                $data[$key]['hideInMenu'] = true;
            }

            if(empty($data[$key]['icon'])) {
                unset($data[$key]['icon']);
            }

            if($value['type'] === 'engine') {
                $path = '/quark/engine?api='.$value['path'];
            } else {
                $path = $value['path'];
            }

            $data[$key]['path'] = $path;
        }

        $menuTrees = list_to_tree($data,'id','pid','children');

        return success('获取成功！','',$menuTrees);
    }

    /**
     * test
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function test(Request $request)
    {

    }
}
