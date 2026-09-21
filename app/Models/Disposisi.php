<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Disposisi extends Model
{
    protected $table = 'disposisi';

    protected $fillable = [
        'permohonan_id',
        'seksi_id',
        'didisposisikan_oleh',
        'diterima_oleh',
        'catatan',
        'tanggal_disposisi',
        'tanggal_diterima',
        'tanggal_selesai_seksi',
    ];

    protected $casts = [
        'tanggal_disposisi' => 'datetime',
        'tanggal_diterima' => 'datetime',
        'tanggal_selesai_seksi' => 'datetime',
    ];

    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(Permohonan::class, 'permohonan_id');
    }

    public function seksi(): BelongsTo
    {
        return $this->belongsTo(Seksi::class, 'seksi_id');
    }

    public function petugasPengirim(): BelongsTo
    {
        return $this->belongsTo(User::class, 'didisposisikan_oleh');
    }

    public function petugasPenerima(): BelongsTo
    {
        return $this->belongsTo(User::class, 'diterima_oleh');
    }
}
