<?php

namespace App\Admin\Metrics;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Models\Picture;
use QuarkCMS\QuarkAdmin\Metrics\Value;

class TotalPictures extends Value
{
    /**
     * 卡片标题
     *
     * @var string
     */
    public $title = '图片数量';

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
        return $this->count($request, Picture::class)->valueStyle(['color'=>'#cf1322']);
    }
}