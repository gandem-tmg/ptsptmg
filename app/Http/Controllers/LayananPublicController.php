<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use Illuminate\Http\Request;

/**
 * Halaman publik: siapa saja bisa lihat daftar & detail layanan beserta
 * persyaratannya tanpa perlu login. Login baru diminta saat klik
 * "Ajukan Sekarang" (lihat route layanan.ajukan di web.php).
 *
 * Navigasi utama di sini berbasis KATEGORI KEBUTUHAN (config
 * klasifikasi_layanan.kategori), BUKAN seksi/unit — sesuai kesepakatan
 * desain: masyarakat tidak perlu tahu struktur organisasi Kemenag.
 * Seksi tetap tersimpan di `layanan.seksi_id` dan tetap dipakai apa
 * adanya untuk disposisi internal; di sisi publik ia cuma tampil sebagai
 * info "ditangani oleh" di halaman detail.
 */
class LayananPublicController extends Controller
{
    public function index(Request $request)
    {
        $kategoriConfig = config('klasifikasi_layanan.kategori');
        $personaConfig = config('klasifikasi_layanan.target_pengguna');

        $isFiltering = $request->filled('search') || $request->filled('kategori') || $request->filled('untuk');

        // Layanan pengaduan selalu dikecualikan dari grid kategori — ia
        // tampil sebagai tombol/link terpisah di view (lihat kesepakatan
        // diskusi arsitektur: pengaduan sifatnya lintas-domain).
        $query = Layanan::with('persyaratan')
            ->whereNotNull('kategori')
            ->where('jenis_layanan', '!=', 'pengaduan');

        if ($request->filled('search')) {
            $search = $request->input('search');
            $query->where(function ($q) use ($search) {
                $q->where('nama_layanan', 'like', '%' . $search . '%')
                  ->orWhere('deskripsi', 'like', '%' . $search . '%')
                  ->orWhere('tag_pencarian', 'like', '%' . $search . '%');
            });
        }

        if ($request->filled('kategori')) {
            $query->where('kategori', $request->input('kategori'));
        }

        // Filter wizard: "Anda ini siapa?" -> cocokkan ke target_pengguna
        // (kolom JSON array), pakai whereJsonContains.
        $personaAktif = $request->input('untuk');
        if ($personaAktif && array_key_exists($personaAktif, $personaConfig)) {
            $query->whereJsonContains('target_pengguna', $personaAktif);
        }

        $layanans = $query->orderBy('nama_layanan')->get();

        // Susun per kategori — dipakai mode browse (grid kategori) maupun
        // mode hasil pencarian/wizard (langsung tampil semua yang cocok).
        $kategoris = collect($kategoriConfig)->map(function ($def, $slug) use ($layanans) {
            return (object) [
                'slug' => $slug,
                'label' => $def['label'],
                'deskripsi' => $def['deskripsi'],
                'subkategori' => $def['subkategori'] ?? [],
                'layananList' => $layanans->where('kategori', $slug)->values(),
            ];
        });

        if ($isFiltering) {
            $kategoris = $kategoris->filter(fn ($k) => $k->layananList->isNotEmpty())->values();
        }

        // Layanan pengaduan, buat tombol terpisah di header/footer halaman.
        $layananPengaduan = Layanan::where('jenis_layanan', 'pengaduan')->first();

        // Kalau kata kunci pencarian ternyata cocok ke layanan Pengaduan
        // (yang sengaja dikecualikan dari grid kategori), tandai supaya
        // view bisa mengarahkan pengguna ke tombol Pengaduan alih-alih
        // cuma bilang "tidak ditemukan".
        $searchMatchesPengaduan = false;
        if ($request->filled('search') && $layananPengaduan) {
            $search = mb_strtolower($request->input('search'));
            $haystack = mb_strtolower($layananPengaduan->nama_layanan . ' ' . $layananPengaduan->tag_pencarian);
            $searchMatchesPengaduan = str_contains($haystack, $search);
        }

        // Layanan populer: diambil dari data permohonan asli (paling sering
        // diajukan). Hanya ditampilkan di mode browse polos (bukan hasil
        // pencarian/wizard/filter kategori).
        $layananPopuler = $isFiltering
            ? collect()
            : Layanan::whereNotNull('kategori')
                ->where('jenis_layanan', '!=', 'pengaduan')
                ->withCount('permohonan')
                ->orderByDesc('permohonan_count')
                ->take(6)
                ->get();

        // Kategori mana yang harus kebuka duluan di mode browse — dipakai
        // supaya browser-back / link "Kembali ke daftar layanan" dari
        // halaman detail balik dengan accordion yang relevan sudah
        // terbuka, bukan ketutup dari awal lagi. Divalidasi dulu biar
        // gak nerima slug sembarangan dari query string.
        $bukaKategori = $request->query('buka');
        if (!array_key_exists($bukaKategori, $kategoriConfig)) {
            $bukaKategori = null;
        }

        return view('public.layanan.index', [
            'kategoris' => $kategoris,
            'personaConfig' => $personaConfig,
            'personaAktif' => $personaAktif,
            'isFiltering' => $isFiltering,
            'totalLayanan' => Layanan::whereNotNull('kategori')->where('jenis_layanan', '!=', 'pengaduan')->count(),
            'layananPopuler' => $layananPopuler,
            'layananPengaduan' => $layananPengaduan,
            'searchMatchesPengaduan' => $searchMatchesPengaduan,
            'bukaKategori' => $bukaKategori,
        ]);
    }

    public function show(Layanan $layanan)
    {
        $layanan->load('seksi', 'persyaratan');
        return view('public.layanan.show', compact('layanan'));
    }
}
