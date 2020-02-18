<?php

namespace QuarkCMS\QuarkAdmin\Database;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use DB;

class PermissionsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['1', '0', 'api/admin/login', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['2', '0', 'api/admin/logout', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['3', '0', 'api/admin/loginErrorTimes', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['4', '0', 'api/admin/dashboard/index', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['5', '0', 'api/admin/account/info', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['6', '0', 'api/admin/account/profile', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['7', '0', 'api/admin/account/password', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['8', '0', 'api/admin/account/menus', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['29', '0', 'api/admin/admin/index', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['30', '0', 'api/admin/admin/create', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['31', '0', 'api/admin/admin/store', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['32', '0', 'api/admin/admin/edit', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['33', '0', 'api/admin/admin/save', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['34', '0', 'api/admin/admin/changeStatus', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['35', '0', 'api/admin/permission/index', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['36', '0', 'api/admin/permission/create', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['37', '0', 'api/admin/permission/store', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['38', '0', 'api/admin/permission/edit', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['39', '0', 'api/admin/permission/save', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['40', '0', 'api/admin/permission/changeStatus', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['41', '0', 'api/admin/role/index', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['42', '0', 'api/admin/role/create', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['43', '0', 'api/admin/role/store', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['44', '0', 'api/admin/role/edit', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['45', '0', 'api/admin/role/save', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['46', '0', 'api/admin/role/changeStatus', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['75', '0', 'api/admin/config/website', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['76', '0', 'api/admin/config/saveWebsite', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['77', '0', 'api/admin/config/index', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['78', '0', 'api/admin/config/create', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['79', '0', 'api/admin/config/store', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['80', '0', 'api/admin/config/edit', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['81', '0', 'api/admin/config/save', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['82', '0', 'api/admin/config/changeStatus', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['83', '0', 'api/admin/menu/index', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['84', '0', 'api/admin/menu/create', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['85', '0', 'api/admin/menu/store', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['86', '0', 'api/admin/menu/edit', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['87', '0', 'api/admin/menu/save', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['88', '0', 'api/admin/menu/destroy', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['89', '0', 'api/admin/menu/changeStatus', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['105', '0', 'api/admin/actionLog/index', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['106', '0', 'api/admin/actionLog/changeStatus', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['107', '0', 'api/admin/actionLog/export', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['108', '0', 'api/admin/picture/index', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['109', '0', 'api/admin/picture/upload', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['110', '0', 'api/admin/picture/download', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['111', '0', 'api/admin/picture/update', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['112', '0', 'api/admin/picture/edit', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['113', '0', 'api/admin/picture/save', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['114', '0', 'api/admin/picture/changeStatus', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['115', '0', 'api/admin/file/index', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['116', '0', 'api/admin/file/upload', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['117', '0', 'api/admin/file/download', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['118', '0', 'api/admin/file/update', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['119', '0', 'api/admin/file/changeStatus', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['120', '0', 'api/admin/test/index', 'admin']);
    }
}
