<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use QuarkCMS\QuarkAdmin\Models\File;
use QuarkCMS\QuarkAdmin\Models\FileCategory;
use OSS\OssClient;
use OSS\Core\OssException;
use QuarkCMS\QuarkAdmin\Table;

class FileController extends Controller
{
    public $title = '文件';

    /**
     * 列表页面
     *
     * @param  Request  $request
     * @return Response
     */
    protected function table()
    {
        $table = new Table(new File);
        $table->headerTitle($this->title.'列表');
        
        $table->column('id','序号');
        $table->column('name','名称');
        $table->column('size','大小')->sorter();
        $table->column('ext','扩展名');
        $table->column('created_at','上传时间');
        $table->column('status','状态')->using(['1'=>'正常','0'=>'禁用'])->width(60);
        $table->column('actions','操作')->width(180)->actions(function($action,$row) {

            // 根据不同的条件定义不同的A标签形式行为
            if($row['status'] === 1) {
                $action->a('禁用')
                ->withPopconfirm('确认要禁用数据吗？')
                ->model()
                ->where('id','{id}')
                ->update(['status'=>0]);
            } else {
                $action->a('启用')
                ->withPopconfirm('确认要启用数据吗？')
                ->model()
                ->where('id','{id}')
                ->update(['status'=>1]);
            }

            // 下载文件
            $action->a('下载')->link(backend_url('api/admin/file/download?id='.$row['id'],true),'_blank');

            $action->a('删除')
            ->withPopconfirm('确认要删除吗？')
            ->api('admin/file/delete?id='.$row['id']);

            return $action;
        });

        // 批量操作
        $table->batchActions(function($action) {
            $action->a('批量删除')
            ->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！')
            ->api('admin/file/delete');

            $action->a('批量禁用')
            ->withPopconfirm('确认要禁用吗？')
            ->model()
            ->whereIn('id','{ids}')
            ->update(['status'=>0]);

            $action->a('批量启用')
            ->withPopconfirm('确认要启用吗？')
            ->model()
            ->whereIn('id','{ids}')
            ->update(['status'=>1]);
        });

        // 搜索
        $table->search(function($search) {

            $search->where('name', '搜索内容',function ($model) {
                $model->where('name', 'like', "%{input}%");
            })->placeholder('名称');

            $search->equal('status', '所选状态')
            ->select([''=>'全部', 1=>'正常', 0=>'已禁用'])
            ->placeholder('选择状态')
            ->width(110);

            $search->between('created_at', '上传时间')->datetime();
        });

        $table->model()->orderBy('id','desc')->paginate(request('pageSize',10));

        return $table;
    }

    /**
     * 改变多个数据状态
     *
     * @param  Request  $request
     * @return Response
     */
    public function delete(Request $request)
    {
        $id = $request->input('id');

        if(empty($id)) {
            return error('参数错误！');
        }

        $query = File::query();

        if(is_array($id)) {
            $query->whereIn('id',$id);
        } else {
            $query->where('id',$id);
        }

        $files = $query->get();

        foreach ($files as $key => $file) {
            // 阿里云存储
            if(strpos($file->path,'http') !== false) {
                $accessKeyId = web_config('OSS_ACCESS_KEY_ID');
                $accessKeySecret = web_config('OSS_ACCESS_KEY_SECRET');
                $endpoint = web_config('OSS_ENDPOINT');
                $bucket = web_config('OSS_BUCKET');
    
                $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

                $path = explode('/',$file->path);
                $count = count($path);
                $object = $path[$count-2].'/'.$path[$count-1];
                
                $ossClient->deleteObject($bucket, $object);
            } else {
                Storage::delete(storage_path('app/').$file->path);
            }
        }

        $query1 = File::query();

        if(is_array($id)) {
            $query1->whereIn('id',$id);
        } else {
            $query1->where('id',$id);
        }

        $result = $query1->delete();

        if ($result) {
            return success('操作成功！');
        } else {
            return error('操作失败！');
        }
    }

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