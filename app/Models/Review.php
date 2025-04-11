<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
    protected $fillable = ['order_id', 'booking_id', 'user_id', 'contractor_id', 'rating', 'review', 'pembayaran'];

    public function order()
    {
        return $this->belongsTo(Order::class)->withDefault();
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class)->withDefault();
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function contractor()
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }
}
