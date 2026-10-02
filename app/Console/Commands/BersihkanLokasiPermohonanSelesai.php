<?php

namespace App\Console\Commands;

use App\Models\Permohonan;
use Illuminate\Console\Command;

/**
 * Perbaikan data satu kali (bukan bug baru — bug lama yang datanya sudah
 * kadung salah) untuk permohonan yang statusnya sudah final (selesai /
 * ditolak / dibatalkan) tapi current_seksi_id masih menunjuk ke seksi lama.
 *
 * Ini terjadi karena updateManualPtsp() sebelumnya tidak mengosongkan
 * current_seksi_id saat PTSP menandai "Selesai di Seksi" secara manual —
 * sudah diperbaiki di kode (lihat PermohonanController::updateManualPtsp
 * dan Permohonan::getLokasiSaatIniAttribute), tapi perbaikan itu hanya
 * mencegah kasus BARU. Permohonan lama yang datanya sudah kadung salah
 * perlu dibersihkan lewat command ini.
 *
 * Jalankan dulu dengan --dry-run untuk lihat permohonan mana saja yang
 * kena, baru jalankan tanpa --dry-run kalau sudah yakin.
 *
 *   php artisan permohonan:bersihkan-lokasi-selesai --dry-run
 *   php artisan permohonan:bersihkan-lokasi-selesai
 */
class BersihkanLokasiPermohonanSelesai extends Command
{
    protected $signature = 'permohonan:bersihkan-lokasi-selesai {--dry-run : Tampilkan daftar permohonan yang kena tanpa mengubah apa pun}';

    protected $description = 'Kosongkan current_seksi_id pada permohonan berstatus final (selesai/ditolak/dibatalkan) yang datanya masih nyangkut di seksi lama';

    public function handle(): int
    {
        $query = Permohonan::whereIn('status', Permohonan::STATUS_FINAL)
            ->whereNotNull('current_seksi_id')
            ->with('currentSeksi');

        $affected = $query->get();

        if ($affected->isEmpty()) {
            $this->info('Tidak ada permohonan yang bermasalah. Data sudah bersih.');

            return self::SUCCESS;
        }

        $this->table(
            ['No. Tiket', 'Status', 'Seksi yang nyangkut (akan dikosongkan)'],
            $affected->map(fn (Permohonan $p) => [
                $p->no_tiket,
                $p->status,
                $p->currentSeksi->nama_seksi ?? "(id {$p->current_seksi_id}, seksi sudah terhapus)",
            ])
        );

        if ($this->option('dry-run')) {
            $this->comment(
                $affected->count() . ' permohonan akan dibersihkan. '
                . 'Jalankan tanpa --dry-run untuk benar-benar menerapkan.'
            );

            return self::SUCCESS;
        }

        if (!$this->confirm("Kosongkan current_seksi_id untuk {$affected->count()} permohonan di atas?", true)) {
            $this->comment('Dibatalkan, tidak ada yang diubah.');

            return self::SUCCESS;
        }

        Permohonan::whereIn('id', $affected->pluck('id'))->update(['current_seksi_id' => null]);

        $this->info($affected->count() . ' permohonan berhasil dibersihkan.');

        return self::SUCCESS;
    }
}
