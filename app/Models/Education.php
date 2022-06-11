<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Education extends Model
{
    public $timestamps = false;

    protected $table = 'educations';

    public function place()
    {
        return $this->belongsTo(Place::class);
    }

    public function profile()
    {
        return $this->belongsTo(User::class);
    }
}
