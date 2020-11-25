<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use QuarkCMS\QuarkAdmin\Container;
use QuarkCMS\QuarkAdmin\Card;
use QuarkCMS\QuarkAdmin\Quark;
use QuarkCMS\QuarkAdmin\Show;
use QuarkCMS\QuarkAdmin\Components\Statistic;
use QuarkCMS\QuarkAdmin\Components\Upgrade;
use DB;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {
        $content[] = $this->statistic();

        $shows[] = $this->systemInfo();

        $shows[] = $this->teamInfo();

        $content[] = Card::make()
        ->style(['marginTop'=>'8px'])
        ->ghost()
        ->gutter(8)
        ->content($shows);

        // $content[] = $this->upgrade();

        $container = Container::make('仪表盘',$content);

        return success('获取成功！','',$container);
    }

    /**
     * 统计卡牌
     *
     * @param  void
     * @return array
     */
    protected function statistic()
    {
        $adminCount = DB::table('admins')->where('status',1)->where('deleted_at',null)->count();
        $statistic1 = Statistic::make()
        ->title('管理员数')
        ->precision(0)
        ->valueStyle(['color' => '#3f8600'])
        ->value($adminCount);

        $cards[] = Card::make()->style(['padding'=>'10px'])->colSpan(6)->content($statistic1);

        $logCount = DB::table('action_logs')->where('status',1)->count();
        $statistic2 = Statistic::make()
        ->title('日志数量')
        ->precision(0)
        ->valueStyle(['color' => '#999999'])
        ->value($logCount);

        $cards[] = Card::make()->style(['padding'=>'10px'])->colSpan(6)->content($statistic2);

        $pictureCount = DB::table('pictures')->where('status',1)->count();
        $statistic3 = Statistic::make()
        ->title('图片数量')
        ->precision(0)->valueStyle(['color' => '#cf1322'])
        ->value($pictureCount);

        $cards[] = Card::make()->style(['padding'=>'10px'])->colSpan(6)->content($statistic3);

        $fileCount = DB::table('files')->where('status',1)->count();
        $statistic4 = Statistic::make()
        ->title('文件数量')
        ->precision(0)->valueStyle(['color' => '#cf1322'])
        ->value($fileCount);

        $cards[] = Card::make()
        ->style(['padding'=>'10px'])
        ->colSpan(6)
        ->content($statistic4);

        $content = Card::make()
        ->ghost()
        ->gutter(8)
        ->content($cards);

        return $content;
    }

    /**
     * 系统信息
     *
     * @param  void
     * @return array
     */
    protected function systemInfo()
    {
        $show = Show::make()->title('系统信息');
        $show->field('QuarkAdmin版本')->value(Quark::version());

        if(strpos(PHP_OS,"Linux")!==false) {
            $os = "linux";
        } else if(strpos(PHP_OS,"WIN")!==false) {
            $os = "windows";
        } else {
            $os = PHP_OS;
        }
        $show->field('服务器操作系统')->value($os);

        $show->field('Laravel版本')->value(app()::VERSION);
        $show->field('运行环境')->value(substr($_SERVER['SERVER_SOFTWARE'],0,50));

        $version = config('database.default') === 'sqlite' ? "sqlite_version()":"version()";
        $show->field('MYSQL版本')->value(DB::select("select ".$version)[0]->$version);
        $show->field('上传限制')->value(ini_get('upload_max_filesize'));

        $content = Card::make()
        ->colSpan(12)
        ->content($show);

        return $content;
    }

    /**
     * 团队信息
     *
     * @param  void
     * @return array
     */
    protected function teamInfo()
    {
        $show = Show::make()->title('团队信息');
        $show->field('作者')->value('tangtanglove');
        $show->field('联系方式')->value('dai_hang_love@126.com');
        $show->field('官方网址')->link('http://www.quarkcms.com','_blank')->value('www.quarkcms.com');
        $show->field('文档地址')->link('http://www.quarkcms.com','_blank')->value('查看文档');
        $show->field('BUG反馈')->link('https://github.com/quarkcms/quark-admin/issues','_blank')->value('提交BUG');
        $show->field('代码仓储')->link('https://github.com/quarkcms/quark-admin','_blank')->value('Github');

        $content = Card::make()
        ->colSpan(12)
        ->content($show);

        return $content;
    }

    /**
     * 升级信息
     *
     * @param  void
     * @return array
     */
    protected function upgrade()
    {
        $upgrade = Upgrade::make('检查更新',backend_url('api/admin/upgrade/index'));

        $upgrade->step('下载文件',backend_url('api/admin/upgrade/download'))->percent(20)->tip('正在下载软件包...');
        $upgrade->step('解压文件',backend_url('api/admin/upgrade/extract'))->percent(20)->tip('正在解压软件包...');
        $upgrade->step('更新程序',backend_url('api/admin/upgrade/updateFile'))->percent(20)->tip('正在更新程序...');
        $upgrade->step('更新数据库',backend_url('api/admin/upgrade/updateDatabase'))->percent(20)->tip('正在更新数据库...');
        $upgrade->step('清除缓存',backend_url('api/admin/upgrade/clearCache'))->percent(10)->tip('正在清理缓存...');
        $upgrade->step('升级完成',backend_url('api/admin/upgrade/finish'))->percent(10)->tip('请稍后，马上完成更新...');

        return $upgrade;
    }
}
