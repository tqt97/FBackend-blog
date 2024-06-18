<?php

namespace App\Concerns;

use App\Models\Comment;
use App\Models\Post;

trait HasBlog
{
    public function name()
    {
        return $this->name;
    }

    public function getAvatarAttribute()
    {
        return $this->profile_photo_path
            ? asset('storage/'.$this->profile_photo_path) :
            'https://ui-avatars.com/api/?&background=random&name='.$this->name;
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
}
