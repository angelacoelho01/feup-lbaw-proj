<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = false;

    public function employment()
    {
        return $this->hasMany(Employment::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'residencies')
            ->withPivot('is_current', 'is_old_city', 'is_hometown');
    }
}
