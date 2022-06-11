<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Employment extends Model
{
    public $timestamps = false;

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function profile()
    {
        return $this->belongsTo(User::class);
    }

    public function place()
    {
        return $this->belongsTo(Place::class);
    }
}
