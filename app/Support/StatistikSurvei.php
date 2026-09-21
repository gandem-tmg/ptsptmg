<?php

namespace App\Support;

use App\Models\SurveiJawaban;
use App\Models\SurveiPertanyaan;
use Illuminate\Support\Collection;

/**
 * Query agregat untuk hasil SKM — dipakai bareng oleh halaman hasil
 * internal (admin/petugas/pimpinan) dan dashboard transparansi publik.
 *
 * PENTING: sama seperti StatistikPermohonan, method di sini murni
 * agregat/hitungan (rata-rata, jumlah, persentase) — tidak ada yang
 * mengembalikan nama/identitas responden, supaya aman dipakai di
 * halaman publik.
 *
 * $dari/$sampai (format Y-m-d) memfilter berdasarkan survei_respon.created_at
 * (kapan survei DIISI) — dipakai untuk filter per bulan/triwulanan di
 * halaman Hasil SKM internal. Semua opsional & default null (tanpa
 * filter), supaya pemanggilan lama (transparansi publik) tidak perlu ubah.
 */
class StatistikSurvei
{
    /**
     * Terapkan filter jenis survei & rentang tanggal ke query whereHas('respon', ...).
     */
    private static function filterRespon($query, ?string $jenisSurvei, ?string $dari, ?string $sampai): void
    {
        $query->whereHas('respon', function ($q) use ($jenisSurvei, $dari, $sampai) {
            if ($jenisSurvei) {
                $q->where('jenis_survei', $jenisSurvei);
            }
            if ($dari) {
                try {
                    $q->whereDate('created_at', '>=', \Carbon\Carbon::parse($dari));
                } catch (\Exception $e) {
                    // format tanggal tidak valid — abaikan filter ini saja
                }
            }
            if ($sampai) {
                try {
                    $q->whereDate('created_at', '<=', \Carbon\Carbon::parse($sampai));
                } catch (\Exception $e) {
                    //
                }
            }
        });
    }

    /**
     * Rata-rata nilai (skala 1-4) per pertanyaan tipe skala_4, plus
     * "Nilai IKM" keseluruhan (rata-rata semua pertanyaan skala_4,
     * dikonversi ke skala 0-100 dengan mengalikan 25 — sesuai pendekatan
     * nilai rata-rata tertimbang di Permenpan RB No. 14/2017, dengan
     * asumsi semua pertanyaan diberi bobot yang sama).
     *
     * $jenisSurvei: null = semua, atau 'per_layanan'/'umum' untuk fokus salah satu.
     */
    public static function rataRataPerPertanyaan(?string $jenisSurvei = null, ?string $dari = null, ?string $sampai = null): Collection
    {
        $pertanyaans = SurveiPertanyaan::where('tipe', 'skala_4')->orderBy('urutan')->get();

        return $pertanyaans->map(function ($pertanyaan) use ($jenisSurvei, $dari, $sampai) {
            $query = SurveiJawaban::where('survei_pertanyaan_id', $pertanyaan->id)
                ->whereNotNull('nilai_rating');

            self::filterRespon($query, $jenisSurvei, $dari, $sampai);

            $jumlah = (clone $query)->count();
            $rataRata = $jumlah > 0 ? round((clone $query)->avg('nilai_rating'), 2) : null;

            return (object) [
                'pertanyaan' => $pertanyaan->teks_pertanyaan,
                'jumlah_jawaban' => $jumlah,
                'rata_rata' => $rataRata,
            ];
        })->values();
    }

    /**
     * Satu angka ringkas "Nilai IKM" (skala 0-100) dari seluruh jawaban
     * skala_4 yang ada, beserta kategori mutunya sesuai ambang batas resmi
     * Permenpan RB No. 14/2017.
     */
    public static function nilaiIkm(?string $jenisSurvei = null, ?string $dari = null, ?string $sampai = null): ?array
    {
        $query = SurveiJawaban::whereNotNull('nilai_rating');
        self::filterRespon($query, $jenisSurvei, $dari, $sampai);

        $jumlah = (clone $query)->count();
        if ($jumlah === 0) {
            return null;
        }

        $rataRata = (clone $query)->avg('nilai_rating');
        $nilaiIkm = round($rataRata * 25, 2);

        $kategori = match (true) {
            $nilaiIkm >= 88.31 => 'Sangat Baik',
            $nilaiIkm >= 76.61 => 'Baik',
            $nilaiIkm >= 65.00 => 'Kurang Baik',
            default => 'Tidak Baik',
        };

        return ['nilai' => $nilaiIkm, 'kategori' => $kategori, 'jumlah_jawaban' => $jumlah];
    }

    /**
     * Rata-rata skala_4 per layanan — cuma dari survei per_layanan
     * (yang jenis 'umum' tidak terkait ke layanan tertentu).
     */
    public static function rataRataPerLayanan(?string $dari = null, ?string $sampai = null): Collection
    {
        $query = SurveiJawaban::whereNotNull('nilai_rating');
        self::filterRespon($query, 'per_layanan', $dari, $sampai);

        return $query
            ->with('respon.permohonan.layanan')
            ->get()
            ->filter(fn ($j) => $j->respon->permohonan && $j->respon->permohonan->layanan)
            ->groupBy(fn ($j) => $j->respon->permohonan->layanan->nama_layanan)
            ->map(fn ($group, $namaLayanan) => (object) [
                'nama_layanan' => $namaLayanan,
                'rata_rata' => round($group->avg('nilai_rating'), 2),
                'jumlah_jawaban' => $group->count(),
            ])
            ->sortBy('nama_layanan')
            ->values();
    }

    /**
     * Rata-rata skala_4 per seksi (lewat layanan.seksi_id).
     */
    public static function rataRataPerSeksi(?string $dari = null, ?string $sampai = null): Collection
    {
        $query = SurveiJawaban::whereNotNull('nilai_rating');
        self::filterRespon($query, 'per_layanan', $dari, $sampai);

        return $query
            ->with('respon.permohonan.layanan.seksi')
            ->get()
            ->filter(fn ($j) => $j->respon->permohonan?->layanan?->seksi)
            ->groupBy(fn ($j) => $j->respon->permohonan->layanan->seksi->nama_seksi)
            ->map(fn ($group, $namaSeksi) => (object) [
                'nama_seksi' => $namaSeksi,
                'rata_rata' => round($group->avg('nilai_rating'), 2),
                'jumlah_jawaban' => $group->count(),
            ])
            ->sortBy('nama_seksi')
            ->values();
    }

    /**
     * Distribusi jawaban untuk satu pertanyaan tipe pilihan_ganda.
     */
    public static function distribusiPilihanGanda(SurveiPertanyaan $pertanyaan): Collection
    {
        return SurveiJawaban::where('survei_pertanyaan_id', $pertanyaan->id)
            ->whereNotNull('pilihan_jawaban')
            ->get()
            ->groupBy('pilihan_jawaban')
            ->map(fn ($group, $pilihan) => (object) ['pilihan' => $pilihan, 'jumlah' => $group->count()])
            ->sortByDesc('jumlah')
            ->values();
    }

    /**
     * Distribusi keseluruhan jawaban skala 1-4 (Tidak Baik..Sangat Baik) —
     * dipakai untuk donut chart ringkasan kepuasan di halaman Hasil SKM.
     */
    public static function distribusiSkala(?string $dari = null, ?string $sampai = null): array
    {
        $query = SurveiJawaban::whereNotNull('nilai_rating');
        self::filterRespon($query, null, $dari, $sampai);

        $counts = $query
            ->selectRaw('nilai_rating, count(*) as jumlah')
            ->groupBy('nilai_rating')
            ->pluck('jumlah', 'nilai_rating');

        return [
            'Tidak Baik' => (int) ($counts[1] ?? 0),
            'Kurang Baik' => (int) ($counts[2] ?? 0),
            'Baik' => (int) ($counts[3] ?? 0),
            'Sangat Baik' => (int) ($counts[4] ?? 0),
        ];
    }
}
