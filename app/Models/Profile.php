<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Profile extends Model
{
    protected $fillable = [
        'user_id', 'foto_profile', 'nama_lengkap', 'nama_panggilan', 'jenis_kelamin',
        'tanggal_lahir', 'tempat_lahir', 'alamat_lengkap', 'nomor_telepon', 'email', 'media_sosial'
    ];

    protected $casts = [
        'media_sosial' => 'array',
        'tanggal_lahir' => 'date' // Tambahkan casting untuk tanggal_lahir
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
