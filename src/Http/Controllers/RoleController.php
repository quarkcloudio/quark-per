<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use QuarkCMS\QuarkAdmin\Models\Menu;
use QuarkCMS\QuarkAdmin\Table;
use QuarkCMS\QuarkAdmin\Action;
use QuarkCMS\QuarkAdmin\Form;
use DB;

class RoleController extends Controller
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
        $table = new Table(new Role);
        $table->headerTitle($this->title.'列表');
        
        $table->column('id','序号');
        $table->column('name','名称')->editLink();
        $table->column('guard_name','guard名称')->link();
        $table->column('created_at','创建时间');
        $table->column('actions','操作')->width(180)->actions(function($row) {

            // 创建行为对象
            $action = new Action();

            // 跳转默认编辑页面
            $action->a('编辑')->editLink();

            $action->a('删除')
            ->withPopconfirm('确认要删除吗？')
            ->model()
            ->where('id','{id}')
            ->delete();

            return $action;
        });

        $table->toolBar()->actions(function($action) {

            // 跳转默认创建页面
            $action->button('创建角色')->type('primary')->icon('plus-circle')->createLink();

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
        });

        // 搜索
        $table->search(function($search) {
            $search->where('name', '搜索内容',function ($model) {
                $model->where('name', 'like', "%{input}%");
            })->placeholder('名称');
        });

        $table->model()->orderBy('id','desc')->paginate(request('pageSize',10));

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
        $form = new Form(new Role);
        if($form->isCreating()) {
            $title = '新增'.$this->title;
        } else {
            $title = '编辑'.$this->title;
        }
        $form->labelCol(['span' => 4])->title($title);
        $form->hidden('id');
        $form->text('name','名称')
        ->rules(['required','max:20'],['required'=>'名称必须填写','max'=>'名称不能超过20个字符'])
        ->creationRules(["unique:roles"],['unique'=>'名称已经存在'])
        ->updateRules(["unique:roles,name,{id}"],['unique'=>'名称已经存在']);

        $form->text('guard_name','Guard名称')
        ->rules(['required','max:20'],['required'=>'Guard名称必须填写','max'=>'Guard名称不能超过20个字符'])
        ->value('admin');

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

        // 保存数据前回调
        $form->saving(function ($form) {
            if(isset($form->data['menu_ids'])) {
                $data = $form->data;
                unset($data['menu_ids']);
                $form->data = $data;
            }
        });

        // 保存数据后回调
        $form->saved(function ($form) {
            if($form->model()) {
                // 根据菜单id获取所有权限
                $permissions = Permission::whereIn('menu_id',request('menu_ids'))->pluck('id')->toArray();

                if($form->isCreating()) {
                    // 同步权限
                    $form->model()->syncPermissions(array_filter(array_unique($permissions)));
                } else {
                    // 同步权限
                    Role::where('id',request('id'))->first()->syncPermissions(array_filter(array_unique($permissions)));
                }

                return success('操作成功！',frontend_url('admin/role/index'));
            } else {
                return error('操作失败，请重试！');
            }
        });

        return $form;
    }
}
