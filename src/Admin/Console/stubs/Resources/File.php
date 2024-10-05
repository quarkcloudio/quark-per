<?php

namespace App\Admin\Resources;

use Illuminate\Http\Request;
use QuarkCloudIO\QuarkAdmin\Field;
use QuarkCloudIO\QuarkAdmin\Resource;

class File extends Resource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public static $title = '文件';

    /**
     * 模型
     *
     * @var string
     */
    public static $model = 'QuarkCloudIO\QuarkAdmin\Models\File';

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
            Field::text('name','名称')->onlyOnIndex(),
            Field::text('size','大小')->onlyOnIndex(),
            Field::text('ext','扩展名')->onlyOnIndex(),
            Field::datetime('created_at','上传时间')->onlyOnIndex(),
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
            new \App\Admin\Searches\DateTimeRange('created_at', '上传时间')
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
            (new \App\Admin\Actions\Delete('批量删除'))->onlyOnIndexTableAlert(),
            (new \App\Admin\Actions\Delete('删除'))->onlyOnIndexTableRow(),
        ];
    }
}