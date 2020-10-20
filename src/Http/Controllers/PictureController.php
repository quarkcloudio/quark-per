<?php

namespace QuarkCMS\QuarkAdmin\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use QuarkCMS\QuarkAdmin\Models\Picture;
use QuarkCMS\QuarkAdmin\Models\PictureCategory;
use OSS\OssClient;
use OSS\Core\OssException;
use QuarkCMS\QuarkAdmin\Table;

class PictureController extends Controller
{
    public $title = '图片';

    /**
     * 列表页面
     *
     * @param  Request  $request
     * @return Response
     */
    protected function table()
    {
        $table = new Table(new Picture);
        $table->headerTitle($this->title.'列表');
        
        $table->column('id','序号');
        $table->column('picture_id','图片')->image();
        $table->column('name','名称');
        $table->column('size','大小')->sorter();
        $table->column('width','宽度');
        $table->column('height','高度');
        $table->column('ext','扩展名');
        $table->column('created_at','上传时间');
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
            $action->a('下载')->link(backend_url('api/admin/picture/download?id='.$row['id'],true),'_blank');

            $action->a('删除')
            ->withPopconfirm('确认要删除吗？')
            ->api('admin/picture/delete?id='.$row['id']);

            return $action;
        });

        // 批量操作
        $table->batchActions(function($action) {
            $action->a('批量删除')
            ->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！')
            ->api('admin/picture/delete');

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

        $table->model()->orderBy('id','desc')->select('id as picture_id','pictures.*')->paginate(request('pageSize',10));

        return $table;
    }

    /**
     * 编辑器图片选择
     *
     * @param  Request  $request
     * @return Response
     */
    public function getLists(Request $request)
    {
        $pictureCategoryId = request('pictureCategoryId');
        $pictureSearchName = request('pictureSearchName');
        $pictureSearchDate = request('pictureSearchDate');

        $query = Picture::query();

        if($pictureCategoryId) {
            $query->where('picture_category_id',$pictureCategoryId);
        }

        if($pictureSearchName) {
            $query->where('name','like',"%$pictureSearchName%");
        }

        if($pictureSearchDate) {
            $query->whereBetween('created_at', [$pictureSearchDate[0], $pictureSearchDate[1]]);
        }

        $pictures = $query->where('status',1)->orderBy('id','desc')->paginate(12);

        $pagination = [];
        $data = [];

        if($pictures) {
            $getPictures = $pictures->toArray();

            $data = $getPictures['data'];

            foreach ($data as $key => $value) {
                $value['path'] = get_picture($value['id']);
                $data[$key] = $value;
            }

            $pagination['defaultCurrent'] = 1;
            $pagination['current'] = $getPictures['current_page'];
            $pagination['pageSize'] = $getPictures['per_page'];
            $pagination['total'] = $getPictures['total'];
        }

        $categorys = PictureCategory::where('obj_type','ADMINID')->where('obj_id',ADMINID)->get();

        $picture['lists'] = $data;
        $picture['pagination'] = $pagination;
        $picture['categorys'] = $categorys;

        return success('获取成功！','',$picture);
    }

    /**
     * 删除图片
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

        $query = Picture::query();

        if(is_array($id)) {
            $query->whereIn('id',$id);
        } else {
            $query->where('id',$id);
        }

        $pictures = $query->get();

        foreach ($pictures as $key => $picture) {
            // 阿里云存储
            if(strpos($picture->path,'http') !== false) {
                $accessKeyId = web_config('OSS_ACCESS_KEY_ID');
                $accessKeySecret = web_config('OSS_ACCESS_KEY_SECRET');
                $endpoint = web_config('OSS_ENDPOINT');
                $bucket = web_config('OSS_BUCKET');

                $ossClient = new OssClient($accessKeyId, $accessKeySecret, $endpoint);

                $path = explode('/',$picture->path);
                $count = count($path);
                $object = $path[$count-2].'/'.$path[$count-1];
                
                $ossClient->deleteObject($bucket, $object);
            } else {
                Storage::delete(storage_path('app/').$picture->path);
            }
        }

        $query1 = Picture::query();

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
     * 上传图片
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
     * 上传图片
     *
     * @param  Request  $request
     * @return Response
     */
    public function uploadFromBase64(Request $request)
    {
        $file = $request->input('file');

        $fileArray = explode(',',$file);

        if(empty($fileArray)) {
            return error('格式错误！');
        }

        $fileHeader = $fileArray[0];
        $fileBody   = $fileArray[1];

        switch ($fileHeader) {
            case 'data:image/jpg;base64':
                $fileType = '.jpg';
                break;
            case 'data:image/jpeg;base64':
                $fileType = '.jpeg';
                break;
            case 'data:image/png;base64':
                $fileType = '.png';
                break;
            case 'data:image/jpe;base64':
                $fileType = '.jpe';
                break;
            case 'data:image/gif;base64':
                $fileType = '.gif';
                break;
            case 'data:image/bmp;base64':
                $fileType = '.bmp';
                break;
            default:
                return error('只能上传jpg,jpeg,png,jpe,gif,bmp格式图片');
                break;
        }

        // 图片名称
        $name = Str::random(40).$fileType;

        $ossOpen = web_config('OSS_OPEN');

        if($ossOpen == 1) {
            $driver = 'oss';
        } else {
            $driver = 'local';
        }

        switch ($driver) {
            case 'oss':

                // 阿里云上传
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
        
                $object = 'pictures/'.$name;
                $content = base64_decode($fileBody);
        
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
                $data['md5'] = md5($content);
    
                // 设置自定义域名，则文件url执行自定义域名
                if(!empty($myDomain)) {
                    $data['path'] = str_replace($bucket.'.'.$endpoint,$myDomain,$ossResult['info']['url']);
                    $data['path'] = str_replace('http','https',$data['path']);
                
                } else {
                    $data['path'] = $ossResult['info']['url'];
                    $data['path'] = str_replace('http','https',$data['path']);
                }
    
                // 插入数据库
                $picture = Picture::create($data);
                $pictureId = $picture->id;
    
                // 获取文件url，用于外部访问
                $url = $data['path'];
    
                // 获取文件大小
                $size = $ossResult['info']['size_upload'];
        
                $result['id'] = $pictureId;
                $result['name'] = $name;
                $result['url'] = $url;
                $result['size'] = $size;
        
                break;
            
            default:

                // 默认本地上传
                $uploadPath = 'uploads/pictures/'.$name;
                $getResult = Storage::disk('public')->put($uploadPath,base64_decode($fileBody));
                
                if($getResult) {
                    $path = 'public/'.$uploadPath;

                    // 数据
                    $data['obj_type'] = 'ADMINID';
                    $data['obj_id'] = ADMINID;
                    $data['name'] = $name;
                    $data['md5'] = md5_file(storage_path('app/').$path);
                    $data['path'] = $path;

                    // 插入数据库
                    $picture = Picture::create($data);
                    $pictureId = $picture->id;

                    // 获取文件url，用于外部访问
                    $url = Storage::url($path);

                    // 获取文件大小
                    $size = Storage::size($path);

                    $result['id'] = $pictureId;
                    $result['name'] = $name;
                    $result['url'] = asset($url);
                    $result['size'] = $size;

                } else {
                    return error('上传失败！');
                }

                break;
        }

        // 返回数据
        return success('上传成功！','',$result);
    }

    /**
     * 本地上传图片
     *
     * @param  Request  $request
     * @return Response
     */
    protected function localUpload($request)
    {
        $file = $request->file('file');
        $md5 = md5_file($file->getRealPath());
        $name = $file->getClientOriginalName();

        $hasPicture = Picture::where('md5',$md5)->where('name',$name)->first();

        // 不存在文件，则插入数据库
        if(empty($hasPicture)) {

            $path = $file->store('public/uploads/pictures');

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

            // 插入数据库
            $picture = Picture::create($data);
            $pictureId = $picture->id;
        } else {
            $pictureId = $hasPicture->id;

            if(strpos($hasPicture->path,'http') !== false) { 
                $url = $hasPicture->path;
            } else {
                // 获取文件url，用于外部访问
                $url = Storage::url($hasPicture->path);
            }

            // 获取文件大小
            $size = $hasPicture->size;
        }

        $result['id'] = $pictureId;
        $result['name'] = $name;
        $result['url'] = asset($url);
        $result['size'] = $size;

        // 返回数据
        return success('上传成功！','',$result);
    }

    /**
     * 阿里云OSS上传
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

        $object = 'pictures/'.Str::random(40).'.'.$file->getClientOriginalExtension();
        $content = file_get_contents($file->getRealPath());

        $md5 = md5($content);
        $name = $file->getClientOriginalName();

        // 判断文件是否已经上传
        $hasPicture = Picture::where('md5',$md5)->where('name',$name)->first();

        // 不存在文件，则插入数据库
        if(empty($hasPicture)) {

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

            // 设置自定义域名，则文件url执行自定义域名
            if(!empty($myDomain)) {
                $data['path'] = str_replace($bucket.'.'.$endpoint,$myDomain,$ossResult['info']['url']);
                $data['path'] = str_replace('http','https',$data['path']);
            } else {
                $data['path'] = $ossResult['info']['url'];
                $data['path'] = str_replace('http','https',$data['path']);
            }

            // 插入数据库
            $picture = Picture::create($data);
            $pictureId = $picture->id;

            // 获取文件url，用于外部访问
            $url = $data['path'];

            // 获取文件大小
            $size = $ossResult['info']['size_upload'];
        } else {
            $pictureId = $hasPicture->id;

            if(strpos($hasPicture->path,'http') !== false) { 
                $url = $hasPicture->path;
            } else {
                // 获取文件url，用于外部访问
                $url = Storage::url($hasPicture->path);
            }

            // 获取文件大小
            $size = $hasPicture->size;
        }

        $result['id'] = $pictureId;
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

        $picture = Picture::where('id',$id)->first();

        if(empty($picture)) {
            return error('文件不存在！');
        }

        if(strpos($picture['path'],'http') !== false) {
            $path = $picture['path'];
        } else {
            $path = '//'.$_SERVER['HTTP_HOST'].Storage::url($picture['path']);
        }

        return redirect($path);
    }
}