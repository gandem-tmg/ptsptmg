<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class LampiranPermohonan extends Model
{
    protected $table = 'lampiran_permohonan';

    protected $fillable = [
        'permohonan_id',
        'persyaratan_id',
        'file_path',
        'tanggal_unggah',
    ];

    protected $casts = [
        'tanggal_unggah' => 'date',
    ];

    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(Permohonan::class, 'permohonan_id');
    }

    public function persyaratan(): BelongsTo
    {
        return $this->belongsTo(Persyaratan::class, 'persyaratan_id');
    }

    public function getNamaFileAttribute()
    {
        return basename($this->file_path);
    }

    public function getTipeFileAttribute()
    {
        return pathinfo($this->file_path, PATHINFO_EXTENSION);
    }

    public function getUkuranFileAttribute()
    {
        return Storage::disk('public')->size($this->file_path);
    }

    public function getPathFileAttribute()
    {
        return $this->file_path;
    }
}
