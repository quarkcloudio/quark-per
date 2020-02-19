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
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['9', '0', 'api/admin/admin/index', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['10', '0', 'api/admin/admin/create', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['11', '0', 'api/admin/admin/store', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['12', '0', 'api/admin/admin/edit', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['13', '0', 'api/admin/admin/save', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['14', '0', 'api/admin/admin/changeStatus', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['15', '0', 'api/admin/permission/index', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['16', '0', 'api/admin/permission/create', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['17', '0', 'api/admin/permission/store', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['18', '0', 'api/admin/permission/edit', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['19', '0', 'api/admin/permission/save', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['20', '0', 'api/admin/permission/changeStatus', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['21', '0', 'api/admin/role/index', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['22', '0', 'api/admin/role/create', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['23', '0', 'api/admin/role/store', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['24', '0', 'api/admin/role/edit', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['25', '0', 'api/admin/role/save', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['26', '0', 'api/admin/role/changeStatus', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['27', '0', 'api/admin/config/website', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['28', '0', 'api/admin/config/saveWebsite', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['29', '0', 'api/admin/config/index', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['30', '0', 'api/admin/config/create', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['31', '0', 'api/admin/config/store', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['32', '0', 'api/admin/config/edit', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['33', '0', 'api/admin/config/save', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['34', '0', 'api/admin/config/changeStatus', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['35', '0', 'api/admin/menu/index', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['36', '0', 'api/admin/menu/create', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['37', '0', 'api/admin/menu/store', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['38', '0', 'api/admin/menu/edit', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['39', '0', 'api/admin/menu/save', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['40', '0', 'api/admin/menu/destroy', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['41', '0', 'api/admin/menu/changeStatus', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['42', '0', 'api/admin/actionLog/index', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['43', '0', 'api/admin/actionLog/changeStatus', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['44', '0', 'api/admin/actionLog/export', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['45', '0', 'api/admin/picture/index', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['46', '0', 'api/admin/picture/upload', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['47', '0', 'api/admin/picture/download', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['48', '0', 'api/admin/picture/update', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['49', '0', 'api/admin/picture/edit', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['50', '0', 'api/admin/picture/save', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['51', '0', 'api/admin/picture/changeStatus', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['52', '0', 'api/admin/file/index', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['53', '0', 'api/admin/file/upload', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['54', '0', 'api/admin/file/download', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['55', '0', 'api/admin/file/update', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['56', '0', 'api/admin/file/changeStatus', 'admin']);
        DB::insert('INSERT INTO permissions (id,menu_id,name,guard_name) VALUES (?,?,?,?)', ['57', '0', 'api/admin/test/index', 'admin']);
    }
}
