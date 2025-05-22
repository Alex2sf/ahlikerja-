<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\Activitylog\Traits\LogsActivity;
use Spatie\Activitylog\LogOptions;

class ContractorProfile extends Model
{
    use LogsActivity;

    protected $fillable = [
        'user_id', 'foto_profile', 'nomor_telepon',
        'alamat', 'perusahaan', 'nomor_npwp', 'bidang_usaha', 'dokumen_pendukung',
        'portofolio', 'approved', 'admin_note', 'legalitas', 'bio'
    ];

    protected $casts = [
        'bidang_usaha' => 'array',
        'dokumen_pendukung' => 'array',
        'portofolio' => 'array',
        'identity_images' => 'array', // Cast sebagai array untuk JSON
        'legalitas' => 'array', // Cast sebagai array untuk JSON
        'approved' => 'boolean'
    ];

    public function getActivitylogOptions(): LogOptions
    {
        return LogOptions::defaults()
            ->logOnly(['perusahaan', 'approved', 'admin_note'])
            ->logOnlyDirty()
            ->setDescriptionForEvent(fn(string $eventName) => "Profil kontraktor untuk {$this->user->name} telah {$eventName}");
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
