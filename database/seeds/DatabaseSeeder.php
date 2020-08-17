<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{

    public static $seeders = [];

    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UserSeeder::class);
        // $this->call(RolePermissionTableSeeder::class);
        // custom seeder for use seeder in module
        foreach (self::$seeders as $seeder) {
            $this->call($seeder);
        }
    }
}
