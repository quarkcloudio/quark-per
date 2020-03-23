<?php

namespace QuarkCMS\QuarkAdmin;

use QuarkCMS\QuarkAdmin\Models\ActionLog;
use QuarkCMS\QuarkAdmin\Models\Picture;
use QuarkCMS\QuarkAdmin\Models\File;
use QuarkCMS\QuarkAdmin\Models\Config;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use OSS\OssClient;
use OSS\Core\OssException;

class Helper
{
    /**
    * 错误时返回json数据
    * @author tangtanglove <dai_hang_love@126.com>
    */
    static function error($msg,$url = '')
    {
        $result['msg'] = $msg;
        $result['url'] = $url;
        $result['status'] = 'error';
        return self::unsetNull($result);
    }

    /**
    * 成功是返回json数据
    * @author tangtanglove <dai_hang_love@126.com>
    */
    static function success($msg,$url ='',$data = '',$status = 'success')
    {
        $result['msg'] = $msg;
        $result['url'] = $url;
        $result['data'] = $data;
        $result['status'] = $status;
        return self::unsetNull($result);
    }

    /**
     * 生成随机位数
     * @param  integer $len 长度
     * @return string
     */
    static function makeRand($len = 6,$string = false)
    {
        if($string) {
            $seed = 'ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz1234567890';
        } else {
            $seed = '0123456789';
        }

        return substr(str_shuffle(str_repeat($seed, $len)), 0, $len);
    }

    /**
    * 把返回的数据集转换成Tree
    * @param array $list 要转换的数据集
    * @param string $pid parent标记字段
    * @param string $level level标记字段
    * @return array
    */
    static function listToTree($list, $pk='id',$pid = 'pid',$child = '_child',$root=0) {
        // 创建Tree
        $tree = array();
        if(is_array($list)) {
            // 创建基于主键的数组引用
            $refer = array();
            foreach ($list as $key => $data) {
                $refer[$data[$pk]] =& $list[$key];
            }
            foreach ($list as $key => $data) {
                // 判断是否存在parent
                $parentId = $data[$pid];
                if ($root == $parentId) {
                    $tree[] =& $list[$key];
                }else{
                    if (isset($refer[$parentId])) {
                        $parent =& $refer[$parentId];
                        $parent[$child][] =& $list[$key];
                    }
                }
            }
        }
        return $tree;
    }

    /**
    * 把Tree转换为有序列表
    * @return array
    */
    static function treeToOrderList($arr,$level=0,$filed='name',$child='_child') {
        static $tree=array();
        if(is_array($arr)) {
            foreach ($arr as $key=>$val) {
                $val[$filed] = str_repeat('—', $level).$val[$filed];
                $tree[]=$val;
                if (isset($val[$child])) {
                    self::treeToOrderList($val[$child],$level+1,$filed,$child);
                }        
            }
        }
        return $tree;
    }

    /**
    * 记录日志
    * @author tangtanglove <dai_hang_love@126.com>
    */
	static function actionLog($data)
    {
        if (empty($data['url'])) {
            $data['url'] = $_SERVER['REQUEST_URI'];
        }
        
        if (!empty(auth()->user())) {
            $data['object_id'] = auth()->user()->id;
        }

        $data['ip'] = $_SERVER["REMOTE_ADDR"];
        ActionLog::create($data);
    }

    /**
    * 获取图片url
    * @author tangtanglove <dai_hang_love@126.com>
    */
	static function getPicture($id,$key=0,$field='path')
    {
        // 获取文件url，用于外部访问
        if(count(explode('[',$id))>1) {
            $ids = json_decode($id, true);
            if(isset($ids[$key])) {

                if($field == 'path') {
                    $field = 'url';
                }

                if(isset($ids[$key][$field])) {
                    return $ids[$key][$field];
                } else {
                    return '//'.$_SERVER['HTTP_HOST'].'/admin/default.png';
                }

            } else {
                return '//'.$_SERVER['HTTP_HOST'].'/admin/default.png';
            }
        }

        // 本身为图片地址，直接返回
        if(strpos($id,'http') !== false) {
            return $id;
        } else if(strpos($id,'public') !== false) {
            // 存在http，本身为图片地址
            $baseUrl = 'http://';
            if(self::config('SSL_OPEN') == 1) {
                $baseUrl = 'https://';
            }
            return $baseUrl.$_SERVER['HTTP_HOST'].Storage::url($id);
        }

        $picture = Picture::where('id',$id)->first();

        // 图片存在
        if(!empty($picture)) {
            if ($field == 'path') {
                // 存在http，本身为图片地址
                if(strpos($picture['path'],'http') !== false) {
                    $url = $picture['path'];
                } else {
                    $baseUrl = 'http://';
                    if(self::config('SSL_OPEN') == 1) {
                        $baseUrl = 'https://';
                    }
                    $url = $baseUrl.$_SERVER['HTTP_HOST'].Storage::url($picture['path']);
                }
                $result = $url;
            } else {
                $result = $picture[$field];
            }
            return $result;
        }
        
        return '//'.$_SERVER['HTTP_HOST'].'/admin/default.png';
    }

    /**
    * 获取文件
    * @author tangtanglove <dai_hang_love@126.com>
    */
	static function getFile($id,$field='path')
    {
        $file = File::where('id',$id)->first();
        if(!empty($file)) {
            $file = $file->toArray();
            if ($field == 'path') {
                if(strpos($file['path'],'http') !==false) {
                    return $file['path'];
                } else {
                    $baseUrl = 'http://';

                    if(self::config('SSL_OPEN') == 1) {
                        $baseUrl = 'https://';
                    }

                    return $baseUrl.$_SERVER['HTTP_HOST'].Storage::url($file['path']);
                }
            } else {
                return $file[$field];
            }
        }
    }

    /**
     * 字符串截取，支持中文和其他编码
     * static 
     * access public
     * @param string $str 需要转换的字符串
     * @param string $start 开始位置
     * @param string $length 截取长度
     * @param string $charset 编码格式
     * @param string $suffix 截断显示字符
     * return string
     */
    static function msubstr($str, $start=0, $length, $charset="utf-8")
    {
        if(function_exists("mb_substr")) {
            $slice = mb_substr($str, $start, $length, $charset);
        } elseif(function_exists('iconv_substr')) {
            $slice = iconv_substr($str,$start,$length,$charset);
            if(false === $slice) {
                $slice = '';
            }
        } else {
            $re['utf-8']   = "/[\x01-\x7f]|[\xc2-\xdf][\x80-\xbf]|[\xe0-\xef][\x80-\xbf]{2}|[\xf0-\xff][\x80-\xbf]{3}/";
            $re['gb2312'] = "/[\x01-\x7f]|[\xb0-\xf7][\xa0-\xfe]/";
            $re['gbk']    = "/[\x01-\x7f]|[\x81-\xfe][\x40-\xfe]/";
            $re['big5']   = "/[\x01-\x7f]|[\x81-\xfe]([\x40-\x7e]|\xa1-\xfe])/";
            preg_match_all($re[$charset], $str, $match);
            $slice = join("",array_slice($match[0], $start, $length));
        }

        $strlen=mb_strlen($str);
        if($strlen>$length) {
            $slice = $slice.'...';
        }
        return $slice;
    }

    /**
     * @param $url 请求网址
     * @param bool $params 请求参数
     * @param int $ispost 请求方式
     * @param bool $headers 请求头部
     * @param int $https https协议
     * @return bool|mixed
     */
    static function curl($url, $params = false, $method = 'get', $headers = false, $https = 0)
    {
        $httpInfo = array();
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_HTTP_VERSION, CURL_HTTP_VERSION_1_1);
        curl_setopt($ch, CURLOPT_USERAGENT, 'Mozilla/5.0 (Windows NT 10.0; WOW64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/41.0.2272.118 Safari/537.36');
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 30);
        curl_setopt($ch, CURLOPT_TIMEOUT, 30);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        if($headers) {
            curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        }

        if ($https) {
            curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE); // 对认证证书来源的检查
            curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, FALSE); // 从证书中检查SSL加密算法是否存在
        }

        if ($method == 'post') {
            curl_setopt($ch, CURLOPT_POST, true);

            if (is_array($params)) {
                $params = http_build_query($params);
                curl_setopt($ch, CURLOPT_POSTFIELDS, $params);
            }
            
            curl_setopt($ch, CURLOPT_URL, $url);
        } else {
            if ($params) {
                if (is_array($params)) {
                    $params = http_build_query($params);
                }
                curl_setopt($ch, CURLOPT_URL, $url . '?' . $params);
            } else {
                curl_setopt($ch, CURLOPT_URL, $url);
            }
        }

        $response = curl_exec($ch);

        if ($response === FALSE) {
            return false;
        }

        $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
        $httpInfo = array_merge($httpInfo, curl_getinfo($ch));
        curl_close($ch);
        return $response;
    }

    /**
    * 获取网站配置信息
    * @author tangtanglove <dai_hang_love@126.com>
    */
    static function config($name)
    {
        $config = Config::where('name',$name)->first();
        $value = '';
        if(!empty($config)) {
            $value = $config->value;
        }
        return $value;
    }

    /**
    * 把 null转换为空''
    * @author tangtanglove <dai_hang_love@126.com>
    */
    static function unsetNull($data)
    {
        $result = json_decode(str_replace(':null', ':""', json_encode($data)),true);
        if($result) {
            $data = $result;
        }
        return $data;
    }

    static function clientIp()
    {
        $keys = array('HTTP_X_FORWARDED_FOR', 'HTTP_CLIENT_IP', 'REMOTE_ADDR');
        foreach ($keys as $key) {
            if ( ! isset($_SERVER[$key])) {
                continue;
            }
            $ip = \array_filter(\explode(',', $_SERVER[$key]));
            $ip = \filter_var(\end($ip), FILTER_VALIDATE_IP);
            if ($ip) {
                return $ip;
            }
        }
        return '';
    }

    /**
    +----------------------------------------------------------
    * 将一个字符串部分字符用*替代隐藏
    +----------------------------------------------------------
    * @param string    $string   待转换的字符串
    * @param int       $bengin   起始位置，从0开始计数，当$type=4时，表示左侧保留长度
    * @param int       $len      需要转换成*的字符个数，当$type=4时，表示右侧保留长度
    * @param int       $type     转换类型：0，从左向右隐藏；1，从右向左隐藏；2，从指定字符位置分割前由右向左隐藏；3，从指定字符位置分割后由左向右隐藏；4，保留首末指定字符串
    * @param string    $glue     分割符
    +----------------------------------------------------------
    * @return string   处理后的字符串
    +----------------------------------------------------------
    */
    public static function hideStr($string, $bengin=0, $len = 4, $type = 0, $glue = "@") {
        if (empty($string))
            return false;
        $array = array();
        if ($type == 0 || $type == 1 || $type == 4) {
            $strlen = $length = mb_strlen($string);
            while ($strlen) {
                $array[] = mb_substr($string, 0, 1, "utf8");
                $string = mb_substr($string, 1, $strlen, "utf8");
                $strlen = mb_strlen($string);
            }
        }
        if ($type == 0) {
            for ($i = $bengin; $i < ($bengin + $len); $i++) {
                if (isset($array[$i]))
                    $array[$i] = "*";
            }
            $string = implode("", $array);
        }else if ($type == 1) {
            $array = array_reverse($array);
            for ($i = $bengin; $i < ($bengin + $len); $i++) {
                if (isset($array[$i]))
                    $array[$i] = "*";
            }
            $string = implode("", array_reverse($array));
        }else if ($type == 2) {
            $array = explode($glue, $string);
            $array[0] = self::hideStr($array[0], $bengin, $len, 1);
            $string = implode($glue, $array);
        } else if ($type == 3) {
            $array = explode($glue, $string);
            $array[1] = self::hideStr($array[1], $bengin, $len, 0);
            $string = implode($glue, $array);
        } else if ($type == 4) {
            $left = $bengin;
            $right = $len;
            $tem = array();
            for ($i = 0; $i < ($length - $right); $i++) {
                if (isset($array[$i]))
                    $tem[] = $i >= $left ? "*" : $array[$i];
            }
            $array = array_chunk(array_reverse($array), $right);
            $array = array_reverse($array[0]);
            for ($i = 0; $i < $right; $i++) {
                $tem[] = $array[$i];
            }
            $string = implode("", $tem);
        }
        return $string;
    }

    // 获取用户token
    static function token($request)
    {
        $authorization = $request->header('Authorization');

        // 获取不到则重新登录
        if (empty($authorization)) {
            return response('Unauthorized.', 401);
        }

        $authorizations = explode(' ',$authorization);
        return $authorizations[1];
    }
    
    /**
     * 改变env文件
     *
     * @param  Request  $request
     * @return Response
     */
    static function modifyEnv(array $data) 
    {
        $envPath = base_path() . DIRECTORY_SEPARATOR . '.env';

        $contentArray = collect(file($envPath, FILE_IGNORE_NEW_LINES));
        
        $contentArray->transform(function ($item) use ($data){
            foreach ($data as $key => $value){
                if(Str::contains($item, $key)){
                    return $key . '=' . $value;
                }
            }
            
            return $item;
        });
        
        $content = implode($contentArray->toArray(), "\n");
        
        \File::put($envPath, $content);
    }

    /**
     * 通过远程url上传图片
     *
     * @param  Request  $request
     * @return Response
     */
    static function uploadPictureFromUrl($url)
    {
        $fileArray = explode('/',$url);

        if(!count($fileArray)) {
            return self::error('未读取到图片名称！');
        }

        $fileInfo = explode('.',$fileArray[count($fileArray)-1]);

        if(count($fileInfo)>=2) {
            $fileName = $fileInfo[0];
            $fileType = $fileInfo[1];

            $name = self::makeRand(40,true).'.'.$fileType;
        } else {
            $name = self::makeRand(40,true).'.png';
        }

        $ossOpen = self::config('OSS_OPEN');

        if($ossOpen == 'on') {
            $driver = 'oss';
        } else {
            $driver = 'local';
        }

        if(strpos($url,'https') !== false) {
            $https = 1;
        } else {
            $https = 0;
        }

        $content = self::curl($url,false,'get',false,$https);

        switch ($driver) {
            case 'oss':

                // 阿里云上传
                $accessKeyId = self::config('OSS_ACCESS_KEY_ID');
                $accessKeySecret = self::config('OSS_ACCESS_KEY_SECRET');
                $endpoint = self::config('OSS_ENDPOINT');
                $bucket = self::config('OSS_BUCKET');
                // 设置自定义域名。
                $myDomain = self::config('OSS_MYDOMAIN');
        
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
        
                // 上传到阿里云
                try {
                    $ossResult = $ossClient->putObject($bucket, $object, $content);
                } catch (OssException $e) {
                    $ossResult = $e->getMessage();
                    // 返回数据
                    return self::error('上传失败！');
                }
    
                // 数据
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
                $getResult = Storage::disk('public')->put($uploadPath,$content);
                
                if($getResult) {
                    $path = 'public/'.$uploadPath;

                    // 数据
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
                    return self::error('上传失败！');
                }

                break;
        }

        // 返回数据
        return self::success('上传成功！','',$result);
    }
}
