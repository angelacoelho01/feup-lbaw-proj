<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JoinRequest extends Model
{
    public $timestamps = false;

    public function profile()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function userGroup()
    {
        return $this->belongsTo(UserGroup::class, 'user_group_id', 'id');
    }

    public function inviter()
    {
        return $this->belongsTo(User::class, 'inviter_id', 'id');
    }

    public function notification()
    {
        return $this->hasOne(Notification::class);
    }
}
