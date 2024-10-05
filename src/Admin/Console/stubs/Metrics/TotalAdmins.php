<?php

namespace App\Admin\Metrics;

use Illuminate\Http\Request;
use QuarkCloudIO\QuarkAdmin\Models\Admin;
use QuarkCloudIO\QuarkAdmin\Metrics\Value;

class TotalAdmins extends Value
{
    /**
     * 卡片标题
     *
     * @var string
     */
    public $title = '管理员数量';

    /**
     * 卡片占的栅格数
     *
     * @var number
     */
    public $col = 6;

    /**
     * 计算数值
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        return $this->count($request, Admin::class)->valueStyle(['color'=>'#3f8600']);
    }
}