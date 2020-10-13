<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Models\Menu;
use Spatie\Permission\Models\Permission;
use QuarkCMS\QuarkAdmin\Table;
use QuarkCMS\QuarkAdmin\Action;
use QuarkCMS\QuarkAdmin\Form;

class MenuController extends Controller
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
        $table = new Table(new Menu);
        $table->headerTitle($this->title.'列表')->tree();
        
        $table->column('name','名称')->link()->width(200);
        $table->column('sort','排序')->width(60);
        $table->column('icon','图标');
        $table->column('path','路由')->width('18%');
        $table->column('show','显示')->using(['1'=>'显示','0'=>'隐藏'])->width(100);
        $table->column('status','状态')->using(['1'=>'正常','0'=>'禁用'])->width(60);
        $table->column('actions','操作')->width(180)->actions(function($row) {

            // 创建行为对象
            $action = new Action();

            // 根据不同的条件定义不同的A标签形式行为
            if($row['status'] === 1) {
                $action->a('禁用')
                ->withPopconfirm('确认要禁用数据吗？')
                ->model()
                ->where('id','{id}')
                ->update(['status'=>0]);
            } else {
                $action->a('启用')
                ->withPopconfirm('确认要启用数据吗？')
                ->model()
                ->where('id','{id}')
                ->update(['status'=>1]);
            }

            // 跳转默认编辑页面
            $action->a('编辑')->modalForm();

            if($row['show'] === 1) {
                $action->a('隐藏')
                ->withPopconfirm('确认要隐藏菜单吗？')
                ->model()
                ->where('id','{id}')
                ->update(['show'=>0]);
            } else {
                $action->a('显示')
                ->withPopconfirm('确认要显示菜单吗？')
                ->model()
                ->where('id','{id}')
                ->update(['show'=>1]);
            }

            $action->a('删除')
            ->withPopconfirm('确认要删除吗？')
            ->model()
            ->where('id','{id}')
            ->delete();

            return $action;
        });

        $table->toolBar()->actions(function($action) {
            // 跳转默认创建页面
            $action->button('创建菜单')
            ->type('primary')
            ->icon('plus-circle')
            ->modalForm();
            return $action;
        });

        // 批量操作
        $table->batchActions(function($action) {
            // 跳转默认编辑页面
            $action->a('批量删除')
            ->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！')
            ->model()
            ->whereIn('id','{ids}')
            ->delete();

            // 下拉菜单形式的行为
            $action->dropdown('更多')->overlay(function($action) {
                $action->item('禁用菜单')
                ->withConfirm('确认要禁用吗？','禁用后菜单将无法使用，请谨慎操作！')
                ->model()
                ->whereIn('id','{ids}')
                ->update(['status'=>0]);

                $action->item('启用菜单')
                ->withConfirm('确认要启用吗？','启用后菜单可以正常使用！')
                ->model()
                ->whereIn('id','{ids}')
                ->update(['status'=>1]);

                return $action;
            });
        });

        // 搜索
        $table->search(function($search) {

            $search->where('name', '搜索内容',function ($model) {
                $model->where('name', 'like', "%{input}%");
            })->placeholder('名称');

            $search->equal('status', '所选状态')
            ->select([''=>'全部', 1=>'正常', 0=>'已禁用'])
            ->placeholder('选择状态')
            ->width(110);
        });

        $table->model()
        ->orderBy('sort', 'asc')
        ->paginate(request('pageSize',100));

        return $table;
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
        ->default(0);

        $form->number('sort','排序')->default(0);

        $form->icon('icon','图标')->width(200);

        $form->select('type','渲染组件')
        ->width(200)
        ->options(['default'=>'无组件','engine'=>'引擎组件'])
        ->default('engine');

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

        $data['type'] = $type;
        $data['name'] = $name;
        $data['guard_name'] = 'admin';
        $data['pid'] = $pid;
        $data['sort'] = $sort;
        $data['icon'] = $icon;
        $data['path'] = $path;
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

        $data['type'] = $type;
        $data['name'] = $name;
        $data['guard_name'] = 'admin';
        $data['pid'] = $pid;
        $data['sort'] = $sort;
        $data['icon'] = $icon;
        $data['path'] = $path;
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
