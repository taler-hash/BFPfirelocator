<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Hydrant extends Model
{
    protected $fillable = [
        'name',
        'latitude',
        'longitude'
    ];
}
