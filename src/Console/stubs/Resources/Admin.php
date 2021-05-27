<?php

namespace App\Admin\Resources;

use QuarkCMS\QuarkAdmin\Resource;

class Admin extends Resource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public $title = '管理员';

    /**
     * 模型
     *
     * @var string
     */
    public static $model = 'QuarkCMS\QuarkAdmin\Models\Admin';

    /**
     * 字段
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function fields(Request $request)
    {
        return [
            
        ];
    }

    /**
     * 行为
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function actions(Request $request)
    {
        return [

        ];
    }
}