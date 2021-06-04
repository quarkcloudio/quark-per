<?php

namespace App\Admin\Actions;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Actions\Action;

class BatchDelete extends Action
{
    /**
     * 设置按钮类型,primary | ghost | dashed | link | text | default
     *
     * @var string
     */
    public $showStyle = 'link';

    /**
     * 行为名称
     *
     * @var string
     */
    public $name = '批量删除';

    /**
     * 执行行为
     *
     * @param  Fields  $fields
     * @param  Collection  $models
     * @return mixed
     */
    public function handle($fields, $models)
    {
        
    }
}