<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    protected $fillable = ['contractor_id', 'start_date', 'end_date', 'transaction_id', 'is_active'];

    public function contractor()
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }
}
