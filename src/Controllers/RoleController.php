<?php

namespace QuarkCMS\QuarkAdmin\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use QuarkCMS\QuarkAdmin\Models\Menu;
use Quark;
use DB;

class RoleController extends QuarkController
{
    public $title = '角色';

    /**
     * 列表页面
     *
     * @param  Request  $request
     * @return Response
     */
    protected function table()
    {
        $grid = Quark::grid(new Role)->title($this->title);
        $grid->column('name','名称')->link();
        $grid->column('guard_name','guard名称')->link();
        $grid->column('created_at','创建时间');

        $grid->column('actions','操作')->width(100)->rowActions(function($rowAction) {
            $rowAction->menu('edit', '编辑');
            $rowAction->menu('delete', '删除')->model(function($model) {
                $model->delete();
            })->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！');
        });

        // 头部操作
        $grid->actions(function($action) {
            $action->button('create', '创建');
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

        $grid->model()->paginate(10);

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

        $form = Quark::form(new Role);

        $title = $form->isCreating() ? '创建'.$this->title : '编辑'.$this->title;
        $form->title($title);
        
        $form->id('id','ID');

        $form->text('name','名称')
        ->rules(['required','max:20'],['required'=>'名称必须填写','max'=>'名称不能超过20个字符'])
        ->creationRules(["unique:roles"],['unique'=>'名称已经存在'])
        ->updateRules(["unique:roles,name,{{id}}"],['unique'=>'名称已经存在']);

        // 查询列表
        $menus = Menu::where('status',1)->where('guard_name','admin')->select('name as title','id as key','pid')->get()->toArray();

        $checkedMenus = [];
        if(isset($id)) {
            foreach ($menus as $key => $menu) {
                $permissionIds = Permission::where('menu_id',$menu['key'])->pluck('id');
    
                $roleHasPermission = DB::table('role_has_permissions')
                ->whereIn('permission_id',$permissionIds)
                ->where('role_id',$id)
                ->first();
    
                if($roleHasPermission) {
                    $checkedMenus[] = strval($menu['key']);
                }
    
                $menus[$key]['key'] = strval($menu['key']);
            }
        }

        $menus = list_to_tree($menus,'key','pid','children',0);

        $form->tree('menu_ids','权限')
        ->data($menus)
        ->value($checkedMenus);

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
        $menuIds       =   $request->json('menu_ids');
        
        if (empty($name)) {
            return error('角色名称必须填写！');
        }

        $data['name'] = $name;
        $data['guard_name'] = 'admin';

        // 添加角色
        $role = Role::create($data);

        // 根据菜单id获取所有权限
        $permissions = Permission::whereIn('menu_id',$menuIds)->pluck('id')->toArray();

        // 同步权限
        $result = $role->syncPermissions(array_filter(array_unique($permissions)));

        if ($result) {
            return success('操作成功！','/quark/engine?api=/admin/role/index&component=table');
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
        $id            =   $request->json('id','');
        $name          =   $request->json('name','');
        $menuIds       =   $request->json('menu_ids');
        
        if (empty($id)) {
            return error('参数错误！');
        }

        if (empty($name)) {
            return error('角色名称必须填写！');
        }

        $data['name'] = $name;
        $data['guard_name'] = 'admin';

        // 更新角色
        $result = Role::where('id',$id)->update($data);

        // 根据菜单id获取所有权限
        $permissions = Permission::whereIn('menu_id',$menuIds)->pluck('id')->toArray();

        // 同步权限
        $result1 = Role::where('id',$id)->first()->syncPermissions(array_filter(array_unique($permissions)));

        if ($result && $result1) {
            return success('操作成功！','/quark/engine?api=/admin/role/index&component=table');
        } else {
            return error('操作失败！');
        }
    }
}
