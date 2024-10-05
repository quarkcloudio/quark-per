<?php

namespace App\Admin\Resources;

use Illuminate\Http\Request;
use QuarkCloudIO\QuarkAdmin\Field;
use QuarkCloudIO\QuarkAdmin\Resource;

class Permission extends Resource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public static $title = '权限';

    /**
     * 模型
     *
     * @var string
     */
    public static $model = 'Spatie\Permission\Models\Permission';

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
        return [
            Field::hidden('id','ID')->onlyOnForms(),
            Field::text('name','名称')->rules(['required'], ['required' => '名称必须填写']),
            Field::text('guard_name','GuardName')->default('admin'),
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
            (new \App\Admin\Actions\SyncPermission)->onlyOnIndex(),
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
}