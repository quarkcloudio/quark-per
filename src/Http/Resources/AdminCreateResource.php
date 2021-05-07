<?php

namespace QuarkCMS\QuarkAdmin\Http\Resources;

use QuarkCMS\Quark\Facades\FormItem;
use QuarkCMS\QuarkAdmin\Http\Resources\FormResource;

class AdminCreateResource extends FormResource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public $title = '创建管理员';

    /**
     * 表单提交接口
     *
     * @var string
     */
    public $api = '/admin/admin/store';

    /**
     * 表单项
     *
     * @param  array $data
     * @return array
     */
    public function items($data)
    {
        $FormItems[] = FormItem::text('username','用户名');

        return $FormItems;
    }
}