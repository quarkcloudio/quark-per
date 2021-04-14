<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use QuarkCMS\QuarkAdmin\Components\Upgrade;

class UpgradeController extends Controller
{
    /**
     * 更新组件对象
     *
     * @var object
     */
    public $upgrade = null;
    
    /**
     * 初始化
     *
     * @param  void
     * @return void
     */
    public function __construct() {
        // $this->upgrade = Upgrade::make()->packageName('quarkcms/quark-cms');
    }

    /**
     * 版本升级
     * @author  tangtanglove <dai_hang_love@126.com>
     */
    public function index(Request $request)
    {
        // $packages = $this->upgrade->getPackages();

        // $result['app_version'] = config('quark.version');
        // foreach ($packages as $key => $value) {
        //     if($value['version'] > config('quark.version')) {
        //         $result['can_upgrade'] = true;
        //         $result['next_package'] = $value;
        //         break;
        //     }
        // }

        // return success('有新版可以更新','',$result);
    }

    /**
     * 下载最新版本文件
     * @author  tangtanglove <dai_hang_love@126.com>
     */
    public function download(Request $request)
    {
        $version = $request->get('version');

        $result = $this->upgrade->downloadPackage($version);

        if($result) {
            return success('文件下载成功！');
        } else {
            return error('文件下载失败！');
        }
    }

    /**
     * 解压程序包
     * @author  tangtanglove <dai_hang_love@126.com>
     */
    public function extract(Request $request)
    {
        $version   = $request->get('version');

        $result = $this->upgrade->extractPackage($version);

        if($result) {
            return success('文件解压成功！');
        } else {
            return error('文件解压失败！');
        }
    }

    /**
     * 更新文件
     * @author  tangtanglove <dai_hang_love@126.com>
     */
    public function updateFile(Request $request)
    {
        $version   = $request->get('version');

        $result = $this->upgrade->updatePackageFiles($version);

        if($result) {
            return success('程序更新成功！');
        } else {
            return error('程序更新失败！');
        }
    }

    /**
     * 更新数据库
     * @author  tangtanglove <dai_hang_love@126.com>
     */
    public function updateDatabase(Request $request)
    {
        $result = Artisan::call('migrate');

        if($result !== false) {
            return success('数据库更新成功！','',$result);
        } else {
            return error('数据库更新失败！',$result);
        }
    }

    /**
     * 清除缓存
     * @author  tangtanglove <dai_hang_love@126.com>
     */
    public function clearCache(Request $request)
    {
        $result = Artisan::call('cache:clear');

        if($result !== false) {
            return success('缓存清除成功！','',$result);
        } else {
            return error('缓存清除失败！',$result);
        }
    }

    /**
     * 完成，更新程序版本
     * @author  tangtanglove <dai_hang_love@126.com>
     */
    public function finish(Request $request)
    {
        $version   = $request->get('version');

        $data = [
            'APP_VERSION' => $version
        ];

        modify_env($data);

        $result = Artisan::call('config:clear');

        if($result !== false) {
            return success('更新成功！');
        } else {
            return error('更新失败！');
        }
    }
}