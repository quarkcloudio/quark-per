<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class PictureCategoriesTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('picture_categories')->insert([
            ['id' => 1,'obj_type' => 'ADMINID','obj_id' => 1,'title' => '默认分类','sort' => 0,'description' => '默认分类']
        ]);
    }
}
