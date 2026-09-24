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
        'seksi_id',
        'tipe_pelaksanaan',
        'perlu_dokumen_hasil',
        'sistem_mekanisme_prosedur',
        'jangka_waktu_pelayanan',
        'biaya_tarif',
        'produk_pelayanan',
        // Klasifikasi kebutuhan-pengguna untuk navigasi publik — lihat
        // config/klasifikasi_layanan.php untuk daftar nilai yang valid.
        'kategori',
        'subkategori',
        'target_pengguna',
        'tag_pencarian',
        'jenis_layanan',
    ];

    protected $casts = [
        'target_pengguna' => 'array',
        // Tanpa cast ini, nilai dari DB tetap int mentah (0/1) sementara
        // $request->boolean() di controller mengembalikan bool asli PHP —
        // perbandingan (string) antara keduanya salah mendeteksi "berubah"
        // padahal nilainya sama (lihat SeksiLayananController::buatRingkasanPerubahan()).
        'perlu_dokumen_hasil' => 'boolean',
    ];

    public function persyaratan(): HasMany
    {
        return $this->hasMany(Persyaratan::class, 'layanan_id');
    }

    public function permohonan(): HasMany
    {
        return $this->hasMany(Permohonan::class, 'layanan_id');
    }

    public function seksi(): \Illuminate\Database\Eloquent\Relations\BelongsTo
    {
        return $this->belongsTo(Seksi::class, 'seksi_id');
    }

    /**
     * Label & ikon kategori kebutuhan, ditarik dari config/klasifikasi_layanan.php
     * (bukan seksi) — dipakai di halaman publik.
     */
    public function getKategoriLabelAttribute(): ?string
    {
        return config("klasifikasi_layanan.kategori.{$this->kategori}.label");
    }

    public function getJenisLayananLabelAttribute(): ?string
    {
        return config("klasifikasi_layanan.jenis_layanan.{$this->jenis_layanan}");
    }

    /**
     * Cek apakah layanan ini relevan untuk tag persona tertentu (dipakai
     * fitur wizard "Tidak tahu harus pilih layanan apa?").
     */
    public function untukPersona(string $tag): bool
    {
        return in_array($tag, $this->target_pengguna ?? [], true);
    }

    public function templateSurat(): HasMany
    {
        return $this->hasMany(TemplateSurat::class, 'layanan_id');
    }

    /**
     * Riwayat perubahan layanan oleh petugas seksi (fitur "kelola layanan
     * seksi sendiri") — dipakai admin & petugas seksi untuk audit.
     */
    public function perubahanLog(): HasMany
    {
        return $this->hasMany(LayananPerubahanLog::class, 'layanan_id')->latest();
    }
}
