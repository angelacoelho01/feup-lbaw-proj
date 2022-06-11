<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Place extends Model
{
    public $timestamps = false;

    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    public function employments()
    {
        return $this->hasMany(Employment::class);
    }
}
