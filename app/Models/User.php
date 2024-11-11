<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Support\Str;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable;


    protected $primarykey = 'id';
    protected $keyType = 'string';
    public $incrementing = false;
    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'username',
        'email',
        'mobile',
        'city',
        'role',
        'password',
        'email_verify_token',
        'is_active',
        'email_verified_at',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
        'created_at',
        'updated_at',
        'email_verify_token',
    ];

    /**
     * The attributes that should be cast.
     *
     * @var array<string, string>
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];


    public static function boot()
    {
        parent::boot();

        static::creating(function ($model) {
            if (empty($model->id)) {
                $model->id = Str::uuid();
            }
        });
    }

    // connection 
    public function connections()
    {
        return $this->belongsToMany(User::class, 'connections', 'user_id', 'connection_id')
            ->withPivot('status', 'request_send_date', 'request_accepted_date');
    }

    // Define relationship for connection requests received by this user
    public function connectionRequestsReceived()
    {
        return $this->belongsToMany(User::class, 'connections', 'connection_id', 'user_id')
            ->wherePivot('status', 'P')
            ->withPivot('status', 'request_send_date', 'request_accepted_date');
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    // Relationship with Comment Likes
    public function likedPosts()
    {
        return $this->belongsToMany(Post::class, 'post_likes')
            ->withTimestamps();
    }

    // Relationship with Comment Likes
    public function likedComments()
    {
        return $this->belongsToMany(Comment::class, 'comment_likes')
            ->withTimestamps();
    }
}
