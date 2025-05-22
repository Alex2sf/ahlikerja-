<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    protected $fillable = [
        'user_id', 'contractor_id', 'judul', 'deskripsi', 'gambar', 'dokumen', 'response_file',
        'lokasi', 'estimasi_anggaran', 'durasi', 'status','final_approve', 'decline_reason', 'is_completed', 'deadline',
        'payment_stage', 'payment_proof_1', 'payment_proof_2', 'payment_proof_3', 'payment_proof_4'
    ];

    protected $casts = [
        'gambar' => 'array',
        'estimasi_anggaran' => 'decimal:2',
        'status' => 'string',
        'final_approve' => 'boolean', // Cast final_approve sebagai boolean
        'deadline' => 'datetime', // Tambahkan casting untuk deadline
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
