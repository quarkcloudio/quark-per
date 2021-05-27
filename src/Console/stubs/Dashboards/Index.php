<?php

namespace App\Admin\Dashboards;

use QuarkCMS\QuarkAdmin\Dashboard;
use App\Admin\Metrics\TotalAdmins;
use App\Admin\Metrics\TotalLogs;
use App\Admin\Metrics\TotalPictures;
use App\Admin\Metrics\TotalFiles;
use App\Admin\Metrics\SystemInfo;
use App\Admin\Metrics\TeamInfo;

class Index extends Dashboard
{
    /**
     * 页面标题
     *
     * @var string
     */
    public $title = '仪表盘';

    /**
     * 卡片列表
     *
     * @return array
     */
    public function cards()
    {
        return [
            new TotalAdmins,
            new TotalLogs,
            new TotalPictures,
            new TotalFiles,
            new SystemInfo,
            new TeamInfo
        ];
    }
}