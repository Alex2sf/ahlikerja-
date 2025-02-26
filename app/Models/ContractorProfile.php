<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ContractorProfile extends Model
{
    protected $fillable = [
        'user_id', 'foto_profile', 'nama_depan', 'nama_belakang', 'nomor_telepon',
        'alamat', 'perusahaan', 'nomor_npwp', 'bidang_usaha', 'dokumen_pendukung', 'portofolio', 'approved', 'admin_note'
    ];

    protected $casts = [
        'bidang_usaha' => 'array',
        'dokumen_pendukung' => 'array',
        'portofolio' => 'array',
        'approved' => 'boolean'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
