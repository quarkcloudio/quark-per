<?php

namespace QuarkCMS\QuarkAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use QuarkCMS\QuarkAdmin\Helper;
use Artisan;

class DashboardController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index()
    {

        $result['app_version'] = config('quark.version');

        return $this->success('获取成功！','',$result);
    }

    /**
     * 清空缓存
     * @author  tangtanglove <dai_hang_love@126.com>
     */
    public function clearCache(Request $request)
    {
        $result = Artisan::call('config:clear');

        if($result !== false) {
            return $this->success('操作成功！');
        } else {
            return $this->error('操作失败！');
        }
    }

    // 获取当前年月的开始与结束日期
    protected function getMonth($month,$type)
    {
        $startDate = date("Y-".$month."-01 00:00:00");
        $endDate = date('Y-m-d', mktime(23, 59, 59, date('m', strtotime($startDate))+1, 00));
        if($type == 1) {
            return $startDate;
        } elseif($type == 2) {
            return $endDate;
        }
    }

    /**
     * 版本升级
     * @author  tangtanglove <dai_hang_love@126.com>
     */
    public function update(Request $request)
    {
        $header['Accept'] = 'application/json';

        $repository = json_decode(Helper::curl('https://api.github.com/repos/tangtanglove/fullstack-backend/releases/latest?access_token='.base64_decode('YWM1MWIzYWQ5NjMzMGNlNGZlMTMyYTVhNWY4MDM2ZWEyM2QwY2ZjMw=='),false,'get',$header,1),true);

        $result['app_version'] = config('app.version');
        $result['repository'] = $repository;

        $result['can_update'] = false;

        if(isset($repository['name'])) {
            if($repository['name'] != $result['app_version']) {
                $result['can_update'] = true;
            }
        }

        return $this->success('获取成功！','',$result);
    }

    /**
     * 下载最新版本文件
     * @author  tangtanglove <dai_hang_love@126.com>
     */
    public function download(Request $request)
    {
        $version   = $request->get('version');

        // $url = 'https://github.com/tangtanglove/fullstack-backend/archive/'.$version.'.zip';
        // $file = readfile($url);

        $url ='https://dev.tencent.com/u/tangtanglove/p/fullstack-backend/git/archive/'.$version.'.zip';
        $file = Helper::curl($url,false,'get',false,1);

        // 默认本地上传
        $path = 'uploads/files/'.$version.".zip";

        $result = Storage::disk('public')->put($path,$file);

        if($result) {
            return $this->success('文件下载成功！','',$path);
        } else {
            return $this->error('文件下载失败！');
        }
    }

    /**
     * 解压程序包
     * @author  tangtanglove <dai_hang_love@126.com>
     */
    public function extract(Request $request)
    {
        $version   = $request->get('version');

        $path = storage_path('app/').'public/uploads/files/'.$version.".zip";
        $outPath = storage_path('app/').'public/uploads/files/';

        $zip = new \ZipArchive();

        $result = $zip->open($path);

        if ($result === true) {
          $zip->extractTo($outPath);
          $zip->close();
        }

        if($result) {
            return $this->success('文件解压成功！');
        } else {
            return $this->error('文件解压失败！');
        }
    }

    /**
     * 更新文件
     * @author  tangtanglove <dai_hang_love@126.com>
     */
    public function updateFile(Request $request)
    {
        $version   = $request->get('version');

        $path = storage_path('app/').'public/uploads/files/fullstack-backend-'.$version;

        $dirs = Helper::getDir($path);
        $files = Helper::getFileLists($path);

        foreach ($dirs as $key => $value) {
            Helper::copyDirToDir(storage_path('app/').'public/uploads/files/fullstack-backend-'.$version.'/'.$value,base_path());
        }

        foreach ($files as $key => $value) {
            Helper::copyFileToDir(storage_path('app/').'public/uploads/files/fullstack-backend-'.$version.'/'.$value,base_path());
        }

        return $this->success('程序更新成功！');
    }

    /**
     * 更新数据库
     * @author  tangtanglove <dai_hang_love@126.com>
     */
    public function updateDatabase(Request $request)
    {
        $result = Artisan::call('migrate');

        if($result !== false) {
            return $this->success('数据库更新成功！','',$result);
        } else {
            return $this->error('数据库更新失败！',$result);
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

        Helper::modifyEnv($data);

        $result = Artisan::call('config:clear');

        if($result !== false) {
            return $this->success('更新成功！');
        } else {
            return $this->error('更新失败！');
        }
    }
}
