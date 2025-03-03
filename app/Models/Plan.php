<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use HasFactory;

    protected $table = 'plans'; // Pastikan nama tabel sesuai

    protected $fillable = ['name', 'price']; // Kolom yang bisa diisi
}
