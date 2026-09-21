<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Persyaratan extends Model
{
    use HasFactory;
    protected $table = 'persyaratan';

    protected $fillable = [
        'layanan_id',
        'nama_persyaratan',
        'wajib',
    ];

    protected $casts = [
        'wajib' => 'boolean',
    ];

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function lampiranPermohonan(): HasMany
    {
        return $this->hasMany(LampiranPermohonan::class, 'persyaratan_id');
    }
}
