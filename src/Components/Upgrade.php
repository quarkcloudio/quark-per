<?php

namespace QuarkCMS\QuarkAdmin\Components;

use QuarkCMS\QuarkAdmin\Element;
use QuarkCMS\QuarkAdmin\Components\Upgrade\Step;
use GuzzleHttp\Client;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Storage;

class Upgrade extends Element
{
    /**
     * 图钉按钮距离窗口底部达到指定偏移量后触发
     *
     * @var number
     */
    public $offsetBottom = 20;

    /**
     * 图钉按钮距离窗口顶部达到指定偏移量后触发
     *
     * @var number
     */
    public $offsetTop = 20;

    /**
     * 默认提示信息
     *
     * @var string
     */
    public $tip = '检查更新';

    /**
     * 检查更新的接口
     *
     * @var string
     */
    public $api = null;

    /**
     * 更新的步骤
     *
     * @var array|object
     */
    public $steps = [];

    /**
     * packagist.org上的包名
     *
     * @var string
     */
    public $packageName = null;

    /**
     * 初始化组件
     *
     * @param  string  $tip
     * @param  string  $api
     * @return void
     */
    public function __construct($tip = null,$api = null) {
        $this->component = 'upgrade';

        if(!empty($tip)) {
            $this->tip = $tip;
        }

        if(!empty($api)) {
            $this->api = $api;
        }

        $this->style = ['float'=>'right'];
    }

    /**
     * 图钉按钮距离窗口底部达到指定偏移量后触发
     *
     * @param  number  $offsetBottom
     * @return $this
     */
    public function offsetBottom($offsetBottom)
    {
        $this->offsetBottom = $offsetBottom;
        return $this;
    }

    /**
     * 图钉按钮距离窗口顶部达到指定偏移量后触发
     *
     * @param  number  $offsetTop
     * @return $this
     */
    public function offsetTop($offsetTop)
    {
        $this->offsetTop = $offsetTop;
        return $this;
    }

    /**
     * 默认提示信息
     *
     * @param  string  $tip
     * @return $this
     */
    public function tip($tip)
    {
        $this->tip = $tip;
        return $this;
    }

    /**
     * 检查更新的接口
     *
     * @param  string  $api
     * @return $this
     */
    public function api($api)
    {
        $this->api = $api;
        return $this;
    }

    /**
     * 升级步骤
     *
     * @param string $title
     * @param string $api
     * @return Step
     */
    public function step($title, $api = '')
    {
        $step = new Step($title, $api);
        $this->steps[] = $step;

        return $step;
    }

    /**
     * 设置包名
     *
     * @param string $packageName
     * @return $this
     */
    public function packageName($packageName)
    {
        $this->packageName = $packageName;

        return $this;
    }

    /**
     * 获取packagist.org的列表
     *
     * @param string $packageName
     * @return array
     */
    public function getPackages($packageName = null)
    {
        if($packageName) {
            $this->packageName = $packageName;
        }

        if(empty($this->packageName)) {
            return false;
        }

        $client = new Client();
        $getContent = $client->request('GET', 'https://repo.packagist.org/p/'.$this->packageName.'.json')->getBody()->getContents();

        $content = json_decode($getContent, true);
        $packages = Arr::sort($content['packages'][$this->packageName]);

        cache(['packages' => $packages], 3600);

        return $packages;
    }

    /**
     * 下载指定版本的Package
     *
     * @param string $version
     * @return array
     */
    public function downloadPackage($version = null)
    {
        $packages = cache('packages');

        if(empty($version) || empty($packages)) {
            return false;
        }

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

        return $result;
    }

    /**
     * 解压下载的指定版本的Package
     *
     * @param string $version
     * @return array
     */
    public function extractPackage($version = null)
    {
        if(empty($version)) {
            return false;
        }

        $path = storage_path('app/').'public/uploads/files/'.$version.".zip";
        $outPath = storage_path('app/').'public/uploads/files/'.$version.'/';

        $zip = new \ZipArchive();

        $result = $zip->open($path);

        if ($result === true) {
          $zip->extractTo($outPath);
          $zip->close();
        }

        return $result;
    }

    /**
     * 更新Package文件
     *
     * @param string $version
     * @return array
     */
    public function updatePackageFiles($version = null)
    {
        if(empty($version)) {
            return false;
        }

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

        return true;
    }

    /**
     * 组件json序列化
     *
     * @return array
     */
    public function jsonSerialize()
    {
        $this->key(json_encode($this));

        return array_merge([
            'offsetBottom' => $this->offsetBottom,
            'offsetTop' => $this->offsetTop,
            'tip' => $this->tip,
            'api' => $this->api,
            'steps' => $this->steps
        ], parent::jsonSerialize());
    }
}
