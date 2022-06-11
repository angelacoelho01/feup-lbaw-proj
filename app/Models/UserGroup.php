<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserGroup extends Model
{
    public $timestamps = false;

    public function joinRequests()
    {
        return $this->hasMany(JoinRequest::class);
    }

    public function admins()
    {
        return $this->belongsToMany(User::class, 'is_group_admin');
    }
}
