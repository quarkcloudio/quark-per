<?php

namespace QuarkCMS\QuarkAdmin\Database;

use Illuminate\Database\Seeder;
use DB;

class MenusTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('menus')->insert([
            ['id' =>1,'name' => '控制台','guard_name' => 'admin','icon' => 'icon-home','type'=>'default','pid' => 0,'sort' => -2,'path' => '/dashboard','show'  => 1,'status' => 1],
            ['id' =>2,'name' => '主页','guard_name' => 'admin','icon' => '','type'=>'default','pid' => 1,'sort' => 0,'path' => '/dashboard/index','show'  => 1,'status' => 1],

            ['id' =>13,'name' => '管理员','guard_name' => 'admin','icon' => 'icon-admin','type'=>'default','pid' => 0,'sort' => 0,'path' => '/admin','show'  => 1,'status' => 1],
            ['id' =>14,'name' => '管理员列表','guard_name' => 'admin','icon' => '','type'=>'table','pid' => 13,'sort' => 0,'path' => 'admin/admin/index','show'  => 1,'status' => 1],
            ['id' =>15,'name' => '菜单列表','guard_name' => 'admin','icon' => '','type'=>'table','pid' => 25,'sort' => 0,'path' => 'admin/menu/index','show'  => 1,'status' => 1],
            ['id' =>16,'name' => '权限列表','guard_name' => 'admin','icon' => '','type'=>'table','pid' => 13,'sort' => 0,'path' => 'admin/permission/index','show'  => 1,'status' => 1],
            ['id' =>17,'name' => '角色列表','guard_name' => 'admin','icon' => '','type'=>'table','pid' => 13,'sort' => 0,'path' => 'admin/role/index','show'  => 1,'status' => 1],

            ['id' =>25,'name' => '系统配置','guard_name' => 'admin','icon' => 'icon-setting','type'=>'default','pid' => 0,'sort' => 0,'path' => '/system','show'  => 1,'status' => 1],
            ['id' =>26,'name' => '设置管理','guard_name' => 'admin','icon' => '','type'=>'default','pid' => 25,'sort' => -1,'path' => '/system/config','show'  => 1,'status' => 1],
            ['id' =>27,'name' => '网站设置','guard_name' => 'admin','icon' => '','type'=>'form','pid' => 26,'sort' => 0,'path' => 'admin/config/website','show'  => 1,'status' => 1],
            ['id' =>28,'name' => '配置管理','guard_name' => 'admin','icon' => '','type'=>'table','pid' => 26,'sort' => 0,'path' => 'admin/config/index','show'  => 1,'status' => 1],
            ['id' =>32,'name' => '操作日志','guard_name' => 'admin','icon' => '','type'=>'table','pid' => 25,'sort' => 0,'path' => 'admin/actionLog/index','show'  => 1,'status' => 1],
            
            ['id' =>33,'name' => '附件空间','guard_name' => 'admin','icon' => 'icon-attachment','type'=>'default','pid' => 0,'sort' => 0,'path' => '/attachment','show'  => 1,'status' => 1],
            ['id' =>34,'name' => '文件管理','guard_name' => 'admin','icon' => '','pid' => 33,'type'=>'table','sort' => 0,'path' => 'admin/file/index','show'  => 1,'status' => 1],
            ['id' =>35,'name' => '图片管理','guard_name' => 'admin','icon' => '','pid' => 33,'type'=>'table','sort' => 0,'path' => 'admin/picture/index','show'  => 1,'status' => 1],
            
            ['id' =>36,'name' => '我的账号','guard_name' => 'admin','icon' => 'icon-user','type'=>'default','pid' => 0,'sort' => 0,'path' => '/account','show'  => 1,'status' => 1],
            ['id' =>37,'name' => '个人设置','guard_name' => 'admin','icon' => '','type'=>'default','pid' => 36,'sort' => 0,'path' => '/account/settings','show'  => 1,'status' => 1],
        ]);
    }
}
