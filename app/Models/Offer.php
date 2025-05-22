<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Offer extends Model
{
    protected $fillable = ['contractor_id', 'post_id', 'accepted', 'status'];

    public function contractor()
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }

    public function post()
    {
        return $this->belongsTo(Post::class);
    }
    public function order()
    {
        return $this->hasOne(Order::class);
    }
}
