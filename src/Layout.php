<?php

namespace QuarkCMS\QuarkAdmin;

use QuarkCMS\Quark\Facades\Layout as LayoutFacade;
use QuarkCMS\Quark\Facades\Page;
use QuarkCMS\Quark\Facades\PageContainer;
use QuarkCMS\QuarkAdmin\Models\Menu;
use QuarkCMS\QuarkAdmin\Models\Admin;
use Illuminate\Support\Str;

trait Layout
{
    /**
     * 设置菜单
     *
     * @param  array $menu
     * @return $this
     */
    public function menu($menu = null)
    {
        if($menu === null) {
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
                    $path = '/index?api='.$value['path'];
                } else {
                    $path = $value['path'];
                }

                $data[$key]['path'] = $path;
            }

            $menuTrees = list_to_tree($data,'id','pid','children');
        } else {
            $menuTrees = $menu;
        }

        return $menuTrees;
    }

    /**
     * 设置布局内容
     *
     * @param  any $content
     * @return array
     */
    public function setLayoutContent($content)
    {
        // 页面内容
        $pageContainer = PageContainer::title($this->title)->body($content);

        // 布局
        $layout = LayoutFacade::title(config('admin.name','QuarkAdmin'))
        ->fixSiderbar()
        ->menu($this->menu())
        ->body($pageContainer);

        // 页面
        return Page::style(['height' => '100vh'])->body($layout);
    }
}
