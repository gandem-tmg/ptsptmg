<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LampiranHasil extends Model
{
    protected $table = 'lampiran_hasil';

    protected $fillable = [
        'permohonan_id',
        'nama_dokumen',
        'file_path',
        'diunggah_oleh',
        'tanggal_unggah',
    ];

    protected $casts = [
        'tanggal_unggah' => 'datetime',
    ];

    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(Permohonan::class, 'permohonan_id');
    }

    public function pengunggah(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diunggah_oleh');
    }

    public function getUkuranFileAttribute()
    {
        return Storage::disk('public')->size($this->file_path);
    }
}
