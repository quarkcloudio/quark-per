<?php

namespace App\Admin\Metrics;

use Illuminate\Http\Request;
use QuarkCMS\QuarkAdmin\Metrics\Descriptions;
use QuarkCMS\Quark\Facades\DescriptionField;
use QuarkAdmin;
use DB;

class SystemInfo extends Descriptions
{
    /**
     * 卡片标题
     *
     * @var string
     */
    public $title = '系统信息';

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
        if(strpos(PHP_OS,"Linux")!==false) {
            $os = "linux";
        } else if(strpos(PHP_OS,"WIN")!==false) {
            $os = "windows";
        } else {
            $os = PHP_OS;
        }

        $version = config('database.default') === 'sqlite' ? "sqlite_version()":"version()";

        return [
            DescriptionField::text('系统版本')->value(QuarkAdmin::version()),

            DescriptionField::text('服务器操作系统')->value($os),

            DescriptionField::text('Laravel版本')->value(app()::VERSION),

            DescriptionField::text('运行环境')->value(
                substr($_SERVER['SERVER_SOFTWARE'],0,50)
            ),

            DescriptionField::text('MYSQL版本')->value(
                DB::select("select ".$version)[0]->$version
            ),

            DescriptionField::text('上传限制')->value(ini_get('upload_max_filesize'))
        ];
    }
}