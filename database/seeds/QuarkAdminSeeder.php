<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class QuarkAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 管理员
        DB::table('admins')->insert([
            ['id' => 1,'username' => 'administrator','nickname' => '超级管理员','email' => 'admin@yourweb.com','phone' => '10086','sex' => 1,'password' => bcrypt('123456')]
        ]);

        // 图片分类
        DB::table('picture_categories')->insert([
            ['id' => 1,'obj_type' => 'ADMINID','obj_id' => 1,'title' => '默认分类','sort' => 0,'description' => '默认分类']
        ]);

        // 菜单
        DB::table('menus')->insert([
            ['id' =>1,'name' => '控制台','guard_name' => 'admin','icon' => 'icon-home','type'=>'default','pid' => 0,'sort' => -2,'path' => '/dashboard','show'  => 1,'status' => 1],
            ['id' =>2,'name' => '主页','guard_name' => 'admin','icon' => '','type'=>'engine','pid' => 1,'sort' => 0,'path' => 'admin/dashboard/index','show'  => 1,'status' => 1],

            ['id' =>13,'name' => '管理员','guard_name' => 'admin','icon' => 'icon-admin','type'=>'default','pid' => 0,'sort' => 0,'path' => '/admin','show'  => 1,'status' => 1],
            ['id' =>14,'name' => '管理员列表','guard_name' => 'admin','icon' => '','type'=>'engine','pid' => 13,'sort' => 0,'path' => 'admin/admin/index','show'  => 1,'status' => 1],
            ['id' =>15,'name' => '菜单列表','guard_name' => 'admin','icon' => '','type'=>'engine','pid' => 25,'sort' => 0,'path' => 'admin/menu/index','show'  => 1,'status' => 1],
            ['id' =>16,'name' => '权限列表','guard_name' => 'admin','icon' => '','type'=>'engine','pid' => 13,'sort' => 0,'path' => 'admin/permission/index','show'  => 1,'status' => 1],
            ['id' =>17,'name' => '角色列表','guard_name' => 'admin','icon' => '','type'=>'engine','pid' => 13,'sort' => 0,'path' => 'admin/role/index','show'  => 1,'status' => 1],

            ['id' =>25,'name' => '系统配置','guard_name' => 'admin','icon' => 'icon-setting','type'=>'default','pid' => 0,'sort' => 0,'path' => '/system','show'  => 1,'status' => 1],
            ['id' =>26,'name' => '设置管理','guard_name' => 'admin','icon' => '','type'=>'default','pid' => 25,'sort' => -1,'path' => '/system/config','show'  => 1,'status' => 1],
            ['id' =>27,'name' => '网站设置','guard_name' => 'admin','icon' => '','type'=>'engine','pid' => 26,'sort' => 0,'path' => 'admin/config/website','show'  => 1,'status' => 1],
            ['id' =>28,'name' => '配置管理','guard_name' => 'admin','icon' => '','type'=>'engine','pid' => 26,'sort' => 0,'path' => 'admin/config/index','show'  => 1,'status' => 1],
            ['id' =>32,'name' => '操作日志','guard_name' => 'admin','icon' => '','type'=>'engine','pid' => 25,'sort' => 0,'path' => 'admin/actionLog/index','show'  => 1,'status' => 1],
            
            ['id' =>33,'name' => '附件空间','guard_name' => 'admin','icon' => 'icon-attachment','type'=>'default','pid' => 0,'sort' => 0,'path' => '/attachment','show'  => 1,'status' => 1],
            ['id' =>34,'name' => '文件管理','guard_name' => 'admin','icon' => '','pid' => 33,'type'=>'engine','sort' => 0,'path' => 'admin/file/index','show'  => 1,'status' => 1],
            ['id' =>35,'name' => '图片管理','guard_name' => 'admin','icon' => '','pid' => 33,'type'=>'engine','sort' => 0,'path' => 'admin/picture/index','show'  => 1,'status' => 1],
            
            ['id' =>36,'name' => '我的账号','guard_name' => 'admin','icon' => 'icon-user','type'=>'default','pid' => 0,'sort' => 0,'path' => '/account','show'  => 1,'status' => 1],
            ['id' =>37,'name' => '个人设置','guard_name' => 'admin','icon' => '','type'=>'default','pid' => 36,'sort' => 0,'path' => '/account/settings','show'  => 1,'status' => 1],
        ]);

        // 网站配置
        DB::table('configs')->insert([
            ['id' => 1,'title' => '网站名称','type' => 'text','name' => 'WEB_SITE_NAME','group_name' => '基本','value' => 'QuarkCMS','remark' => '','status' => 1],
            ['id' => 2,'title' => '关键字','type' => 'text','name' => 'WEB_SITE_KEYWORDS','group_name' => '基本','value' => 'QuarkCMS','remark' => '','status' => 1],
            ['id' => 3,'title' => '描述','type' => 'textarea','name' => 'WEB_SITE_DESCRIPTION','group_name' => '基本','value' => 'QuarkCMS','remark' => '','status' => 1],
            ['id' => 4,'title' => 'Logo','type' => 'picture','name' => 'WEB_SITE_LOGO','group_name' => '基本','value' => '','remark' => '','status' => 1],
            ['id' => 5,'title' => '统计代码','type' => 'textarea','name' => 'WEB_SITE_SCRIPT','group_name' => '基本','value' => '','remark' => '','status' => 1],
            ['id' => 6,'title' => '网站版权','type' => 'text','name' => 'WEB_SITE_COPYRIGHT','group_name' => '基本','value' => '© Company 2018','remark' => '','status' => 1],
            ['id' => 7,'title' => '开启SSL','type' => 'switch','name' => 'SSL_OPEN','group_name' => '基本','value' => '0','remark' => '','status' => 1],
            ['id' => 8,'title' => '开启网站','type' => 'switch','name' => 'WEB_SITE_OPEN','group_name' => '基本','value' => '1','remark' => '','status' => 1],

            ['id' => 17,'title' => 'KeyID','type' => 'text','name' => 'OSS_ACCESS_KEY_ID','group_name' => '阿里云存储','value' => '','remark' => '你的AccessKeyID','status' => 1],
            ['id' => 18,'title' => 'KeySecret','type' => 'text','name' => 'OSS_ACCESS_KEY_SECRET','group_name' => '阿里云存储','value' => '','remark' => '你的AccessKeySecret','status' => 1],
            ['id' => 19,'title' => 'EndPoint','type' => 'text','name' => 'OSS_ENDPOINT','group_name' => '阿里云存储','value' => '','remark' => '地域节点','status' => 1],
            ['id' => 20,'title' => 'Bucket域名','type' => 'text','name' => 'OSS_BUCKET','group_name' => '阿里云存储','value' => '','remark' => '','status' => 1],
            ['id' => 21,'title' => '自定义域名','type' => 'text','name' => 'OSS_MYDOMAIN','group_name' => '阿里云存储','value' => '','remark' => '例如：oss.web.com','status' => 1],
            ['id' => 22,'title' => '开启云存储','type' => 'switch','name' => 'OSS_OPEN','group_name' => '阿里云存储','value' => '0','remark' => '','status' => 1],

            ['id' => 50,'title' => '开发者模式','type' => 'switch','name' => 'APP_DEBUG','group_name' => '基本','value' => '1','remark' => '','status' => 1],
        ]);
    }
}