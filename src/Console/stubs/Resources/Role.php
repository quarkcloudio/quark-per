<?php

namespace App\Admin\Resources;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Field;
use QuarkCMS\QuarkAdmin\Resource;

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
     * 定义排序
     *
     * @param  Request  $request
     * @return object
     */
    public static function indexQuery(Request $request, $query)
    {
        return $query->orderBy('id','desc');
    }

    /**
     * 字段
     *
     * @param  Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            Field::hidden('id','ID')->onlyOnForms(),
            Field::text('name','名称')->rules(['required'], ['required' => '名称必须填写']),
            Field::text('guard_name','GuardName')->default('admin'),
            Field::datetime('created_at','创建时间')->onlyOnIndex(),
            Field::datetime('updated_at','更新时间')->onlyOnIndex(),
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
            (new \App\Admin\Actions\Delete('批量删除'))->onlyOnTableAlert(),
            (new \App\Admin\Actions\EditLink('编辑'))->onlyOnTableRow(),
            (new \App\Admin\Actions\Delete('删除'))->onlyOnTableRow(),
        ];
    }
}