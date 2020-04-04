<?php

namespace QuarkCMS\QuarkAdmin\Controllers;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Models\Menu;
use Spatie\Permission\Models\Permission;
use Quark;

class MenuController extends QuarkController
{
    public $title = '菜单';

    /**
     * 列表页面
     *
     * @param  Request  $request
     * @return Response
     */
    protected function table()
    {
        $grid = Quark::grid(new Menu)
        ->title($this->title)
        ->tree();

        $grid->column('name','名称')->link()->width(200);
        $grid->column('sort','排序')->editable()->width('12%');
        $grid->column('icon','图标');
        $grid->column('path','路由')->editable()->width('18%');
        $grid->column('show','显示')->editable('switch',[
            'on'  => ['value' => 1, 'text' => '显示'],
            'off' => ['value' => 0, 'text' => '隐藏']
        ])->width(100);
        $grid->column('status','状态')->editable('switch',[
            'on'  => ['value' => 1, 'text' => '正常'],
            'off' => ['value' => 0, 'text' => '禁用']
        ])->width(100);
        $grid->column('actions','操作')->width(100)->rowActions(function($rowAction) {
            $rowAction->menu('editWithModal', '编辑')->withModal('编辑菜单',function($modal) {
                $modal->disableFooter();
                $modal->form->ajax('admin/menu/edit');
            });
            $rowAction->menu('delete', '删除')->model(function($model) {
                $model->delete();
            })->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！');
        });

        // 头部操作
        $grid->actions(function($action) {
            $action->button('createWithModal', '创建')
            ->type('primary')
            ->icon('plus-circle')
            ->withModal('创建菜单',function($modal) {
                $modal->disableFooter();
                $modal->form->ajax('admin/menu/create');
            });
            $action->button('refresh', '刷新');
        });

        // select样式的批量操作
        $grid->batchActions(function($batch) {
            $batch->option('', '批量操作');
            $batch->option('delete', '删除')->model(function($model) {
                $model->delete();
            })->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！');
        })->style('select',['width'=>120]);

        $grid->search(function($search) {
            $search->where('name', '搜索内容',function ($query) {
                $query->where('name', 'like', "%{input}%");
            })->placeholder('名称');
        })->expand(false);

        $grid->disableAdvancedSearch();

        $grid->model()
        ->orderBy('sort', 'asc')
        ->paginate(100);

        return $grid;
    }

    /**
     * 表单页面
     * 
     * @param  Request  $request
     * @return Response
     */
    protected function form()
    {
        $id = request('id');

        $form = Quark::form(new Menu);

        $title = $form->isCreating() ? '创建'.$this->title : '编辑'.$this->title;
        $form->title($title);
        
        $layout['labelCol']['span'] = 4;
        $layout['wrapperCol']['span'] = 20;
        $form->layout($layout);

        $form->id('id','ID');

        $form->text('name','名称')
        ->rules(['required','max:20'],['required'=>'名称必须填写','max'=>'名称不能超过20个字符']);

        // 查询列表
        $menus = Menu::query()->where('guard_name','admin')
        ->orderBy('sort', 'asc')
        ->orderBy('id', 'asc')
        ->get()
        ->toArray();

        $menuTrees = list_to_tree($menus,'id','pid','children',0);
        $menuTreeLists = tree_to_ordered_list($menuTrees,0,'name','children');

        $list[0] = '根节点';
        foreach ($menuTreeLists as $key => $menuTreeList) {
            $list[$menuTreeList['id']] = $menuTreeList['name'];
        }

        $form->select('pid','父节点')
        ->width(200)
        ->options($list)
        ->default(1);

        $form->number('sort','排序')->default(0);

        $form->icon('icon','图标')->width(200);

        $form->select('type','渲染组件')
        ->width(200)
        ->options(['default'=>'无组件','table'=>'列表组件','form'=>'表单组件','show'=>'详情组件'])
        ->default('default');

        $form->text('path','路由')->help('前端路由或后端api');

        $permissions = Permission::all();

        $getPermissions = [];
        foreach ($permissions as $key => $permission) {
            $getPermissions[$permission['id']] = $permission['name'];
        }

        $permissionIds = [];

        if($id) {
            $permissionIds= Permission::where('menu_id',$id)->pluck('id');
        }

        $form->select('permission_ids','绑定权限')
        ->mode('tags')
        ->options($getPermissions)
        ->value($permissionIds);

        $form->switch('show','显示')->options([
            'on'  => '是',
            'off' => '否'
        ])->default(true);

        $form->switch('status','状态')->options([
            'on'  => '正常',
            'off' => '禁用'
        ])->default(true);

        return $form;
    }

    /**
     * 保存方法
     * 
     * @param  Request  $request
     * @return Response
     */
    public function store(Request $request)
    {
        $name          =   $request->json('name','');
        $pid           =   $request->json('pid',0);
        $sort          =   $request->json('sort',0);
        $icon          =   $request->json('icon','');
        $type          =   $request->json('type','');
        $path          =   $request->json('path');
        $permissionIds =   $request->json('permission_ids','');
        $show          =   $request->json('show');
        $status        =   $request->json('status','');
        
        if (empty($name)) {
            return error('名称必须填写！');
        }

        switch ($type) {
            case 'default':
                $getPath = $path;
                break;

            case 'table':
                $getPath = '/quark/engine?api='.$path.'&component=table';
                break;

            case 'form':
                $getPath = '/quark/engine?api='.$path.'&component=form';
                break;

            case 'show':
                $getPath = '/quark/engine?api='.$path.'&component=show';
                break;

            default:
                $getPath = $path;
                break;
        }

        $data['name'] = $name;
        $data['guard_name'] = 'admin';
        $data['pid'] = $pid;
        $data['sort'] = $sort;
        $data['icon'] = $icon;
        $data['path'] = $getPath;
        $data['show'] = $show;
        $data['status'] = $status;

        $result = Menu::create($data);

        if($permissionIds) {
            Permission::whereIn('id',$permissionIds)->update(['menu_id' => $result['id']]);
        }

        if($result) {
            return success('操作成功！');
        } else {
            return error('操作失败！');
        }
    }

    /**
     * 保存编辑数据
     *
     * @param  Request  $request
     * @return Response
     */
    public function update(Request $request)
    {
        $id            =   $request->json('id');
        $name          =   $request->json('name','');
        $pid           =   $request->json('pid',0);
        $sort          =   $request->json('sort',0);
        $icon          =   $request->json('icon','');
        $type          =   $request->json('type','');
        $path          =   $request->json('path');
        $permissionIds =   $request->json('permission_ids','');
        $show          =   $request->json('show');
        $status        =   $request->json('status','');

        if (empty($id)) {
            return error('请选择数据！');
        }
        
        if (empty($name)) {
            return error('名称必须填写！');
        }
        
        switch ($type) {
            case 'default':
                $getPath = $path;
                break;

            case 'table':
                $getPath = '/quark/engine?api='.$path.'&component=table';
                break;

            case 'form':
                $getPath = '/quark/engine?api='.$path.'&component=form';
                break;

            case 'show':
                $getPath = '/quark/engine?api='.$path.'&component=show';
                break;

            default:
                $getPath = $path;
                break;
        }

        if ($show == true) {
            $show = 1;
        } else {
            $show = 0; //隐藏
        }

        if ($status == true) {
            $status = 1;
        } else {
            $status = 2; //禁用
        }

        $data['name'] = $name;
        $data['guard_name'] = 'admin';
        $data['pid'] = $pid;
        $data['sort'] = $sort;
        $data['icon'] = $icon;
        $data['path'] = $getPath;
        $data['show'] = $show;
        $data['status'] = $status;

        $result = Menu::where('id',$id)->update($data);

        if($permissionIds) {

            // 先清空
            Permission::where('menu_id',$id)->update(['menu_id' => 0]);

            // 赋值
            Permission::whereIn('id',$permissionIds)->update(['menu_id' => $id]);
        }

        if ($result) {
            return success('操作成功！');
        } else {
            return error('操作失败！');
        }
    }
}
