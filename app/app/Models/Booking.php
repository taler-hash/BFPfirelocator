<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use App\Models\Station;
use App\Observers\BookingObserver;
use Illuminate\Database\Eloquent\Attributes\ObservedBy;

#[ObservedBy(BookingObserver::class)]
class Booking extends Model
{
    use LogsActivity;

    protected $fillable = [
        'location_name',
        'longitude',
        'latitude',
        'booking_date',
        'status',
        'station_id',
        'reason'
    ];

    public function responders()
    {
        return $this->hasMany(BookingResponder::class);
    }
    
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function station() {
        return $this->belongsTo(Station::class);
    }

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['station.name', 'status', 'user.name'])
            ->logOnlyDirty();
    }
    public function scopeOwned($q, $id) {
        $q->where('user_id', $id);
    }
}
