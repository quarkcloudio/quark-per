<?php

namespace QuarkCloudIO\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use QuarkCloudIO\QuarkAdmin\Models\File;
use QuarkCloudIO\QuarkAdmin\Models\FileCategory;
use OSS\OssClient;
use OSS\Core\OssException;

class FileController extends Controller
{
    /**
     * 上传附件
     *
     * @param  Request  $request
     * @return Response
     */
    public function upload(Request $request)
    {
        $ossOpen = web_config('OSS_OPEN');

        if($ossOpen == 1) {
            $driver = 'oss';
        } else {
            $driver = 'local';
        }

        switch ($driver) {
            case 'oss':
                // 阿里云上传
                $result = $this->ossUpload($request);
                break;
            default:
                // 默认本地上传
                $result = $this->localUpload($request);
                break;
        }
        return $result;
    }

    /**
     * 本地上传文件
     *
     * @param  Request  $request
     * @return Response
     */
    protected function localUpload($request)
    {
        $file = $request->file('file');
        $md5  = md5_file($file->getRealPath());
        $name = $file->getClientOriginalName();
        $ext = $file->getClientOriginalExtension();

        $hasFile = File::where('md5',$md5)->where('name',$name)->first();

        // 不存在文件，则插入数据库
        if(empty($hasFile)) {

            $saveFileName = Str::random(40).'.'.$ext;

            $path = $file->storeAs('public/uploads/files',$saveFileName);

            // 获取文件url，用于外部访问
            $url = Storage::url($path);

            // 获取文件大小
            $size = Storage::size($path);

            // 数据
            $data['obj_type'] = 'ADMINID';
            $data['obj_id'] = ADMINID;
            $data['name'] = $name;
            $data['size'] = $size;
            $data['md5'] = $md5;
            $data['path'] = $path;
            $data['ext'] = $ext;

            // 插入数据库
            $file = File::create($data);
            $fileId = $file->id;
        } else {
            $fileId = $hasFile->id;

            if(strpos($hasFile->path,'http') !== false) {
                $url = $hasFile->path;
            } else {
                // 获取文件url，用于外部访问
                $url = Storage::url($hasFile->path);
            }

            // 获取文件大小
            $size = $hasFile->size;
        }

        $result['id'] = $fileId;
        $result['name'] = $name;
        $result['url'] = asset($url);
        $result['size'] = $size;

        // 返回数据
        return success('上传成功！','',$result);
    }

    /**
     * 阿里云OSS上传文件
     *
     * @param  Request  $request
     * @return Response
     */
    protected function ossUpload($request)
    {
        $file = $request->file('file');

        $accessKeyId = web_config('OSS_ACCESS_KEY_ID');
        $accessKeySecret = web_config('OSS_ACCESS_KEY_SECRET');
        $endpoint = web_config('OSS_ENDPOINT');
        $bucket = web_config('OSS_BUCKET');
        // 设置自定义域名。
        $myDomain = web_config('OSS_MYDOMAIN');

        try {
            $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

            // 如果设置自定义域名
            if(!empty($myDomain)) {
                // 查看CNAME记录。
                $cnameConfig = $ossClient->getBucketCname($bucket);

                $hasCname = false;
                foreach ($cnameConfig as $key => $value) {
                    if($value['Domain'] == $myDomain) {
                        $hasCname = true;
                    }
                }

                // 未添加CNAME记录，则程序自动添加
                if($hasCname === false) {
                    // 添加CNAME记录。
                    $ossClient->addBucketCname($bucket, $myDomain);
                }
            }

        } catch (OssException $e) {
            print $e->getMessage();
        }

        $object = 'files/'.Str::random(40).'.'.$file->getClientOriginalExtension();
        $content = file_get_contents($file->getRealPath());

        $md5 = md5($content);
        $name = $file->getClientOriginalName();
        $ext = $file->getClientOriginalExtension();
        // 判断文件是否已经上传
        $hasFile = File::where('md5',$md5)->where('name',$name)->first();

        // 不存在文件，则插入数据库
        if(empty($hasFile)) {

            // 上传到阿里云
            try {
                $ossResult = $ossClient->putObject($bucket, $object, $content);
            } catch (OssException $e) {
                $ossResult = $e->getMessage();
                // 返回数据
                return error('上传失败！');
            }

            // 数据
            $data['obj_type'] = 'ADMINID';
            $data['obj_id'] = ADMINID;
            $data['name'] = $name;
            $data['size'] = $ossResult['info']['size_upload'];
            $data['md5'] = $md5;
            $data['ext'] = $ext;

            // 设置自定义域名，则文件url执行自定义域名
            if(!empty($myDomain)) {
                $data['path'] = str_replace($bucket.'.'.$endpoint,$myDomain,$ossResult['info']['url']);
                $data['path'] = str_replace('http','https',$data['path']);
            } else {
                $data['path'] = $ossResult['info']['url'];
                $data['path'] = str_replace('http','https',$data['path']);
            }

            // 插入数据库
            $file = File::create($data);
            $fileId = $file->id;

            // 获取文件url，用于外部访问
            $url = $data['path'];

            // 获取文件大小
            $size = $ossResult['info']['size_upload'];
        } else {
            $fileId = $hasFile->id;

            if(strpos($hasFile->path,'http') !== false) { 
                $url = $hasFile->path;
            } else {
                // 获取文件url，用于外部访问
                $url = Storage::url($hasFile->path);
            }

            // 获取文件大小
            $size = $hasFile->size;
        }

        $result['id'] = $fileId;
        $result['name'] = $name;
        $result['url'] = $url;
        $result['size'] = $size;

        // 返回数据
        return success('上传成功！','',$result);
    }

    /**
     * 改变多个数据状态
     *
     * @param  Request  $request
     * @return Response
     */
    public function download(Request $request)
    {
        $id = $request->get('id');

        if(empty($id)) {
            return error('参数错误！');
        }

        $file = File::where('id',$id)->first();

        if(empty($file)) {
            return error('文件不存在！');
        }

        if(strpos($file['path'],'http') !== false) {
            $path = $file['path'];
        } else {
            $path = '//'.$_SERVER['HTTP_HOST'].Storage::url($file['path']);
        }

        return redirect($path);
    }
}