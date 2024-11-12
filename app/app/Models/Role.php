<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $appends = [
        'label'
    ];

    public function getLabelAttribute() {
        return ucfirst(implode(" ",explode("_", $this->attributes['name'])));
    }
}
