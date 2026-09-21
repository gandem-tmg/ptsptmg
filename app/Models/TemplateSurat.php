<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class TemplateSurat extends Model
{
    protected $table = 'template_surat';

    protected $fillable = [
        'layanan_id',
        'judul_template',
        'isi_template',
        'aktif',
    ];

    protected $casts = [
        'aktif' => 'boolean',
    ];

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function suratKeluar(): HasMany
    {
        return $this->hasMany(SuratKeluar::class, 'template_surat_id');
    }

    /**
     * Ganti placeholder {{key}} dengan data dari $data (array asosiatif).
     * Sengaja pakai str_replace biasa, BUKAN Blade::render(), supaya isi
     * template (yang diinput admin lewat form, bukan lewat code review)
     * tidak bisa menjalankan kode PHP.
     */
    public function render(array $data): string
    {
        $isi = $this->isi_template;

        foreach ($data as $key => $value) {
            $isi = str_replace('{{' . $key . '}}', e($value), $isi);
        }

        return $isi;
    }
}
