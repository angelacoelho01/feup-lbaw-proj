<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    public $timestamps = false;

    protected $fillable = ['post_text', 'is_public'];

    public function profile()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function tags()
    {
        return $this->belongsToMany(User::class, 'tags');
    }

    public function multimediaContent()
    {
        return $this->hasMany(MultimediaContent::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }
}
