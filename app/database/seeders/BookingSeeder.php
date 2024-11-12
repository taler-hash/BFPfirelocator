<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Booking;
use App\Models\BookingResponder;
use Illuminate\Database\Eloquent\Collection;

class BookingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $now = now();
        $consoBrgyStaff = User::find(3);
        $consoBookings = ([
            [
                'location_name' => 'Brgy tayud Consolacion',
                'latitude' => 10.362,
                'longitude' => 123.9824080467,
                'station_id' => 2,
                'status' => 'completed',
                'booking_date' => $now
            ],
            [
                'location_name' => 'Global Dimension Company Inc',
                'latitude' => 10.367,
                'longitude' => 123.9814424515,
                'station_id' => 2,
                'status' => 'completed',
                'booking_date' => $now
            ]
        ]);

        $consoBooked = $consoBrgyStaff->bookings()->createMany($consoBookings);

        collect($consoBooked)->map(function ($booking) {
            $responders = User::where('station_id', 2)->role(['responder'])->get();
            $booking->responders()->createMany($this->idAsUserId($responders->toArray()));
        });
    
        $mandaueBrgyStaff = User::find(7);
        $mandaueBookings = [
            [
                'location_name' => 'Wilcom Depot',
                'latitude' => 10.336,
                'longitude' => 123.9597916603,
                'station_id' => 3,
                'status' => 'completed',
                'booking_date' => $now
            ],
            [
                'location_name' => 'Uratex Cebu Showroom',
                'latitude' => 10.34,
                'longitude' => 123.9566159248,
                'station_id' => 3,
                'status' => 'completed',
                'booking_date' => $now
            ]
        ];

        $mandaueBooked = $mandaueBrgyStaff->bookings()->createMany($mandaueBookings);

        collect($mandaueBooked)->map(function ($booking) {
            $responders = User::where('station_id', 3)->role(['responder'])->get();
            $booking->responders()->createMany($this->idAsUserId($responders->toArray()));
        });

    }

    private function idAsUserId(array $collection) {
        return collect($collection)->map(function ($item) {
            return [
                'user_id' => $item['id'],
                'name' => $item['name']
            ];
        })->toArray();
    }
}
