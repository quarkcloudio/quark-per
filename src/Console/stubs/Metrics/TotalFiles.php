<?php

namespace App\Admin\Metrics;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Models\File;
use QuarkCMS\QuarkAdmin\Metrics\Value;

class TotalFiles extends Value
{
    /**
     * 卡片标题
     *
     * @var string
     */
    public $title = '文件数量';

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
        return $this->count($request, File::class)->valueStyle(['color'=>'#cf1322']);
    }
}