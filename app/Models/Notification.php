<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    public $timestamps = false;

    public function profile()
    {
        return $this->belongsTo(User::class);
    }

    public function friendRequest()
    {
        return $this->belongsTo(FriendRequest::class);
    }

    public function joinRequest()
    {
        return $this->belongsTo(JoinRequest::class);
    }

    public function reaction()
    {
        return $this->belongsTo(Reaction::class);
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class);
    }
}
