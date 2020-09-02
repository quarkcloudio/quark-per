<?php

namespace QuarkCMS\QuarkAdmin\Database;

use Illuminate\Database\Seeder;
use DB;

class DatabaseSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $this->call(AdminsTableSeeder::class);
        $this->call(MenusTableSeeder::class);
        $this->call(ConfigsTableSeeder::class);
        $this->call(PictureCategoriesTableSeeder::class);
    }
}
