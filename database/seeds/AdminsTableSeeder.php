<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class AdminsTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('admins')->insert([
            ['id' => 1,'username' => 'administrator','nickname' => '超级管理员','email' => 'admin@yourweb.com','phone' => '10086','sex' => 1,'password' => bcrypt('123456')]
        ]);
    }
}
