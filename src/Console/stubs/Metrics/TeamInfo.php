<?php

namespace App\Admin\Metrics;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Metrics\Descriptions;
use QuarkAdmin;
use DB;

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
            [
                'type' => "text",
                'label' => "作者",
                'value' => "tangtanglove"
            ],
            [
                'type' => "text",
                'label' => "联系方式",
                'value' => "dai_hang_love@126.com"
            ],
            [
                'type' => "link",
                'label' => "官方网址",
                'value' => "www.quarkcms.com",
                'href' => "https://www.quarkcms.com",
                'target' => '_blank'
            ],
            [
                'type' => "link",
                'label' => "文档地址",
                'value' => "查看文档",
                'href' => "https://www.quarkcms.com",
                'target' => '_blank'
            ],
            [
                'type' => "link",
                'label' => "BUG反馈",
                'value' => "提交BUG",
                'href' => "https://github.com/quarkcms/quark-admin/issues",
                'target' => '_blank'
            ],
            [
                'type' => "link",
                'label' => "代码仓储",
                'value' => "Github",
                'href' => "https://github.com/quarkcms/quark-admin",
                'target' => '_blank'
            ]
        ];
    }
}