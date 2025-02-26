<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;

class User extends Authenticatable
{
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function setPasswordAttribute($value)
    {
        $this->attributes['password'] = bcrypt($value);
    }

    public function profile()
    {
        return $this->hasOne(Profile::class);
    }

    public function contractorProfile()
    {
        return $this->hasOne(ContractorProfile::class);
    }

    public function posts()
    {
        return $this->hasMany(Post::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function sentChats()
    {
        return $this->hasMany(Chat::class, 'sender_id');
    }

    public function receivedChats()
    {
        return $this->hasMany(Chat::class, 'receiver_id');
    }
    public function offers()
    {
        return $this->hasMany(Offer::class, 'contractor_id');
    }
    public function contractorOrders()
    {
        return $this->hasMany(Order::class, 'contractor_id');
    }
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    public function contractorBookings()
    {
        return $this->hasMany(Booking::class, 'contractor_id');
    }
    public function subscriptions()
    {
        return $this->hasMany(Subscription::class, 'contractor_id');
    }
}
