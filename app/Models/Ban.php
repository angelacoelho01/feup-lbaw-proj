<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Ban extends Model
{
    public $timestamps = false;

    public function profile()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function banned()
    {
        return $this->belongsTo(User::class, 'banned_id');
    }
}
