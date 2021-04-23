<?php

namespace QuarkCMS\QuarkAdmin\Http\Resources;

use QuarkCMS\Quark\Facades\Layout;
use QuarkCMS\Quark\Facades\Card;
use QuarkCMS\Quark\Facades\Row;
use QuarkCMS\Quark\Facades\StatisticCard;
use QuarkCMS\Quark\Facades\Descriptions;
use QuarkCMS\QuarkAdmin\Http\Resources\LayoutResource;

class AdminIndexResource extends LayoutResource
{
    /**
     * 页面标题
     *
     * @var string
     */
    public $title = '管理员列表';

    /**
     * 页面内容
     *
     * @param  void
     * @return void
     */
    public function body()
    {
        $data = $this->data;

        return 'xxx';
    }
}