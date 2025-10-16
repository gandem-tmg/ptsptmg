<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Layanan extends Model
{
    use HasFactory;

    protected $table = 'layanan';

    protected $fillable = [
        'nama_layanan',
        'deskripsi',
        'kode_layanan',
    ];

    public function persyaratan(): HasMany
    {
        return $this->hasMany(Persyaratan::class, 'layanan_id');
    }

    public function permohonan(): HasMany
    {
        return $this->hasMany(Permohonan::class, 'layanan_id');
    }
}
