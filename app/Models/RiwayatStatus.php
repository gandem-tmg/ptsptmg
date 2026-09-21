<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

/**
 * Log append-only setiap perubahan status permohonan.
 *
 * SENGAJA tidak ada updated_at (lihat migration) — baris di tabel ini
 * tidak boleh pernah diubah setelah dibuat. Jangan tambahkan method
 * update/delete untuk model ini di controller manapun.
 */
class RiwayatStatus extends Model
{
    protected $table = 'riwayat_status';

    const UPDATED_AT = null;

    protected $fillable = [
        'permohonan_id',
        'status',
        'catatan',
        'updated_by',
    ];

    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(Permohonan::class, 'permohonan_id');
    }

    public function petugas(): BelongsTo
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
}
