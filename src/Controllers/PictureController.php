<?php

namespace QuarkCMS\QuarkAdmin\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Input;
use QuarkCMS\QuarkAdmin\Helper;
use QuarkCMS\QuarkAdmin\Models\Picture;
use QuarkCMS\QuarkAdmin\Models\PictureCategory;
use OSS\OssClient;
use OSS\Core\OssException;
use Session;
use Cache;
use Quark;

class PictureController extends QuarkController
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
        $grid = Quark::grid(new Picture)->title($this->title);
        $grid->column('picture_id','图片')->image();
        $grid->column('name','名称');
        $grid->column('size','大小')->sorter();
        $grid->column('width','宽度');
        $grid->column('height','高度');
        $grid->column('ext','扩展名');
        $grid->column('created_at','上传时间');
        $grid->column('status','状态')->editable('switch',[
            'on'  => ['value' => 1, 'text' => '正常'],
            'off' => ['value' => 0, 'text' => '禁用']
        ])->width(100);

        $grid->column('actions','操作')->width(100)->rowActions(function($rowAction) {
            $rowAction->button('delete', '删除')
            ->type('default',true)
            ->size('small')
            ->setAction('admin/picture/delete')
            ->withPopconfirm('确认要删除吗？');
        },'button');

        // 头部操作
        $grid->actions(function($action) {
            $action->button('refresh', '刷新');
        });

        // select样式的批量操作
        $grid->batchActions(function($batch) {
            $batch->option('', '批量操作');
            $batch->option('resume', '启用')->model(function($model) {
                $model->update(['status'=>1]);
            });
            $batch->option('forbid', '禁用')->model(function($model) {
                $model->update(['status'=>0]);
            });
            $batch->option('delete', '删除')->model(function($model) {
                $model->delete();
            })->withConfirm('确认要删除吗？','删除后数据将无法恢复，请谨慎操作！');
        })->style('select',['width'=>120]);

        $grid->search(function($search) {
            $search->equal('status', '所选状态')->select([''=>'全部',1=>'正常',0=>'已禁用'])->placeholder('选择状态')->width(110);
            $search->where('name', '搜索内容',function ($query) {
                $query->where('name', 'like', "%{input}%");
            })->placeholder('名称');
            $search->between('created_at', '上传时间')->datetime()->advanced();
        })->expand(false);

        $grid->model()->select('id as picture_id','pictures.*')->paginate(10);

        return $grid;
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
                $value['path'] = Helper::getPicture($value['id']);
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

        return $this->success('获取成功！','',$picture);
    }

    /**
     * 删除图片
     *
     * @param  Request  $request
     * @return Response
     */
    public function delete(Request $request)
    {
        $id = $request->json('id');

        if(empty($id)) {
            return $this->error('参数错误！');
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
                $accessKeyId = Helper::getConfig('OSS_ACCESS_KEY_ID');
                $accessKeySecret = Helper::getConfig('OSS_ACCESS_KEY_SECRET');
                $endpoint = Helper::getConfig('OSS_ENDPOINT');
                $bucket = Helper::getConfig('OSS_BUCKET');

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
            return $this->success('操作成功！');
        } else {
            return $this->error('操作失败！');
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
        $ossOpen = Helper::config('OSS_OPEN');

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
            return $this->error('格式错误！');
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
                return $this->error('只能上传jpg,jpeg,png,jpe,gif,bmp格式图片');
                break;
        }

        // 图片名称
        $name = Helper::makeRand(40,true).$fileType;

        $ossOpen = Helper::config('OSS_OPEN');

        if($ossOpen == 1) {
            $driver = 'oss';
        } else {
            $driver = 'local';
        }

        switch ($driver) {
            case 'oss':

                // 阿里云上传
                $accessKeyId = Helper::config('OSS_ACCESS_KEY_ID');
                $accessKeySecret = Helper::config('OSS_ACCESS_KEY_SECRET');
                $endpoint = Helper::config('OSS_ENDPOINT');
                $bucket = Helper::config('OSS_BUCKET');
                // 设置自定义域名。
                $myDomain = Helper::config('OSS_MYDOMAIN');
        
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
                    return $this->error('上传失败！');
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
                    return $this->error('上传失败！');
                }

                break;
        }

        // 返回数据
        return $this->success('上传成功！','',$result);
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
        return $this->success('上传成功！','',$result);
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

        $accessKeyId = Helper::config('OSS_ACCESS_KEY_ID');
        $accessKeySecret = Helper::config('OSS_ACCESS_KEY_SECRET');
        $endpoint = Helper::config('OSS_ENDPOINT');
        $bucket = Helper::config('OSS_BUCKET');
        // 设置自定义域名。
        $myDomain = Helper::config('OSS_MYDOMAIN');

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

        $object = 'pictures/'.Helper::makeRand(40,true).'.'.$file->getClientOriginalExtension();
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
                return $this->error('上传失败！');
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
        return $this->success('上传成功！','',$result);
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
            return $this->error('参数错误！');
        }

        $picture = Picture::where('id',$id)->first();

        if(empty($picture)) {
            return $this->error('文件不存在！');
        }

        if(strpos($picture['path'],'http') !== false) {
            $path = $picture['path'];
        } else {
            $path = '//'.$_SERVER['HTTP_HOST'].Storage::url($picture['path']);
        }

        return redirect($path);
    }
}