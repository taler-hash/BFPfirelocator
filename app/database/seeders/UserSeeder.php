<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Models\Station;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Admin
        User::create([
            'name' => 'Admin',
            'user_name' => 'admin',
            'position' => null,
            'password' => Hash::make('taler113099'),
            'station_id' => 1
        ])->assignRole('admin');

        $consolacion = Station::where('id', 2)
        ->first()
        ->users()
        ->createMany([
            // Admin Staff
            [
                'name' => 'test Admin staff conso',
                'user_name' => 'adminstaffconso',
                'position' => null,
                'password' => Hash::make('taler113099')
            ],
            // Brgy Staff
            [
                'name' => 'test Brgy staff conso',
                'user_name' => 'brgystaffconso',
                'position' => 'Purok Leader',
                'password' => Hash::make('taler113099')
            ],
            //Responder
            [
                'name' => 'test Responder conso',
                'user_name' => 'responderconso',
                'position' => null,
                'password' => Hash::make('taler113099')
            ],
            [
                'name' => 'test Responder2 conso',
                'user_name' => 'responder2conso',
                'position' => null,
                'password' => Hash::make('taler113099')
            ]
        ]);

        $consolacion[0]->assignRole('admin_staff');
        $consolacion[1]->assignRole('brgy_staff');
        $consolacion[2]->assignRole('responder');
        $consolacion[3]->assignRole('responder');

        $mandaue = Station::where('id', 3)
        ->first()
        ->users()
        ->createMany([
            // Admin Staff
            [
                'name' => 'test Admin staff mandaue',
                'user_name' => 'adminstaffmandaue',
                'position' => null,
                'password' => Hash::make('taler113099')
            ],
            // Brgy Staff
            [
                'name' => 'test Brgy staff mandaue',
                'user_name' => 'brgystaffmandaue',
                'position' => 'Purok Leader',
                'password' => Hash::make('taler113099')
            ],
            //Responder
            [
                'name' => 'test Responder mandaue',
                'user_name' => 'respondermandaue',
                'position' => null,
                'password' => Hash::make('taler113099')
            ],
            [
                'name' => 'test Responder2 mandaue',
                'user_name' => 'responder2mandaue',
                'position' => null,
                'password' => Hash::make('taler113099')
            ]
        ]);

        $mandaue[0]->assignRole('admin_staff');
        $mandaue[1]->assignRole('brgy_staff');
        $mandaue[2]->assignRole('responder');
        $mandaue[3]->assignRole('responder');
    }
}
