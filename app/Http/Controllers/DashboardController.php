<?php

namespace App\Http\Controllers;

use App\Models\Layanan;
use App\Models\Permohonan;
use App\Models\Seksi;

class DashboardController extends Controller
{
    public function index()
    {
        $user = auth()->user();

        return match ($user->role) {
            'pemohon' => $this->pemohonDashboard($user),
            'petugas' => $this->petugasDashboard(),
            'petugas_seksi' => $this->petugasSeksiDashboard($user),
            'pimpinan' => $this->pimpinanDashboard(),
            'admin' => $this->adminDashboard(),
            default => view('dashboard', ['cards' => [], 'listTitle' => null, 'listItems' => collect(), 'routePrefix' => null]),
        };
    }

    /**
     * Bangun link "Lihat Semua" milik satu kartu statistik, mengarah ke
     * halaman daftar permohonan role terkait dengan filter status yang
     * sesuai isi kartu (satu status, beberapa status sekaligus, atau tanpa
     * filter sama sekali kalau $status null). Dipusatkan di sini supaya
     * SEMUA dashboard (poin 1 & 3) konsisten dan gampang dites.
     */
    private function cardHref(string $routePrefix, string|array|null $status = null): ?string
    {
        $routeName = $routePrefix . '.permohonan.index';

        if (!\Illuminate\Support\Facades\Route::has($routeName)) {
            return null;
        }

        return route($routeName, $status ? ['status' => $status] : []);
    }

    private function pemohonDashboard($user)
    {
        $semua = Permohonan::where('user_id', $user->id)->with('layanan')->latest('tanggal_pengajuan')->get();

        $cards = [
            ['label' => 'Perlu Dilengkapi', 'value' => $semua->where('status', 'dikembalikan')->count(), 'color' => 'rose', 'icon' => 'exclamation', 'href' => $this->cardHref('pemohon', 'dikembalikan')],
            ['label' => 'Sedang Berjalan', 'value' => $semua->whereIn('status', Permohonan::statusAktif())->count(), 'color' => 'amber', 'icon' => 'clock', 'href' => $this->cardHref('pemohon', Permohonan::statusAktif())],
            ['label' => 'Total Permohonan', 'value' => $semua->count(), 'color' => 'emerald', 'icon' => 'inbox', 'href' => $this->cardHref('pemohon')],
            ['label' => 'Selesai', 'value' => $semua->where('status', 'selesai')->count(), 'color' => 'sky', 'icon' => 'check', 'href' => $this->cardHref('pemohon', 'selesai')],
        ];

        return view('dashboard', [
            'cards' => $cards,
            'listTitle' => 'Permohonan Saya',
            'listItems' => $semua->take(6),
            'routePrefix' => 'pemohon',
            'emptyText' => 'Belum ada permohonan. Yuk ajukan layanan pertama Anda.',
            'showSeksiOnCard' => true,
        ]);
    }

    private function petugasDashboard()
    {
        $cards = [
            ['label' => 'Total Permohonan', 'value' => Permohonan::count(), 'color' => 'emerald', 'icon' => 'inbox', 'href' => $this->cardHref('petugas')],
            ['label' => 'Perlu Diverifikasi', 'value' => Permohonan::whereIn('status', ['diajukan', 'verifikasi_ptsp'])->count(), 'color' => 'amber', 'icon' => 'clock', 'href' => $this->cardHref('petugas', ['diajukan', 'verifikasi_ptsp'])],
            ['label' => 'Sedang di Seksi', 'value' => Permohonan::whereIn('status', ['didisposisikan', 'diproses_seksi'])->count(), 'color' => 'sky', 'icon' => 'arrow-path', 'href' => $this->cardHref('petugas', ['didisposisikan', 'diproses_seksi'])],
            ['label' => 'Perlu Verifikasi Akhir', 'value' => Permohonan::where('status', 'selesai_seksi')->count(), 'color' => 'purple', 'icon' => 'clock', 'href' => $this->cardHref('petugas', 'selesai_seksi')],
            ['label' => 'Selesai', 'value' => Permohonan::where('status', 'selesai')->count(), 'color' => 'emerald', 'icon' => 'check', 'href' => $this->cardHref('petugas', 'selesai')],
        ];

        $listItems = Permohonan::whereIn('status', ['diajukan', 'verifikasi_ptsp', 'selesai_seksi'])
            ->with('layanan', 'user', 'currentSeksi')
            ->latest('tanggal_pengajuan')
            ->take(8)
            ->get();

        $seksiBreakdown = Seksi::withCount('permohonanAktif')->orderByDesc('permohonan_aktif_count')->get();

        return view('dashboard', [
            'cards' => $cards,
            'listTitle' => 'Perlu Tindakan Anda',
            'listItems' => $listItems,
            'routePrefix' => 'petugas',
            'emptyText' => 'Tidak ada permohonan yang perlu ditindaklanjuti saat ini. Semua sudah beres!',
            'showSeksiOnCard' => true,
            'seksiBreakdown' => $seksiBreakdown,
        ]);
    }

    private function petugasSeksiDashboard($user)
    {
        $base = Permohonan::where('current_seksi_id', $user->seksi_id);

        $cards = [
            ['label' => 'Total di Seksi Ini', 'value' => (clone $base)->count(), 'color' => 'emerald', 'icon' => 'inbox', 'href' => $this->cardHref('seksi')],
            ['label' => 'Baru Masuk', 'value' => (clone $base)->where('status', 'didisposisikan')->count(), 'color' => 'amber', 'icon' => 'clock', 'href' => $this->cardHref('seksi', 'didisposisikan')],
            ['label' => 'Sedang Diproses', 'value' => (clone $base)->where('status', 'diproses_seksi')->count(), 'color' => 'sky', 'icon' => 'arrow-path', 'href' => $this->cardHref('seksi', 'diproses_seksi')],
        ];

        $listItems = (clone $base)
            ->whereIn('status', ['didisposisikan', 'diproses_seksi'])
            ->with('layanan', 'user')
            ->latest('tanggal_pengajuan')
            ->take(8)
            ->get();

        return view('dashboard', [
            'cards' => $cards,
            'listTitle' => 'Perlu Tindakan Anda',
            'listItems' => $listItems,
            'routePrefix' => 'seksi',
            'emptyText' => 'Tidak ada permohonan yang perlu ditindaklanjuti di seksi ini saat ini.',
            'showSeksiOnCard' => false,
        ]);
    }

    private function pimpinanDashboard()
    {
        $cards = [
            ['label' => 'Total Permohonan', 'value' => Permohonan::count(), 'color' => 'emerald', 'icon' => 'inbox', 'href' => $this->cardHref('pimpinan')],
            ['label' => 'Sedang Berjalan', 'value' => Permohonan::whereIn('status', Permohonan::statusAktif())->count(), 'color' => 'amber', 'icon' => 'clock', 'href' => $this->cardHref('pimpinan', Permohonan::statusAktif())],
            ['label' => 'Selesai', 'value' => Permohonan::where('status', 'selesai')->count(), 'color' => 'emerald', 'icon' => 'check', 'href' => $this->cardHref('pimpinan', 'selesai')],
            ['label' => 'Ditolak', 'value' => Permohonan::where('status', 'ditolak')->count(), 'color' => 'rose', 'icon' => 'exclamation', 'href' => $this->cardHref('pimpinan', 'ditolak')],
        ];

        $listItems = Permohonan::with('layanan', 'user', 'currentSeksi')->latest('tanggal_pengajuan')->take(8)->get();
        $seksiBreakdown = Seksi::withCount('permohonanAktif')->orderByDesc('permohonan_aktif_count')->get();

        return view('dashboard', [
            'cards' => $cards,
            'listTitle' => 'Aktivitas Terbaru',
            'listItems' => $listItems,
            'routePrefix' => 'pimpinan',
            'emptyText' => 'Belum ada aktivitas permohonan.',
            'showSeksiOnCard' => true,
            'seksiBreakdown' => $seksiBreakdown,
        ]);
    }

    private function adminDashboard()
    {
        $cards = [
            ['label' => 'Total Layanan', 'value' => Layanan::count(), 'color' => 'emerald', 'icon' => 'briefcase', 'href' => \Illuminate\Support\Facades\Route::has('admin.layanan.index') ? route('admin.layanan.index') : null],
            ['label' => 'Total Permohonan', 'value' => Permohonan::count(), 'color' => 'sky', 'icon' => 'inbox', 'href' => $this->cardHref('admin')],
            ['label' => 'Sedang Berjalan', 'value' => Permohonan::whereIn('status', Permohonan::statusAktif())->count(), 'color' => 'amber', 'icon' => 'clock', 'href' => $this->cardHref('admin', Permohonan::statusAktif())],
            ['label' => 'Selesai', 'value' => Permohonan::where('status', 'selesai')->count(), 'color' => 'emerald', 'icon' => 'check', 'href' => $this->cardHref('admin', 'selesai')],
        ];

        $listItems = Permohonan::with('layanan', 'user', 'currentSeksi')->latest('tanggal_pengajuan')->take(8)->get();

        return view('dashboard', [
            'cards' => $cards,
            'listTitle' => 'Aktivitas Terbaru',
            'listItems' => $listItems,
            'routePrefix' => 'admin',
            'emptyText' => 'Belum ada aktivitas permohonan.',
            'showSeksiOnCard' => true,
        ]);
    }
}
