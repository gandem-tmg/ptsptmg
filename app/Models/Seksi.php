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

    public function permohonanAktif(): HasMany
    {
        return $this->hasMany(Permohonan::class, 'current_seksi_id');
    }

    public function disposisi(): HasMany
    {
        return $this->hasMany(Disposisi::class, 'seksi_id');
    }
}
