<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Comment extends Model
{
    public $timestamps = false;

    protected $fillable = ['content'];

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

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function notification()
    {
        return $this->hasOne(Notification::class);
    }
}
