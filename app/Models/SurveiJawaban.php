<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SurveiJawaban extends Model
{
    protected $table = 'survei_jawaban';

    protected $fillable = [
        'survei_respon_id',
        'survei_pertanyaan_id',
        'nilai_rating',
        'pilihan_jawaban',
        'jawaban_teks',
    ];

    public function respon(): BelongsTo
    {
        return $this->belongsTo(SurveiRespon::class, 'survei_respon_id');
    }

    public function pertanyaan(): BelongsTo
    {
        return $this->belongsTo(SurveiPertanyaan::class, 'survei_pertanyaan_id');
    }
}
