<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Permohonan extends Model
{
    use HasFactory;

    protected $table = 'permohonan';

    protected $fillable = [
        'user_id',
        'layanan_id',
        'tanggal_pengajuan',
        'status',
        'no_tiket',
        'no_tiket_admin',
        'catatan_admin',
        'nama',
        'alamat',
        'nik',
        'no_hp',
        'ktp_path',
        'deskripsi',
        'unit_kerja',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function lampiranPermohonan(): HasMany
    {
        return $this->hasMany(LampiranPermohonan::class, 'permohonan_id');
    }
}
