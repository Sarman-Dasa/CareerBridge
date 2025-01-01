<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Comment extends BaseModel
{
    use HasFactory;

    protected $primarykey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;

    protected $fillable = ['comment', 'post_id', 'user_id', 'parent_id'];

    protected $appends = ['has_like', 'like_count'];

    public function user()
    {
        return $this->belongsTo(User::class)->select('id', 'first_name', 'last_name', 'username', 'email', 'profile_image');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    // Relationship with Users who liked the comment
    public function likes()
    {
        return $this->belongsToMany(User::class, 'comment_likes')
            ->withTimestamps();
    }

    public function parent()
    {
        return $this->belongsTo(Comment::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Comment::class, 'parent_id')->with('children')->with('user'); // Eager load recursively
    }


    public function getHasLikeAttribute()
    {
        $userId = Auth::id();
        return $this->likes()->where('user_id', $userId)->exists();
    }

    public function getlikeCountAttribute()
    {
        return $this->likes()->count();
    }
}
