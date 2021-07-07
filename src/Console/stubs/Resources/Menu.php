<?php

namespace App\Admin\Resources;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Field;
use QuarkCMS\QuarkAdmin\Resource;
use Spatie\Permission\Models\Permission;

class Menu extends Resource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public static $title = '菜单';

    /**
     * 模型
     *
     * @var string
     */
    public static $model = 'QuarkCMS\QuarkAdmin\Models\Menu';

    /**
     * 列表查询
     *
     * @param  Request  $request
     * @return object
     */
    public static function indexQuery(Request $request, $query)
    {
        return $query->orderBy('sort', 'asc');
    }

    /**
     * 字段
     *
     * @param  Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        foreach (Permission::all() as $permission) {
            $getPermissions[$permission['id']] = $permission['name'];
        }

        return [
            Field::hidden('id','ID')
            ->onlyOnForms(),

            Field::text('name','名称')
            ->rules(
                ['required'],
                ['required' => '名称必须填写']
            ),

            Field::text('guard_name','GuardName')
            ->default('ADMIN')
            ->onlyOnForms(),
            
            Field::icon('icon','图标')
            ->default(0)
            ->onlyOnForms(),

            Field::radio('type','渲染组件')
            ->options(['default'=>'无组件', 'engine'=>'引擎组件'])
            ->default('engine'),

            Field::text('path','路由')
            ->editable()
            ->help('前端路由或后端api'),

            Field::select('pid','父节点')
            ->options(\QuarkCMS\QuarkAdmin\Models\Menu::orderedList())
            ->default(0)
            ->onlyOnForms(),
            
            Field::text('sort','排序')
            ->default(0)
            ->editable(),
    
            Field::select('permission_ids','绑定权限')
            ->mode('tags')
            ->options($getPermissions ?? [])
            ->onlyOnForms(),

            Field::switch('show','显示')
            ->editable()
            ->trueValue('是')
            ->falseValue('否')
            ->default(true),

            Field::switch('status','状态')
            ->editable()
            ->trueValue('正常')
            ->falseValue('禁用')
            ->default(true)
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
            new \App\Admin\Searches\Input('path', '路由'),
            new \App\Admin\Searches\Status
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
            (new \App\Admin\Actions\CreateDrawer($this->title()))->onlyOnIndex(),
            (new \App\Admin\Actions\Delete('批量删除'))->onlyOnTableAlert(),
            (new \App\Admin\Actions\Disable('批量禁用'))->onlyOnTableAlert(),
            (new \App\Admin\Actions\Enable('批量启用'))->onlyOnTableAlert(),
            (new \App\Admin\Actions\ChangeStatus)->onlyOnTableRow(),
            (new \App\Admin\Actions\EditDrawer('编辑'))->onlyOnTableRow(),
            (new \App\Admin\Actions\Delete('删除'))->onlyOnTableRow(),
        ];
    }

    /**
     * 列表页面显示前回调
     * 
     * @param Request $request
     * @param mixed $list
     * @return array
     */
    public function beforeIndexShowing(Request $request, $list)
    {
        // 转换成树形表格
        return list_to_tree($list,'id','pid','children', 0);
    }

    /**
     * 编辑页面显示前回调
     *
     * @param Request $request
     * @param array $data
     * @return array
     */
    public function beforeEditing(Request $request, $data)
    {
        $data['permission_ids'] = $request->id ? Permission::where('menu_id',$request->id)->pluck('id') : [];

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
        unset($submitData['permission_ids']);

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
        // 先清空权限
        Permission::where('menu_id',request('id'))->update(['menu_id' => 0]);

        if(request('permission_ids')) {
            // 更新权限
            return Permission::whereIn('id',request('permission_ids'))->update(['menu_id' => $model->id]);
        } else {
            return $model;
        }
    }
}