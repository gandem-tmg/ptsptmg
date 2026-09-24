<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Permohonan extends Model
{
    use HasFactory;

    protected $table = 'permohonan';

    /**
     * Daftar LENGKAP status yang valid di kolom enum `status`, plus label
     * tampilannya — status lama & baru sekaligus. SATU-SATUNYA sumber
     * acuan; jangan hardcode daftar status terpisah di view/controller lain
     * supaya tidak ada lagi filter/dropdown/card yang "bolong" (cuma cek
     * status lama atau cuma sebagian status baru).
     */
    public const STATUS_LABELS = [
        // -- Alur baru (disposisi PTSP -> seksi -> verifikasi akhir) --
        'diajukan' => 'Diajukan',
        'verifikasi_ptsp' => 'Verifikasi PTSP',
        'dikembalikan' => 'Dikembalikan',
        'didisposisikan' => 'Didisposisikan',
        'diproses_seksi' => 'Diproses Seksi',
        'selesai_seksi' => 'Selesai di Seksi',
        'verifikasi_akhir' => 'Verifikasi Akhir',
        'selesai' => 'Selesai',
        'ditolak' => 'Ditolak',
        'dibatalkan' => 'Dibatalkan',
        // -- Status lama, dipertahankan untuk kompatibilitas data existing --
        'verifikasi' => 'Verifikasi (lama)',
        'proses' => 'Diproses (lama)',
    ];

    /**
     * Status akhir/final: permohonan tidak akan berubah status lagi
     * setelah ini (dari sudut pandang pemohon). Dipakai buat card
     * "Sedang Berjalan" dsb supaya konsisten di semua dashboard.
     */
    public const STATUS_FINAL = ['selesai', 'ditolak', 'dibatalkan'];

    /**
     * Semua status yang masih "berjalan" (belum final) — kebalikan dari
     * STATUS_FINAL, dihitung otomatis dari STATUS_LABELS supaya kalau ada
     * status baru ditambah, otomatis ikut ke sini juga.
     */
    public static function statusAktif(): array
    {
        return array_values(array_diff(array_keys(self::STATUS_LABELS), self::STATUS_FINAL));
    }

    protected $fillable = [
        'user_id',
        'layanan_id',
        'current_seksi_id',
        'tanggal_pengajuan',
        'status',
        'sumber_pengajuan',
        'no_tiket',
        'no_tiket_admin',
        'qr_code_path',
        'catatan_admin',
        'nama',
        'alamat',
        'nik',
        'no_hp',
        'ktp_path',
        'deskripsi',
        'unit_kerja',
        'is_manual',
        'manual_seksi_id',
        'nama_layanan_manual',
        'deskripsi_layanan_manual',
        'kelengkapan_manual',
    ];

    protected $casts = [
        'tanggal_pengajuan' => 'date',
        'is_manual' => 'boolean',
    ];

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function layanan(): BelongsTo
    {
        return $this->belongsTo(Layanan::class, 'layanan_id');
    }

    public function currentSeksi(): BelongsTo
    {
        return $this->belongsTo(Seksi::class, 'current_seksi_id');
    }

    /**
     * Seksi yang dipilih petugas saat membuat permohonan manual (layanan
     * di luar katalog). Cuma relevan kalau is_manual = true; dipakai
     * sebagai tujuan default disposisi karena tidak ada layanan->seksi_id
     * untuk permohonan seperti ini.
     */
    public function manualSeksi(): BelongsTo
    {
        return $this->belongsTo(Seksi::class, 'manual_seksi_id');
    }

    /**
     * Nama layanan untuk ditampilkan — dari katalog resmi kalau ada,
     * atau dari isian manual petugas kalau permohonan ini di luar
     * katalog (is_manual = true). SATU-SATUNYA tempat acuan supaya
     * view tidak perlu cek is_manual berulang-ulang di banyak tempat.
     */
    public function getNamaLayananLabelAttribute(): string
    {
        if ($this->is_manual) {
            return $this->nama_layanan_manual ?: 'Layanan di luar katalog';
        }

        return $this->layanan->nama_layanan ?? '-';
    }

    /**
     * Seksi tujuan untuk ditampilkan — dari layanan->seksi (katalog resmi),
     * atau dari seksi yang dipilih petugas saat input manual.
     */
    public function getSeksiLabelAttribute(): string
    {
        if ($this->is_manual) {
            return $this->manualSeksi->nama_seksi ?? '-';
        }

        return $this->layanan->seksi->nama_seksi ?? '-';
    }

    /**
     * Posisi permohonan saat ini, untuk ditampilkan di kolom "Sedang
     * Ditangani" / "Sedang di Seksi" (detail, kartu daftar, lacak tiket).
     * Sebelumnya view memanggil $permohonan->lokasi_saat_ini padahal
     * accessor-nya tidak pernah ada, jadi kolom itu selalu tampil kosong.
     *
     * - Ada seksi aktif (didisposisikan / diproses_seksi) -> nama seksinya.
     * - Menunggu pemohon melengkapi berkas (dikembalikan)  -> Pemohon.
     * - Status final (selesai / ditolak / dibatalkan)      -> "-".
     * - Selain itu (diajukan, verifikasi, selesai_seksi,
     *   verifikasi_akhir, status lama)                     -> PTSP.
     */
    public function getLokasiSaatIniAttribute(): string
    {
        if ($this->current_seksi_id && $this->currentSeksi) {
            return $this->currentSeksi->nama_seksi;
        }

        if (in_array($this->status, self::STATUS_FINAL, true)) {
            return '-';
        }

        if ($this->status === 'dikembalikan') {
            return 'Pemohon (melengkapi berkas)';
        }

        return 'PTSP';
    }

    public function lampiranPermohonan(): HasMany
    {
        return $this->hasMany(LampiranPermohonan::class, 'permohonan_id');
    }

    public function lampiranHasil(): HasMany
    {
        return $this->hasMany(LampiranHasil::class, 'permohonan_id');
    }

    public function disposisi(): HasMany
    {
        return $this->hasMany(Disposisi::class, 'permohonan_id');
    }

    public function riwayatStatus(): HasMany
    {
        return $this->hasMany(RiwayatStatus::class, 'permohonan_id')->orderBy('created_at');
    }

    public function suratKeluar(): HasMany
    {
        return $this->hasMany(SuratKeluar::class, 'permohonan_id');
    }

    public function surveiRespon(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(SurveiRespon::class, 'permohonan_id');
    }

    /**
     * Permohonan yang sudah selesai tapi SKM-nya belum diisi pemohon —
     * dipakai untuk daftar "Belum Isi SKM" & notifikasi pengingat.
     */
    public function scopeSelesaiBelumSurvei($query)
    {
        return $query->where('status', 'selesai')->whereDoesntHave('surveiRespon');
    }

    /**
     * Catat perubahan status secara konsisten: update kolom status utama
     * SEKALIGUS menambah baris riwayat (append-only, tidak pernah diedit).
     * Selalu pakai method ini untuk ganti status, jangan update 'status'
     * langsung lewat ->update(), supaya riwayat_status tidak pernah bolong.
     */
    /**
     * Nomor tiket baru: {tanggal:yymmdd}-{acak 3 digit}, contoh: 250912-483.
     * Bagian belakang SENGAJA acak (bukan urut 001, 002, dst) supaya tidak
     * gampang ditebak/di-scan (nomor sekuensial lama gampang di-enumerate
     * tinggal increment angka). Prefix tanggal tetap dipertahankan biar
     * masih ada konteks & gampang diingat/ditulis manual.
     * ~1000 kemungkinan per hari, dikombinasikan dengan throttle di route
     * publik (lihat routes/web.php) — cukup buat volume harian PTSP normal,
     * tapi kalau volume permohonan per hari sudah mendekati ratusan,
     * pertimbangkan naikkan jadi 4 digit atau tambah karakter huruf.
     * Dibungkus transaksi + lockForUpdate + cek tabrakan supaya aman kalau
     * ada 2 pengajuan masuk bersamaan (tidak mungkin dapat nomor sama).
     */
    public static function generateNoTiket(): string
    {
        $tanggal = now()->format('ymd');

        return DB::transaction(function () use ($tanggal) {
            $terpakai = static::where('no_tiket', 'like', $tanggal . '-%')
                ->lockForUpdate()
                ->pluck('no_tiket')
                ->all();

            for ($percobaan = 0; $percobaan < 50; $percobaan++) {
                $kandidat = $tanggal . '-' . str_pad((string) random_int(0, 999), 3, '0', STR_PAD_LEFT);
                if (!in_array($kandidat, $terpakai, true)) {
                    return $kandidat;
                }
            }

            // Fallback kalau 50x percobaan acak selalu tabrakan — praktis
            // mustahil di volume harian normal, cuma bisa kejadian kalau
            // tiket hari itu sudah mendekati penuh 1000 kombinasi. Pakai
            // jam:menit:detik di belakang supaya tetap dijamin unik,
            // daripada berhenti total / lempar error ke pemohon.
            return $tanggal . '-' . now()->format('His');
        });
    }

    public function catatPerubahanStatus(string $status, ?int $updatedBy, ?string $catatan = null): void
    {
        $this->update(['status' => $status]);

        $this->riwayatStatus()->create([
            'status' => $status,
            'catatan' => $catatan,
            'updated_by' => $updatedBy,
        ]);
    }
}
