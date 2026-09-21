<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SurveiRespon extends Model
{
    use HasFactory;

    protected $table = 'survei_respon';

    protected $fillable = [
        'jenis_survei',
        'permohonan_id',
        'user_id',
        'nama_pengisi',
        'jenis_kelamin',
        'usia_rentang',
        'pendidikan_terakhir',
        'pekerjaan_utama',
    ];

    /**
     * Opsi standar profil responden SKM (Permenpan-RB No. 14/2017) —
     * dipakai bareng di form pengisian & tampilan hasil, satu sumber saja.
     */
    public const OPSI_USIA = ['< 20 tahun', '20–30 tahun', '31–40 tahun', '41–50 tahun', '> 50 tahun'];
    public const OPSI_PENDIDIKAN = ['SD ke bawah', 'SMP/Sederajat', 'SMA/Sederajat', 'D3', 'S1', 'S2 ke atas'];
    public const OPSI_PEKERJAAN = ['PNS/TNI/Polri', 'Pegawai Swasta', 'Wiraswasta', 'Pelajar/Mahasiswa', 'Lainnya'];

    public function permohonan(): BelongsTo
    {
        return $this->belongsTo(Permohonan::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function jawaban(): HasMany
    {
        return $this->hasMany(SurveiJawaban::class, 'survei_respon_id');
    }
}
