<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;
class Post extends Model
{

    use LogsActivity;
    protected $fillable = [
        'user_id', 'judul', 'deskripsi', 'gambar', 'lokasi', 'estimasi_anggaran', 'durasi'
    ];

    protected $casts = [
        'gambar' => 'array',
        'estimasi_anggaran' => 'decimal:2'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['judul', 'deskripsi'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Postingan '{$this->judul}' telah {$eventName}");
    }
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
