<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class SuratKeluar extends Model
{
    protected $table = 'surat_keluar';

    protected $fillable = [
        'permohonan_id',
        'template_surat_id',
        'nomor_surat',
        'isi_surat',
        'file_path',
        'metode_ttd',
        'ditandatangani_oleh',
        'tanggal_ttd',
        'kode_verifikasi',
        'qr_verifikasi_path',
        'dibuat_oleh',
    ];

    protected $casts = [
        'tanggal_ttd' => 'datetime',
    ];

    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(Permohonan::class, 'permohonan_id');
    }

    public function template(): BelongsTo
    {
        return $this->belongsTo(TemplateSurat::class, 'template_surat_id');
    }

    public function penandatangan(): BelongsTo
    {
        return $this->belongsTo(User::class, 'ditandatangani_oleh');
    }

    public function pembuat(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }

    /**
     * Nomor surat sekuensial per seksi per tahun, format sederhana:
     * B-{urutan}/Kk.13.30-Temanggung/{kode_seksi}/{bulan_romawi}/{tahun}
     *
     * CATATAN: "Kk.13.30" itu placeholder kode satuan kerja — ganti dengan
     * kode resmi Kemenag Kab. Temanggung sesuai kaidah tata naskah dinas
     * yang berlaku. Dibungkus transaksi + lockForUpdate supaya aman kalau
     * ada 2 surat dibuat bersamaan (tidak dapat nomor yang sama).
     */
    public static function generateNomorSurat(Seksi $seksi): string
    {
        $tahun = now()->year;
        $bulanRomawi = [
            1 => 'I', 2 => 'II', 3 => 'III', 4 => 'IV', 5 => 'V', 6 => 'VI',
            7 => 'VII', 8 => 'VIII', 9 => 'IX', 10 => 'X', 11 => 'XI', 12 => 'XII',
        ][now()->month];

        return DB::transaction(function () use ($seksi, $tahun, $bulanRomawi) {
            $urutan = static::where('nomor_surat', 'like', '%/' . $seksi->kode_seksi . '/%/' . $tahun)
                ->lockForUpdate()
                ->count() + 1;

            return sprintf(
                'B-%d/Kk.13.30-Temanggung/%s/%s/%d',
                $urutan,
                $seksi->kode_seksi,
                $bulanRomawi,
                $tahun
            );
        });
    }

    public static function generateKodeVerifikasi(): string
    {
        do {
            $kode = Str::upper(Str::random(24));
        } while (static::where('kode_verifikasi', $kode)->exists());

        return $kode;
    }
}
