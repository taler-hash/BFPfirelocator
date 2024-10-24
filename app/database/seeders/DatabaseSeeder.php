<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $roles =  new RoleSeeder();
        $station = new StationSeeder();
        $users = new UserSeeder();

        $roles->run();
        $station->run();
        $users->run();
    }
}
