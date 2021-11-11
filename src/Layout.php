<?php

namespace QuarkCMS\QuarkAdmin;

use QuarkCMS\Quark\Facades\Layout as LayoutFacade;
use QuarkCMS\Quark\Facades\Page;
use QuarkCMS\Quark\Facades\PageContainer;
use QuarkCMS\Quark\Facades\Table;
use QuarkCMS\Quark\Facades\Form;
use QuarkCMS\Quark\Facades\Card;
use QuarkCMS\Quark\Facades\Tabs;
use QuarkCMS\Quark\Facades\Row;
use QuarkCMS\Quark\Facades\Col;
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
    protected function menu($menu = null)
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
     * 渲染页面组件
     *
     * @param  any $request
     * @param  any $content
     * @return array
     */
    public function pageComponentRender($request, $content)
    {
        $pageContainer = $this->pageContainerComponentRender($request, $content);

        $layout = $this->layoutComponentRender($request, $pageContainer);

        // 页面
        return Page::style(['height' => '100vh'])->body($layout);
    }

    /**
     * 渲染页面布局组件
     *
     * @param  any  $request
     * @param  object  $content
     * @return array
     */
    public function layoutComponentRender($request, $content)
    {
        $layout = LayoutFacade::title(config('admin.name','QuarkAdmin'));

        $layout->logo(config('admin.logo'));
        $layout->headerActions(config('admin.layout.header_actions'));
        $layout->layout(config('admin.layout.layout'));
        $layout->splitMenus(config('admin.layout.split_menus'));
        $layout->headerTheme(config('admin.layout.header_theme'));
        $layout->contentWidth(config('admin.layout.content_width'));
        $layout->navTheme(config('admin.layout.nav_theme'));
        $layout->primaryColor(config('admin.layout.primary_color'));
        $layout->fixedHeader(config('admin.layout.fixed_header'));
        $layout->fixSiderbar(config('admin.layout.fix_siderbar'));
        $layout->iconfontUrl(config('admin.layout.iconfont_url'));
        $layout->locale(config('admin.layout.locale'));
        $layout->siderWidth(config('admin.layout.sider_width'));
        $layout->menu($this->menu());
        $layout->body($content);

        return $layout;
    }

    /**
     * 渲染页面容器组件
     *
     * @param  any  $request
     * @param  object  $content
     * @return array
     */
    public function pageContainerComponentRender($request, $content)
    {
        // 页面内容
        return PageContainer::header(
            PageContainer::pageHeader($this->title(),$this->subTitle())
        )
        ->body($content);
    }

    /**
     * 渲染列表页组件
     *
     * @param  ResourceIndexRequest  $request
     * @param  object  $data
     * @return array
     */
    public function indexComponentRender($request, $data)
    {
        $resource = $request->resource();

        $table = Table::key('table')
        ->polling($resource::$indexPolling)
        ->title($this->indexTitle($request))
        ->tableExtraRender($this->indexExtraRender($request))
        ->toolBar($this->indexToolBar($request))
        ->columns($this->indexColumns($request))
        ->batchActions($this->indexTableAlertActions($request))
        ->searches($this->indexSearches($request));

        return $resource::pagination() ? 
                $table->pagination(
                    $data->currentPage(),
                    $data->perPage(),
                    $data->total()
                )->datasource($data->items()) : $table->datasource($data);
    }

    /**
     * 渲染创建页组件
     *
     * @param  ResourceCreateRequest  $request
     * @param  array  $data
     * @return array
     */
    public function creationComponentRender($request, $data)
    {
        return $this->formComponentRender(
            $request,
            $this->formTitle($request),
            $this->formExtraActions($request),
            $this->creationApi($request),
            $this->creationFieldsWithinComponents($request),
            $this->formActions($request),
            $data
        );
    }

    /**
     * 渲染编辑页组件
     *
     * @param  ResourceEditRequest  $request
     * @param  array  $data
     * @return array
     */
    public function updateComponentRender($request, $data)
    {
        return $this->formComponentRender(
            $request,
            $this->formTitle($request),
            $this->formExtraActions($request),
            $this->updateApi($request),
            $this->updateFieldsWithinComponents($request),
            $this->formActions($request),
            $data
        );
    }

    /**
     * 渲染表单组件
     *
     * @param  mixed   $request
     * @param  string  $title
     * @param  mixed   $extra
     * @param  string  $api
     * @param  array   $fields
     * @param  array   $actions
     * @param  array   $data
     * @return array
     */
    public function formComponentRender($request, $title, $extra, $api, $fields, $actions, $data)
    {
        if($fields[0]->component === 'tabPane') {
            return $this->formWithinTabs($request, $title, $extra, $api, $fields, $actions, $data);
        } else {
            return $this->formWithinCard($request, $title, $extra, $api, $fields, $actions, $data);
        }
    }

    /**
     * 在卡片内的From组件
     *
     * @param  mixed  $request
     * @return array
     */
    public function formWithinCard($request, $title, $extra, $api, $fields, $actions, $data)
    {
        $form = Form::api($api)
        ->style(['marginTop' => '30px'])
        ->actions($actions)
        ->body($fields)
        ->initialValues($data);

        return Card::title($title)
        ->headerBordered()
        ->extra($extra)
        ->body($form);
    }

    /**
     * 在标签页内的From组件
     *
     * @param  mixed  $request
     * @return array
     */
    public function formWithinTabs($request, $title, $extra, $api, $fields, $actions, $data)
    {
        return Form::api($api)
        ->actions($actions)
        ->style([
            'marginTop' => '30px',
            'backgroundColor' => '#fff',
            'paddingBottom' => '20px'
        ])
        ->body(Tabs::tabPanes($fields)->tabBarExtraContent($extra))
        ->initialValues($data);
    }

    /**
     * 渲染详情页组件
     *
     * @param  ResourceEditRequest  $request
     * @param  array  $data
     * @return array
     */
    public function detailComponentRender($request, $data)
    {
        $title = $this->detailTitle($request);
        $extra = $this->detailExtraActions($request);
        $fields = $this->detailFieldsWithinComponents($request, $data);
        $actions = $this->detailActions($request);

        if(is_array($fields)) {
            if($fields[0]->component === 'tabPane') {
                return $this->detailWithinTabs($request, $title, $extra, $fields, $actions, $data);
            }
        }

        return $this->detailWithinCard($request, $title, $extra, $fields, $actions, $data);
    }

    /**
     * 在卡片内的详情页组件
     *
     * @param  mixed  $request
     * @return array
     */
    public function detailWithinCard($request, $title, $extra, $fields, $actions, $data)
    {
        return Card::title($title)
        ->headerBordered()
        ->extra($extra)
        ->body($fields);
    }

    /**
     * 在标签页内的详情页组件
     *
     * @param  mixed  $request
     * @return array
     */
    public function detailWithinTabs($request, $title, $extra, $fields, $actions, $data)
    {
        return Tabs::tabPanes($fields)->tabBarExtraContent($extra);
    }

    /**
     * 渲染仪表盘页组件
     *
     * @param  DashboardRequest  $request
     * @return array
     */
    public function dashboardComponentRender($request)
    {
        $cards = $this->cards();
        
        $colNum = 0;
        $rows = $cols = [];

        foreach ($cards as $key => $card) {
            $colNum = $colNum + $card->col;

            $cardItem = Card::body(
                $card->calculate($request)
            );

            $cols[] = Col::span($card->col)->body($cardItem);
            if($colNum%24 === 0) {
                $row = Row::gutter(8);
                if($key !== 1) {
                    $row = $row->style(['marginTop' => '20px']);
                }
                $rows[] = $row->body($cols);
                $cols = [];
            }
        }

        if($cols) {
            $row = Row::gutter(8);
            if($colNum > 24) {
                $row = $row->style(['marginTop' => '20px']);
            }
            $rows[] = $row->body($cols);
        }

        return $rows;
    }

    /**
     * 渲染组件
     *
     * @param  any $request
     * @param  any $content
     * @return array
     */
    public function render($request, $content)
    {
        return $this->pageComponentRender($request, $content);
    }
}
