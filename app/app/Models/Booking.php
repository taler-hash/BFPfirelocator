<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'location_name',
        'longitude',
        'latitude',
        'booking_date',
        'status'
    ];

    public function responders() {
        return $this->hasMany(User::class);
    }

}
