<?php



namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = ['user_id', 'contractor_id', 'judul', 'deskripsi', 'gambar', 'lokasi', 'estimasi_anggaran', 'durasi', 'status', 'is_completed'];
    protected $casts = [
        'gambar' => 'array',
        'estimasi_anggaran' => 'decimal:2',
        'status' => 'string'
    ];

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function contractor()
    {
        return $this->belongsTo(User::class, 'contractor_id');
    }
    public function review()
    {
        return $this->hasOne(Review::class, 'booking_id');
    }
}
