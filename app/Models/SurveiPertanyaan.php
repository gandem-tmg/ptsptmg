<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveiPertanyaan extends Model
{
    use HasFactory;

    protected $table = 'survei_pertanyaan';

    protected $fillable = [
        'teks_pertanyaan',
        'tipe',
        'opsi_jawaban',
        'jenis_survei',
        'urutan',
        'aktif',
    ];

    protected $casts = [
        'opsi_jawaban' => 'array',
        'aktif' => 'boolean',
    ];

    /**
     * Label default kalau admin belum kustomisasi opsi_jawaban untuk
     * pertanyaan tipe skala_4 — mengikuti istilah umum SKM resmi
     * (Tidak Baik / Kurang Baik / Baik / Sangat Baik).
     */
    public function labelSkala(): array
    {
        return $this->opsi_jawaban ?: ['Tidak Baik', 'Kurang Baik', 'Baik', 'Sangat Baik'];
    }

    public function jawaban(): HasMany
    {
        return $this->hasMany(SurveiJawaban::class, 'survei_pertanyaan_id');
    }

    public function scopeAktif($query)
    {
        return $query->where('aktif', true);
    }

    public function scopeUntukJenis($query, string $jenis)
    {
        return $query->whereIn('jenis_survei', [$jenis, 'keduanya']);
    }
}
