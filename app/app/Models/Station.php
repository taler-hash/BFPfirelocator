<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Station extends Model
{
    protected $fillable = [
        'name',
        'location',
        'longitude',
        'latitude'
    ];

    protected $appends = [
        'show_action'
    ];

    public function users() {
        return $this->hasMany(User::class);
    }

    public function subscription() {
        return $this->hasOne(Subscription::class);
    }

    public function getShowActionAttribute() {
        return $this->attributes['name'] !== 'Admin';
    }
}
