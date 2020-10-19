<?php

use Illuminate\Database\Seeder;

class QuarkAdminSeeder extends Seeder
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
