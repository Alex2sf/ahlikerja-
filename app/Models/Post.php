<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    protected $fillable = [
        'user_id', 'judul', 'deskripsi', 'gambar', 'lokasi', 'estimasi_anggaran', 'durasi'
    ];

    protected $casts = [
        'gambar' => 'array',
        'estimasi_anggaran' => 'decimal:2'
    ];
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function likes()
    {
        return $this->hasMany(Like::class);
    }

    public function comments()
    {
        return $this->hasMany(Comment::class);
    }
    public function offers()
    {
        return $this->hasMany(Offer::class);
    }
}
