<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use GuzzleHttp\Client;
use Artisan;
use Arr;

class UpgradeController extends Controller
{
    /**
     * 版本升级
     * @author  tangtanglove <dai_hang_love@126.com>
     */
    public function index(Request $request)
    {
        $client = new Client();
        $getContent = $client->request('GET', 'https://repo.packagist.org/p/'.$this->packageName.'.json')->getBody()->getContents();

        $content = json_decode($getContent, true);
        $packages = Arr::sort($content['packages'][$this->packageName]);

        cache(['packages' => $packages], 3600);

        $result['app_version'] = config('quark.app.version');
        foreach ($packages as $key => $value) {
            if($value['version'] > config('quark.app.version')) {
                $result['can_upgrade'] = true;
                $result['next_package'] = $value;
                break;
            }
        }

        return success('获取成功！','',$result);
    }

    /**
     * 下载最新版本文件
     * @author  tangtanglove <dai_hang_love@126.com>
     */
    public function download(Request $request)
    {
        $version   = $request->get('version');

        $packages = cache('packages');

        foreach ($packages as $key => $value) {
            if($value['version'] == $version) {
                $reference = $value['dist']['reference'];
                break;
            }
        }

        $url ='https://mirrors.aliyun.com/composer/dists/'.$this->packageName.'/'.$reference.'.zip';
        $client = new Client();
        $file = $client->request('GET', $url)->getBody()->getContents();

        // 默认本地上传
        $path = 'uploads/files/'.$version.".zip";

        $result = Storage::disk('public')->put($path,$file);

        if($result) {
            return success('文件下载成功！','',$path);
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

        $path = storage_path('app/').'public/uploads/files/'.$version.".zip";
        $outPath = storage_path('app/').'public/uploads/files/'.$version.'/';

        $zip = new \ZipArchive();

        $result = $zip->open($path);

        if ($result === true) {
          $zip->extractTo($outPath);
          $zip->close();
        }

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

        $path = storage_path('app/').'public/uploads/files/'.$version;

        $dirs = get_folder_dirs($path);

        $filePath = $path.'/'.$dirs[0];

        $appDirs = get_folder_dirs($filePath);
        $appFiles = get_folder_files($filePath);

        foreach ($appDirs as $key => $value) {
            copy_dir_to_folder($path.'/'.$dirs[0].'/'.$value,base_path());
        }

        foreach ($appFiles as $key => $value) {
            copy_file_to_folder($path.'/'.$dirs[0].'/'.$value,base_path());
        }

        return success('程序更新成功！');
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