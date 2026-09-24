<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Seksi extends Model
{
    use HasFactory;

    protected $table = 'seksi';

    protected $fillable = [
        'nama_seksi',
        'kode_seksi',
        'keterangan',
    ];

    public function layanan(): HasMany
    {
        return $this->hasMany(Layanan::class, 'seksi_id');
    }

    public function petugas(): HasMany
    {
        return $this->hasMany(User::class, 'seksi_id');
    }

    /**
     * Permohonan yang SEDANG ditangani seksi ini — lokasi (current_seksi_id)
     * SAJA tidak cukup, karena permohonan yang sudah final (selesai/ditolak/
     * dibatalkan) tetap "parkir" di current_seksi_id terakhirnya walau
     * pekerjaannya sudah tidak berjalan lagi. Makanya status final harus
     * dikecualikan, supaya angka "sedang ditangani" di dashboard pimpinan
     * tidak ikut menghitung yang sudah kelar.
     */
    public function permohonanAktif(): HasMany
    {
        return $this->hasMany(Permohonan::class, 'current_seksi_id')
            ->whereNotIn('status', Permohonan::STATUS_FINAL);
    }

    public function disposisi(): HasMany
    {
        return $this->hasMany(Disposisi::class, 'seksi_id');
    }
}
