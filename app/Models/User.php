<?php
namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Post;
use App\Models\Like;
use App\Models\Comment;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
class User extends Authenticatable
{

    use HasFactory, LogsActivity, Notifiable;
    protected $fillable = [
        'name', 'email', 'password', 'role'
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['name', 'email', 'role'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "User {$this->name} telah {$eventName}");
    }

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

    public function reviews()
{
    return $this->hasMany(Review::class, 'contractor_id');
}
}
