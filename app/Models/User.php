<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{

    use Notifiable;

    public $timestamps = false;

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected $fillable = [
        'name', 'email', 'password',
    ];

    public function banner()
    {
        return $this->hasOne(Ban::class, 'user_id');
    }

    public function bans()
    {
        return $this->hasMany(Ban::class, 'banned_id');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function tags()
    {
        return $this->belongsToMany(Post::class, 'tags');
    }

    public function educations()
    {
        return $this->hasMany(Education::class);
    }

    public function employments()
    {
        return $this->hasMany(Employment::class);
    }

    public function joinGroupRequests()
    {
        return $this->hasMany(JoinRequest::class, 'user_id');
    }

    public function sentGroupInvites()
    {
        return $this->hasMany(JoinRequest::class, 'inviter_id');
    }

    public function friendRequests() 
    {
        return $this->hasMany(FriendRequest::class);
    }

    public function sentFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'sender_id');
    }

    public function receivedFriendRequests()
    {
        return $this->hasMany(FriendRequest::class, 'receiver_id');
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }

    public function reactions()
    {
        return $this->hasMany(Reaction::class);
    }

    public function notifications()
    {
        return $this->hasMany(Notification::class);
    }

    public function isGroupAdmin()
    {
        return $this->belongsToMany(UserGroup::class, 'is_group_admin');
    }

    public function cities()
    {
        return $this->belongsToMany(City::class, 'residencies')->withPivot('is_current', 'is_hometown', 'is_old_city');
    }
}
