<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $fillable = ['user_id', 'contractor_id', 'post_id', 'offer_id', 'is_completed'];
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contractor()
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }

    public function offer()
    {
        return $this->belongsTo(Offer::class);
    }

    public function review()
    {
        return $this->hasOne(Review::class, 'order_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class, 'booking_id'); // Sesuaikan foreign key
    }

}
