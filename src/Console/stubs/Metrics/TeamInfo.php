<?php

namespace App\Admin\Metrics;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Metrics\Descriptions;
use QuarkCMS\Quark\Facades\DescriptionField;

class TeamInfo extends Descriptions
{
    /**
     * 卡片标题
     *
     * @var string
     */
    public $title = '团队信息';

    /**
     * 卡片占的栅格数
     *
     * @var number
     */
    public $col = 12;

    /**
     * 计算数值
     *
     * @param  \Illuminate\Http\Request  $request
     * @return mixed
     */
    public function calculate(Request $request)
    {
        return $this->result($this->data());
    }

    /**
     * 系统信息
     *
     * @return array
     */
    protected function data()
    {
        return [
            DescriptionField::text('作者')->value('tangtanglove'),

            DescriptionField::text('联系方式')->value('dai_hang_love@126.com'),

            DescriptionField::text('官方网址')->value(
                "<a href='https://www.quarkcms.com' target='_blank'>www.quarkcms.com</a>"
            ),

            DescriptionField::text('文档地址')->value(
                "<a href='https://www.quarkcms.com' target='_blank'>查看文档</a>"
            ),

            DescriptionField::text('BUG反馈')->value(
                "<a href='https://github.com/quarkcms/quark-admin/issues' target='_blank'>提交BUG</a>"
            ),
            
            DescriptionField::text('代码仓储')->value(
                "<a href='https://github.com/quarkcms/quark-admin' target='_blank'>Github</a>"
            )
        ];
    }
}