<?php

namespace App\Support;

use App\Models\Permohonan;
use Illuminate\Support\Collection;

/**
 * Kumpulan query agregat untuk halaman Statistik (internal, admin/petugas)
 * dan Dashboard Transparansi (publik). Dipisah ke sini supaya kedua
 * controller itu tidak duplikasi logic yang sama.
 *
 * PENTING: semua method di sini murni agregat/hitungan — tidak ada satupun
 * yang mengembalikan data pribadi pemohon (nama, NIK, dst), supaya aman
 * juga dipakai di halaman publik (transparansi).
 */
class StatistikPermohonan
{
    /**
     * Terapkan filter umum (rentang tanggal & layanan/seksi) ke query
     * permohonan. $seksiId di sini sengaja merujuk ke seksi PEMILIK LAYANAN
     * (layanan.seksi_id), BUKAN current_seksi_id — soalnya current_seksi_id
     * dikosongkan lagi begitu permohonan selesai ditangani seksi (lihat
     * PermohonanController::selesaikanSeksi()), jadi tidak bisa dipakai
     * untuk rekap historis, cuma valid untuk "beban aktif saat ini".
     */
    public static function applyFilter($query, ?string $dari, ?string $sampai, ?int $layananId, ?int $seksiId): void
    {
        if ($dari) {
            try {
                $query->whereDate('tanggal_pengajuan', '>=', \Carbon\Carbon::parse($dari));
            } catch (\Exception $e) {
                // format tanggal tidak valid — abaikan filter ini saja
            }
        }

        if ($sampai) {
            try {
                $query->whereDate('tanggal_pengajuan', '<=', \Carbon\Carbon::parse($sampai));
            } catch (\Exception $e) {
                //
            }
        }

        if ($layananId) {
            $query->where('layanan_id', $layananId);
        }

        if ($seksiId) {
            $query->whereHas('layanan', fn ($q) => $q->where('seksi_id', $seksiId));
        }
    }

    /**
     * Rata-rata hari dari tanggal pengajuan sampai status 'selesai'
     * tercapai, per layanan. Cuma menghitung yang sudah 'selesai'.
     */
    public static function rataRataHariSelesaiPerLayanan($filteredQuery): Collection
    {
        $permohonans = (clone $filteredQuery)
            ->where('status', 'selesai')
            ->with(['layanan', 'riwayatStatus' => fn ($q) => $q->where('status', 'selesai')])
            ->get();

        return $permohonans
            ->filter(fn ($p) => $p->layanan && $p->tanggal_pengajuan && $p->riwayatStatus->first())
            ->groupBy('layanan_id')
            ->map(function ($group) {
                $totalHari = 0;
                foreach ($group as $p) {
                    $totalHari += $p->tanggal_pengajuan->diffInDays($p->riwayatStatus->first()->created_at);
                }

                return (object) [
                    'nama_layanan' => $group->first()->layanan->nama_layanan,
                    'jumlah_selesai' => $group->count(),
                    'rata_rata_hari' => round($totalHari / $group->count(), 1),
                ];
            })
            ->sortByDesc('jumlah_selesai')
            ->values();
    }

    /**
     * Jumlah permohonan yang SAAT INI aktif di tiap seksi (masih
     * didisposisikan / diproses seksi) — dipakai untuk lihat beban kerja
     * kekinian, bukan rekap historis (makanya boleh pakai current_seksi_id).
     *
     * $seksiId opsional: kalau diisi, hasilnya dibatasi ke satu seksi saja
     * (dipakai untuk statistik petugas seksi yang tidak boleh lihat beban
     * seksi lain).
     */
    public static function bebanAktifPerSeksi(?int $seksiId = null): Collection
    {
        return Permohonan::whereIn('status', ['didisposisikan', 'diproses_seksi'])
            ->whereNotNull('current_seksi_id')
            ->when($seksiId, fn ($q) => $q->where('current_seksi_id', $seksiId))
            ->with('currentSeksi')
            ->get()
            ->filter(fn ($p) => $p->currentSeksi)
            ->groupBy('current_seksi_id')
            ->map(fn ($group) => (object) [
                'nama_seksi' => $group->first()->currentSeksi->nama_seksi,
                'jumlah' => $group->count(),
            ])
            ->sortByDesc('jumlah')
            ->values();
    }

    /**
     * Layanan paling banyak diajukan (dalam filter yang berlaku).
     */
    public static function layananTerpopuler($filteredQuery, int $limit = 5): Collection
    {
        return (clone $filteredQuery)
            ->with('layanan')
            ->get()
            ->filter(fn ($p) => $p->layanan)
            ->groupBy('layanan_id')
            ->map(fn ($group) => (object) [
                'nama_layanan' => $group->first()->layanan->nama_layanan,
                'jumlah' => $group->count(),
            ])
            ->sortByDesc('jumlah')
            ->take($limit)
            ->values();
    }

    /**
     * Tren jumlah pengajuan per bulan (N bulan terakhir). Sengaja tidak
     * ikut menerima filter rentang tanggal (dari/sampai) — soalnya tujuan
     * grafik ini justru menunjukkan gambaran lintas bulan; filter
     * layanan/seksi tetap berlaku kalau ada.
     */
    public static function trenBulanan(?int $layananId, ?int $seksiId, int $bulanTerakhir = 6): array
    {
        $labels = [];
        $data = [];

        for ($i = $bulanTerakhir - 1; $i >= 0; $i--) {
            $date = now()->subMonths($i);
            $labels[] = $date->format('M Y');

            $query = Permohonan::query();
            static::applyFilter($query, null, null, $layananId, $seksiId);

            $data[] = $query->whereYear('tanggal_pengajuan', $date->year)
                ->whereMonth('tanggal_pengajuan', $date->month)
                ->count();
        }

        return ['labels' => $labels, 'data' => $data];
    }
}
