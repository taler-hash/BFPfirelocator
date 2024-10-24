<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

use App\Models\Station;

class StationSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Station::create([
            'name' => 'Admin',
            'location' => 'Admin',
            'longitude' => 0,
            'latitude' => 0
        ]);

        Station::create([
            'name' => 'Consolacion',
            'location' => '120 Cebu N Rd, Consolacion, 6001 Cebu',
            'longitude' => 123.95762443542482,
            'latitude' => 10.376272075727526
        ]);

        Station::create([
            'name' => 'Mandaue Centro',
            'location' => 'Mandaue City, 6014 Cebu',
            'longitude' => 123.939707,
            'latitude' => 10.3243386
        ]);
    }
}
