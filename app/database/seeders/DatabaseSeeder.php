<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Services\BookingService;
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
        $bookings = new BookingSeeder();

        $roles->run();
        $station->run();
        $users->run();
        $bookings->run();
    }
}
