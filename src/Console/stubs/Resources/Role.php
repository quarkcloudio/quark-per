<?php

namespace App\Admin\Resources;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Field;
use QuarkCMS\QuarkAdmin\Resource;
use QuarkCMS\QuarkAdmin\Models\Menu;
use Spatie\Permission\Models\Permission;
use DB;

class Role extends Resource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public static $title = '角色';

    /**
     * 模型
     *
     * @var string
     */
    public static $model = 'Spatie\Permission\Models\Role';

    /**
     * 分页
     *
     * @var int|bool
     */
    public static $perPage = 10;

    /**
     * 字段
     *
     * @param  Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        // 查询列表
        $menus = Menu::tree();

        return [
            Field::hidden('id','ID')->onlyOnForms(),
            Field::text('name','名称')
            ->rules(['required'], ['required' => '名称必须填写'])
            ->creationRules(
                ['unique:roles,name'],
                ['unique'=>'名称已存在']
            ),
            Field::text('guard_name','GuardName')->default('admin')->onlyOnForms(),
            Field::tree('menu_ids','权限')->data($menus)->onlyOnForms(),
            Field::datetime('created_date','创建时间',function () {
                return \Carbon\Carbon::parse($this->created_at)->format('Y-m-d H:i:s');
            })->onlyOnIndex(),
            Field::datetime('updated_date','更新时间',function () {
                return \Carbon\Carbon::parse($this->updated_at)->format('Y-m-d H:i:s');
            })->onlyOnIndex(),
        ];
    }

    /**
     * 搜索表单
     *
     * @param  Request  $request
     * @return object
     */
    public function searches(Request $request)
    {
        return [
            new \App\Admin\Searches\Input('name', '名称'),
            new \App\Admin\Searches\Input('guard_name', 'GuardName')
        ];
    }

    /**
     * 行为
     *
     * @param  Request  $request
     * @return object
     */
    public function actions(Request $request)
    {
        return [
            (new \App\Admin\Actions\CreateLink($this->title()))->onlyOnIndex(),
            (new \App\Admin\Actions\Delete('批量删除'))->onlyOnIndexTableAlert(),
            (new \App\Admin\Actions\EditLink('编辑'))->onlyOnIndexTableRow(),
            (new \App\Admin\Actions\Delete('删除'))->onlyOnIndexTableRow(),
            new \App\Admin\Actions\FormSubmit,
            new \App\Admin\Actions\FormReset,
            new \App\Admin\Actions\FormBack,
            new \App\Admin\Actions\FormExtraBack
        ];
    }

    /**
     * 保存前回调
     *
     * @param  Request  $request
     * @param  array $data
     * @return object
     */
    public function beforeEditing(Request $request, $data)
    {
        $id = $request->get('id');

        // 查询列表
        $menus = Menu::where('status',1)->where('guard_name','admin')->select('name as title','id as key','pid')->get()->toArray();

        $checkedMenus = [];

        foreach ($menus as $key => $menu) {
            $permissionIds = Permission::where('menu_id',$menu['key'])->pluck('id');

            $roleHasPermission = DB::table('role_has_permissions')
            ->whereIn('permission_id',$permissionIds)
            ->where('role_id',$id)
            ->first();

            if($roleHasPermission) {
                $checkedMenus[] = strval($menu['key']);
            }
        }

        $data['menu_ids'] = $checkedMenus;

        return $data;
    }

    /**
     * 保存前回调
     *
     * @param  Request  $request
     * @param  array $submitData
     * @return object
     */
    public function beforeSaving(Request $request, $submitData)
    {
        if(isset($submitData['menu_ids'])) {
            unset($submitData['menu_ids']);
        }

        return $submitData;
    }

    /**
     * 保存后回调
     *
     * @param  Request  $request
     * @return object
     */
    public function afterSaved(Request $request, $model)
    {
        // 根据菜单id获取所有权限
        if(empty($request->input('menu_ids'))) {
            return $model;
        }

        $permissions = Permission::whereIn('menu_id',$request->input('menu_ids'))->pluck('id')->toArray();

        if (empty($permissions)) {
            return $model;
        }
        
        if($this->isCreating()) {

            // 同步权限
            return $model->syncPermissions(array_filter(array_unique($permissions)));
        } else {

            // 同步权限
            return Role::where('id',$request->input('id'))->first()->syncPermissions(array_filter(array_unique($permissions)));
        }
    }
}