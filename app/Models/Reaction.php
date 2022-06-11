<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Reaction extends Model
{
    public $timestamps = false;

    public function profile()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class, 'post_id', 'id');
    }

    public function comment()
    {
        return $this->belongsTo(Comment::class, 'comment_id', 'id');
    }

    public function notification()
    {
        return $this->hasOne(Notification::class);
    }
}
