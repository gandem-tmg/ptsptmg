<?php

namespace App\Http\Controllers;

use App\Models\Disposisi;
use App\Models\LampiranHasil;
use App\Models\LampiranPermohonan;
use App\Models\Layanan;
use App\Models\Permohonan;
use App\Models\Persyaratan;
use App\Models\Seksi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use Barryvdh\DomPDF\Facade\Pdf;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class PermohonanController extends Controller
{
    public function index(Request $request)
    {
        $user = auth()->user();
        $search = $request->input('search');

        if ($user->role === 'pemohon') {
            $query = Permohonan::where('user_id', $user->id)->with(['layanan', 'surveiRespon']);
            $this->applySearch($query, $search, withUser: false);
            $this->applyFilters($query, $request);
            $permohonans = $query->paginate(20);
            return view('pemohon.permohonan.index', compact('permohonans'));
        } elseif ($user->role === 'admin') {
            $query = Permohonan::with('layanan', 'user', 'currentSeksi');
            $this->applySearch($query, $search, withUser: true);
            $this->applyFilters($query, $request);
            $permohonans = $query->paginate(20);
            $seksis = Seksi::orderBy('nama_seksi')->get();
            return view('admin.permohonan.index', compact('permohonans', 'seksis'));
        } elseif ($user->role === 'petugas') {
            // PTSP mengawasi semua permohonan lintas seksi.
            $query = Permohonan::with('layanan', 'user', 'currentSeksi');
            $this->applySearch($query, $search, withUser: true);
            $this->applyFilters($query, $request);
            $permohonans = $query->paginate(20);
            $seksis = Seksi::orderBy('nama_seksi')->get();
            return view('petugas.permohonan.index', compact('permohonans', 'seksis'));
        } elseif ($user->role === 'petugas_seksi') {
            // Hanya permohonan yang sedang berada di seksi milik petugas ini.
            $query = Permohonan::where('current_seksi_id', $user->seksi_id)->with('layanan', 'user');
            $this->applySearch($query, $search, withUser: true);
            $this->applyFilters($query, $request);
            $permohonans = $query->paginate(20);
            return view('petugas_seksi.permohonan.index', compact('permohonans'));
        } elseif ($user->role === 'pimpinan') {
            // Read-only, lintas seksi, untuk monitoring.
            $query = Permohonan::with('layanan', 'user', 'currentSeksi');
            $this->applySearch($query, $search, withUser: true);
            $this->applyFilters($query, $request);
            $permohonans = $query->paginate(20);
            $seksis = Seksi::orderBy('nama_seksi')->get();
            return view('pimpinan.permohonan.index', compact('permohonans', 'seksis'));
        }

        abort(403);
    }

    /**
     * Filter lanjutan di halaman index: status, seksi (untuk role lintas
     * seksi), dan rentang tanggal pengajuan. Tanggal dibungkus try/catch
     * supaya format tanggal yang tidak valid cuma bikin filter itu diabaikan,
     * bukan bikin seluruh halaman index gagal.
     */
    private function applyFilters($query, Request $request): void
    {
        // 'status' bisa berupa satu nilai (dari dropdown filter) ATAU
        // array (dari link kartu dashboard yang mewakili beberapa status
        // sekaligus, mis. "Sedang Berjalan") — keduanya didukung supaya
        // link kartu dashboard bisa langsung dipakai tanpa endpoint terpisah.
        if ($status = $request->input('status')) {
            if (is_array($status)) {
                $query->whereIn('status', $status);
            } else {
                $query->where('status', $status);
            }
        }

        if ($seksiId = $request->input('seksi_id')) {
            $query->where('current_seksi_id', $seksiId);
        }

        if ($dari = $request->input('tanggal_dari')) {
            try {
                $query->whereDate('tanggal_pengajuan', '>=', \Carbon\Carbon::parse($dari));
            } catch (\Exception $e) {
                // format tanggal tidak valid — abaikan filter ini saja
            }
        }

        if ($sampai = $request->input('tanggal_sampai')) {
            try {
                $query->whereDate('tanggal_pengajuan', '<=', \Carbon\Carbon::parse($sampai));
            } catch (\Exception $e) {
                //
            }
        }
    }

    private function applySearch($query, ?string $search, bool $withUser): void
    {
        if (!$search) {
            return;
        }

        $query->where(function ($q) use ($search, $withUser) {
            $q->where('no_tiket', 'like', '%' . $search . '%')
              ->orWhere('status', 'like', '%' . $search . '%')
              ->orWhereHas('layanan', function ($q2) use ($search) {
                  $q2->where('nama_layanan', 'like', '%' . $search . '%');
              });

            if ($withUser) {
                $q->orWhereHas('user', function ($q2) use ($search) {
                    $q2->where('name', 'like', '%' . $search . '%');
                });
            }
        });
    }

    public function create(Request $request)
    {
        $layanans = Layanan::with('persyaratan', 'seksi')->get();
        $persyaratanByLayanan = $layanans->mapWithKeys(function ($layanan) {
            return [$layanan->id => $layanan->persyaratan];
        });
        // Pre-select layanan kalau datang dari halaman publik /layanan/{layanan}.
        $selectedLayananId = $request->integer('layanan_id') ?: null;
        return view('pemohon.permohonan.create', compact('layanans', 'persyaratanByLayanan', 'selectedLayananId'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
            // "required" di level ini TIDAK benar-benar mewajibkan apa-apa kalau
            // request sama sekali tidak mengirim key 'lampiran' (wildcard tanpa
            // data yang cocok = lolos begitu saja) — makanya sebelumnya pemohon
            // bisa submit tanpa lampiran wajib sama sekali. Pengecekan wajib yang
            // sesungguhnya dilakukan manual per persyaratan di bawah, sama seperti
            // yang sudah dipakai di guestStore().
            'lampiran.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $persyaratans = Persyaratan::where('layanan_id', $request->layanan_id)->get();

        foreach ($persyaratans as $index => $persyaratan) {
            if ($persyaratan->wajib && !$request->hasFile('lampiran.' . $index)) {
                return back()->withErrors([
                    'lampiran.' . $index => 'Lampiran untuk ' . $persyaratan->nama_persyaratan . ' wajib diunggah.',
                ])->withInput();
            }
        }

        $permohonan = Permohonan::create([
            'user_id' => auth()->id(),
            'layanan_id' => $request->layanan_id,
            'tanggal_pengajuan' => now(),
            'status' => 'diajukan',
            'sumber_pengajuan' => 'online',
            'no_tiket' => Permohonan::generateNoTiket(),
        ]);

        $this->simpanLampiranPersyaratan($request, $permohonan);
        $this->generateQrCode($permohonan);
        $permohonan->catatPerubahanStatus('diajukan', auth()->id(), 'Permohonan diajukan oleh pemohon.');

        $permohonan->load('lampiranPermohonan.persyaratan');
        $this->generateBuktiPdf($permohonan);

        $user = auth()->user();
        $user->notify(new \App\Notifications\PermohonanSubmitted($permohonan, 'permohonan_pdf/' . $permohonan->no_tiket . '.pdf'));

        return redirect()->route('pemohon.permohonan.index')->with('warning', 'Permohonan berhasil diajukan. No Tiket Anda: ' . $permohonan->no_tiket . '. Bukti pengajuan telah dikirim ke email Anda.');
    }

    public function show(Permohonan $permohonan)
    {
        $this->authorizeAccessPermohonan($permohonan);

        $permohonan->load(
            'layanan.persyaratan',
            'layanan.seksi',
            'user',
            'currentSeksi',
            'lampiranPermohonan.persyaratan',
            'lampiranHasil.pengunggah',
            'disposisi.seksi',
            'disposisi.petugasPengirim',
            'disposisi.petugasPenerima',
            'riwayatStatus.petugas',
            'suratKeluar.penandatangan'
        );

        return match (auth()->user()->role) {
            'pemohon' => view('pemohon.permohonan.show', compact('permohonan')),
            'petugas' => view('petugas.permohonan.show', compact('permohonan')),
            'petugas_seksi' => view('petugas_seksi.permohonan.show', compact('permohonan')),
            'admin' => view('admin.permohonan.show', compact('permohonan')),
            'pimpinan' => view('pimpinan.permohonan.show', compact('permohonan')),
            default => abort(403),
        };
    }

    /**
     * PTSP: disposisikan permohonan ke seksi terkait.
     * Kalau seksi_id tidak dikirim manual, pakai default dari layanan.
     */
    public function disposisikan(Request $request, Permohonan $permohonan)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['petugas', 'admin'])) {
            abort(403);
        }

        $request->validate([
            'seksi_id' => 'nullable|exists:seksi,id',
            'catatan' => 'nullable|string',
        ]);

        $seksiId = $request->input('seksi_id')
            ?: ($permohonan->is_manual ? $permohonan->manual_seksi_id : $permohonan->layanan->seksi_id);

        if (!$seksiId) {
            return back()->withErrors([
                'seksi_id' => 'Layanan ini belum punya seksi penanggung jawab default. Pilih seksi tujuan secara manual.',
            ]);
        }

        Disposisi::create([
            'permohonan_id' => $permohonan->id,
            'seksi_id' => $seksiId,
            'didisposisikan_oleh' => $user->id,
            'catatan' => $request->catatan,
            'tanggal_disposisi' => now(),
        ]);

        $permohonan->update(['current_seksi_id' => $seksiId]);
        $permohonan->catatPerubahanStatus('didisposisikan', $user->id, $request->catatan);

        return back()->with('success', 'Permohonan berhasil didisposisikan ke seksi terkait.');
    }

    /**
     * PTSP: kembalikan ke pemohon karena berkas kurang lengkap.
     */
    public function kembalikan(Request $request, Permohonan $permohonan)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['petugas', 'admin'])) {
            abort(403);
        }

        $request->validate(['catatan' => 'required|string']);

        $permohonan->catatPerubahanStatus('dikembalikan', $user->id, $request->catatan);

        return back()->with('success', 'Permohonan dikembalikan ke pemohon untuk dilengkapi.');
    }

    /**
     * Petugas seksi: acknowledge disposisi yang baru masuk.
     */
    public function terimaDisposisi(Permohonan $permohonan)
    {
        $user = auth()->user();
        if ($user->role !== 'petugas_seksi' || $permohonan->current_seksi_id !== $user->seksi_id) {
            abort(403);
        }

        $disposisi = $permohonan->disposisi()
            ->whereNull('diterima_oleh')
            ->latest('tanggal_disposisi')
            ->first();

        if ($disposisi) {
            $disposisi->update(['diterima_oleh' => $user->id, 'tanggal_diterima' => now()]);
        }

        $permohonan->catatPerubahanStatus('diproses_seksi', $user->id, 'Disposisi diterima oleh seksi, mulai ditindaklanjuti.');

        return back()->with('success', 'Disposisi diterima.');
    }

    /**
     * Petugas seksi: catat progres/checkpoint tanpa mengubah status utama.
     * Dipakai untuk catatan seperti "sedang diproses via sistem nasional",
     * "menunggu kehadiran pemohon", "menunggu tanda tangan", dst.
     */
    public function updateProgresSeksi(Request $request, Permohonan $permohonan)
    {
        $user = auth()->user();
        if ($user->role !== 'petugas_seksi' || $permohonan->current_seksi_id !== $user->seksi_id) {
            abort(403);
        }

        $request->validate(['catatan' => 'required|string']);

        $permohonan->catatPerubahanStatus($permohonan->status, $user->id, $request->catatan);

        return back()->with('success', 'Progres berhasil dicatat.');
    }

    /**
     * Petugas seksi: tandai selesai, unggah dokumen hasil (opsional),
     * kembalikan ke PTSP untuk verifikasi akhir.
     */
    public function selesaikanSeksi(Request $request, Permohonan $permohonan)
    {
        $user = auth()->user();
        if ($user->role !== 'petugas_seksi' || $permohonan->current_seksi_id !== $user->seksi_id) {
            abort(403);
        }

        $request->validate([
            'catatan' => 'nullable|string',
            'dokumen_hasil' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:5120',
            'nama_dokumen' => 'required_with:dokumen_hasil|string|max:255',
        ]);

        if ($request->hasFile('dokumen_hasil')) {
            $path = $request->file('dokumen_hasil')->store('lampiran_hasil', 'public');
            LampiranHasil::create([
                'permohonan_id' => $permohonan->id,
                'nama_dokumen' => $request->nama_dokumen,
                'file_path' => $path,
                'diunggah_oleh' => $user->id,
                'tanggal_unggah' => now(),
            ]);
        }

        $disposisi = $permohonan->disposisi()
            ->whereNull('tanggal_selesai_seksi')
            ->latest('tanggal_disposisi')
            ->first();

        if ($disposisi) {
            $disposisi->update(['tanggal_selesai_seksi' => now()]);
        }

        $permohonan->update(['current_seksi_id' => null]);
        $permohonan->catatPerubahanStatus(
            'selesai_seksi',
            $user->id,
            $request->catatan ?: 'Ditindaklanjuti oleh seksi, dikembalikan ke PTSP.'
        );

        return back()->with('success', 'Permohonan diselesaikan di seksi dan dikembalikan ke PTSP.');
    }

    /**
     * PTSP: verifikasi akhir hasil dari seksi, lalu serahkan ke pemohon.
     */
    public function verifikasiAkhir(Request $request, Permohonan $permohonan)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['petugas', 'admin'])) {
            abort(403);
        }

        $request->validate(['catatan' => 'nullable|string']);

        $permohonan->catatPerubahanStatus(
            'selesai',
            $user->id,
            $request->catatan ?: 'Diverifikasi PTSP dan diserahkan ke pemohon.'
        );

        return back()->with('success', 'Permohonan selesai dan siap diserahkan ke pemohon.');
    }

    /**
     * Opsi A (disepakati): PTSP boleh membantu update status atas nama seksi
     * yang belum aktif memakai sistem — TAPI wajib isi alasan, dan catatannya
     * otomatis ditandai "[Update manual oleh PTSP]" supaya jelas beda dengan
     * update asli dari petugas seksi. Sengaja dibatasi hanya untuk 2 transisi
     * ini (bukan bebas ke semua status) supaya tidak jadi jalan pintas total.
     */
    public function updateManualPtsp(Request $request, Permohonan $permohonan)
    {
        $user = auth()->user();
        if (!in_array($user->role, ['petugas', 'admin'])) {
            abort(403);
        }

        $request->validate([
            'status' => 'required|in:diproses_seksi,selesai_seksi',
            'catatan' => 'required|string|min:5',
        ]);

        // Ikut update baris disposisi terkait, supaya "Riwayat Disposisi"
        // tidak nyangkut di "belum diterima seksi" walau status sebenarnya
        // sudah maju (bug yang sempat dilaporkan).
        $disposisi = $permohonan->disposisi()->latest('tanggal_disposisi')->first();
        if ($disposisi) {
            if ($request->status === 'diproses_seksi' && !$disposisi->tanggal_diterima) {
                $disposisi->update(['tanggal_diterima' => now()]);
            }
            if ($request->status === 'selesai_seksi' && !$disposisi->tanggal_selesai_seksi) {
                $disposisi->update([
                    'tanggal_diterima' => $disposisi->tanggal_diterima ?? now(),
                    'tanggal_selesai_seksi' => now(),
                ]);
            }
        }

        $permohonan->catatPerubahanStatus(
            $request->status,
            $user->id,
            '[Update manual oleh PTSP] ' . $request->catatan
        );

        return back()->with('success', 'Status berhasil diupdate secara manual.');
    }

    /**
     * Pimpinan: monitoring lintas seksi, read-only.
     */
    public function monitoring(Request $request)
    {
        $user = auth()->user();
        if ($user->role !== 'pimpinan') {
            abort(403);
        }

        $query = Permohonan::with('layanan.seksi', 'currentSeksi', 'user');

        if ($request->filled('seksi_id')) {
            $query->where('current_seksi_id', $request->input('seksi_id'));
        }
        if ($request->filled('status')) {
            $status = $request->input('status');
            is_array($status) ? $query->whereIn('status', $status) : $query->where('status', $status);
        }

        $permohonans = $query->latest('tanggal_pengajuan')->paginate(20);
        $seksis = Seksi::orderBy('nama_seksi')->get();

        return view('pimpinan.monitoring.index', compact('permohonans', 'seksis'));
    }

    /**
     * Admin: override manual (dipertahankan untuk koreksi data),
     * tetap dicatat lewat catatPerubahanStatus supaya riwayat tidak bolong.
     */
    public function updateStatus(Request $request, Permohonan $permohonan)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|string',
            'no_tiket_admin' => 'nullable|string|max:50',
            'catatan_admin' => 'nullable|string',
        ]);

        $permohonan->update($request->only(['no_tiket_admin', 'catatan_admin']));
        $permohonan->catatPerubahanStatus($request->status, auth()->id(), $request->catatan_admin ?: 'Override manual oleh admin.');

        return redirect()->back()->with('success', 'Status berhasil diperbarui.');
    }

    /**
     * Unduh bukti pengajuan untuk user yang SUDAH LOGIN (pemohon/petugas/
     * petugas_seksi/admin/pimpinan). Otorisasi dicek lewat
     * authorizeAccessPermohonan() yang sama dipakai show() & download
     * lampiran — supaya konsisten: pemohon hanya boleh permohonan miliknya
     * sendiri, petugas_seksi hanya yang pernah didisposisikan ke seksinya.
     * (Sebelumnya di sini cuma dicek role === 'pemohon', jadi petugas_seksi
     * bisa download PDF permohonan seksi LAIN kalau tahu ID-nya — sudah
     * diperbaiki.)
     */
    public function downloadPdf(Permohonan $permohonan)
    {
        $this->authorizeAccessPermohonan($permohonan);

        return $this->streamBuktiPdf($permohonan);
    }

    /**
     * Unduh bukti pengajuan untuk PEMOHON TANPA AKUN (guest/walk-in
     * online) — publik, tanpa login. Diakses lewat no_tiket (bukan ID
     * numerik), lihat catatan di routes/web.php kenapa.
     */
    public function downloadPdfGuest(string $no_tiket)
    {
        $permohonan = Permohonan::where('no_tiket', $no_tiket)->firstOrFail();

        return $this->streamBuktiPdf($permohonan);
    }

    private function streamBuktiPdf(Permohonan $permohonan)
    {
        $permohonan->load('lampiranPermohonan.persyaratan');

        $pdfPath = storage_path('app/public/permohonan_pdf/' . $permohonan->no_tiket . '.pdf');

        if (!file_exists($pdfPath)) {
            $this->generateBuktiPdf($permohonan);
        }

        return response()->download($pdfPath, 'bukti_pengajuan_' . $permohonan->no_tiket . '.pdf');
    }

    public function edit(Permohonan $permohonan)
    {
        return view('admin.permohonan.edit', compact('permohonan'));
    }

    public function update(Request $request, Permohonan $permohonan)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        $request->validate([
            'status' => 'required|string',
            'no_tiket_admin' => 'nullable|string|max:50',
            'catatan_admin' => 'nullable|string',
        ]);

        $permohonan->update($request->only(['no_tiket_admin', 'catatan_admin']));
        $permohonan->catatPerubahanStatus($request->status, auth()->id(), $request->catatan_admin ?: 'Diubah manual oleh admin.');

        return redirect()->route('admin.permohonan.index')->with('success', 'Permohonan berhasil diperbarui.');
    }

    /**
     * Hapus permohonan secara permanen — SENGAJA dibatasi khusus admin.
     * Petugas PTSP dan petugas seksi tidak diberi aksi ini supaya data
     * permohonan tidak pernah bisa hilang dari histori; kalau memang ada
     * kesalahan pengajuan, alurnya lewat perubahan status (ditolak /
     * dibatalkan), bukan penghapusan data.
     */
    public function destroy(Permohonan $permohonan)
    {
        if (auth()->user()->role !== 'admin') {
            abort(403);
        }

        foreach ($permohonan->lampiranPermohonan as $lampiran) {
            Storage::disk('public')->delete($lampiran->file_path);
            $lampiran->delete();
        }

        foreach ($permohonan->lampiranHasil as $lampiran) {
            Storage::disk('public')->delete($lampiran->file_path);
            $lampiran->delete();
        }

        Storage::disk('public')->delete('permohonan_pdf/' . $permohonan->no_tiket . '.pdf');
        if ($permohonan->qr_code_path) {
            Storage::disk('public')->delete($permohonan->qr_code_path);
        }

        $permohonan->delete();

        return redirect()->route('admin.permohonan.index')->with('success', 'Permohonan berhasil dihapus.');
    }

    /**
     * Pemohon: kumpulan semua dokumen resmi (surat hasil) dari SELURUH
     * permohonan miliknya, jadi tidak perlu buka satu-satu.
     */
    public function dokumenSaya()
    {
        $user = auth()->user();

        // Hanya dokumen dari permohonan yang SUDAH selesai diverifikasi PTSP
        // (status 'selesai') — dokumen dari seksi yang belum diverifikasi
        // akhir tidak ditampilkan di sini dulu, biar tidak dianggap final.
        $dokumen = LampiranHasil::whereHas('permohonan', function ($q) use ($user) {
            $q->where('user_id', $user->id)->where('status', 'selesai');
        })
            ->with('permohonan.layanan')
            ->latest('tanggal_unggah')
            ->paginate(20);

        return view('pemohon.dokumen.index', compact('dokumen'));
    }

    /**
     * Pemohon membatalkan pengajuannya sendiri. Hanya diperbolehkan selama
     * permohonan belum diterima seksi (status 'diajukan' atau
     * 'didisposisikan') — begitu seksi menerima dan mulai memproses,
     * pembatalan sepihak berisiko bikin data ganda/simpang siur dengan
     * kerja yang sudah berjalan di seksi.
     */
    public function batalkan(Permohonan $permohonan)
    {
        $user = auth()->user();

        if ($permohonan->user_id !== $user->id) {
            abort(403);
        }

        if (!in_array($permohonan->status, ['diajukan', 'didisposisikan'])) {
            return back()->withErrors([
                'batal' => 'Permohonan ini sudah mulai ditangani seksi dan tidak bisa dibatalkan sendiri. Silakan hubungi petugas PTSP.',
            ]);
        }

        $permohonan->current_seksi_id = null;
        $permohonan->save();

        $permohonan->catatPerubahanStatus('dibatalkan', $user->id, 'Dibatalkan mandiri oleh pemohon.');

        return redirect()->route('pemohon.permohonan.index')->with('success', 'Permohonan berhasil dibatalkan.');
    }

    /**
     * Cek apakah user yang login boleh mengakses permohonan ini.
     * Dipakai bersama oleh show() dan endpoint download lampiran.
     */
    private function authorizeAccessPermohonan(Permohonan $permohonan): void
    {
        $user = auth()->user();

        if ($user->role === 'pemohon' && $permohonan->user_id !== $user->id) {
            abort(403);
        }

        if ($user->role === 'petugas_seksi') {
            $pernahDitangani = $permohonan->disposisi()->where('seksi_id', $user->seksi_id)->exists();
            if (!$pernahDitangani) {
                abort(403);
            }
        }
    }

    /**
     * Unduh lampiran dari pemohon dengan nama file yang mudah dibaca
     * (sesuai nama persyaratan), bukan nama acak hasil enkripsi Laravel.
     */
    public function downloadLampiran(LampiranPermohonan $lampiran)
    {
        $this->authorizeAccessPermohonan($lampiran->permohonan);

        $ext = pathinfo($lampiran->file_path, PATHINFO_EXTENSION);
        $namaFile = Str::slug($lampiran->persyaratan->nama_persyaratan ?? 'dokumen') . '.' . $ext;

        return Storage::disk('public')->download($lampiran->file_path, $namaFile);
    }

    /**
     * Unduh dokumen hasil dari seksi dengan nama file yang mudah dibaca.
     */
    public function downloadLampiranHasil(LampiranHasil $lampiran)
    {
        $this->authorizeAccessPermohonan($lampiran->permohonan);

        $ext = pathinfo($lampiran->file_path, PATHINFO_EXTENSION);
        $namaFile = Str::slug($lampiran->nama_dokumen ?? 'dokumen-hasil') . '.' . $ext;

        return Storage::disk('public')->download($lampiran->file_path, $namaFile);
    }

    // ==========================================================
    // Guest & walk-in
    // ==========================================================

    public function guestBiodata()
    {
        return view('guest.biodata');
    }

    public function storeBiodata(Request $request)
    {
        $request->validate([
            'nama' => 'required|string|max:255',
            'alamat' => 'required|string',
            // TIDAK unique: satu NIK yang sama wajar dipakai untuk banyak
            // permohonan berbeda sepanjang waktu (mis. warga yang sama
            // mengajukan 2 layanan berbeda di hari/bulan berbeda). Rule
            // unique di sini sebelumnya memblokir NIK yang sama untuk
            // pengajuan kedua dst — sudah dihapus bersamaan dengan
            // constraint unique di kolom DB-nya.
            'nik' => 'required|string|max:16',
            'no_hp' => 'required|string|max:15',
            'ktp' => 'required|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $ktpPath = $request->file('ktp')->store('ktp_guest', 'public');

        session([
            'guest_biodata' => [
                'nama' => $request->nama,
                'alamat' => $request->alamat,
                'nik' => $request->nik,
                'no_hp' => $request->no_hp,
                'ktp_path' => $ktpPath,
            ]
        ]);

        return redirect()->route('guest.permohonan.create');
    }

    public function guestCreate()
    {
        if (!session()->has('guest_biodata')) {
            return redirect()->route('guest.permohonan.biodata');
        }

        $layanans = Layanan::with('persyaratan', 'seksi')->get();
        $persyaratanByLayanan = $layanans->mapWithKeys(function ($layanan) {
            return [$layanan->id => $layanan->persyaratan];
        });

        return view('guest.permohonan.create', compact('layanans', 'persyaratanByLayanan'));
    }

    public function guestStore(Request $request)
    {
        if (!session()->has('guest_biodata')) {
            return redirect()->route('guest.permohonan.biodata');
        }

        $request->validate([
            'layanan_id' => 'required|exists:layanan,id',
            'deskripsi' => 'nullable|string',
            'lampiran.*' => 'nullable|file|mimes:pdf,jpg,jpeg,png|max:2048',
        ]);

        $persyaratans = Persyaratan::where('layanan_id', $request->layanan_id)->get();

        foreach ($persyaratans as $index => $persyaratan) {
            if (!$request->hasFile('lampiran.' . $index)) {
                return back()->withErrors(['lampiran.' . $index => 'Lampiran untuk ' . $persyaratan->nama_persyaratan . ' diperlukan.']);
            }
        }

        $biodata = session('guest_biodata');

        $permohonan = Permohonan::create([
            'user_id' => null,
            'layanan_id' => $request->layanan_id,
            'tanggal_pengajuan' => now(),
            'status' => 'diajukan',
            'sumber_pengajuan' => 'online',
            'no_tiket' => Permohonan::generateNoTiket(),
            'nama' => $biodata['nama'],
            'alamat' => $biodata['alamat'],
            'nik' => $biodata['nik'],
            'no_hp' => $biodata['no_hp'],
            'ktp_path' => $biodata['ktp_path'],
            'deskripsi' => $request->deskripsi,
        ]);

        $this->simpanLampiranPersyaratan($request, $permohonan);
        $this->generateQrCode($permohonan);
        $permohonan->catatPerubahanStatus('diajukan', null, 'Permohonan diajukan oleh pemohon (tanpa akun).');

        $permohonan->load('lampiranPermohonan.persyaratan');
        $this->generateBuktiPdf($permohonan);

        session()->forget('guest_biodata');
        session(['submitted_ticket' => $permohonan->no_tiket, 'submitted_permohonan_id' => $permohonan->id]);

        return redirect()->route('guest.searchTicket');
    }

    public function searchTicket()
    {
        return view('guest.search_ticket');
    }

    public function showTicket(Request $request)
    {
        $request->validate(['no_tiket' => 'required|string']);

        $permohonan = Permohonan::where('no_tiket', $request->no_tiket)
            ->with('layanan.seksi', 'currentSeksi', 'riwayatStatus', 'surveiRespon')
            ->first();

        if (!$permohonan) {
            return back()->withErrors(['no_tiket' => 'Nomor tiket tidak ditemukan.']);
        }

        return view('guest.show_ticket', compact('permohonan'));
    }

    /**
     * Dituju oleh link/QR code di bukti pengajuan — supaya pemohon tinggal
     * scan tanpa perlu ketik manual nomor tiket.
     */
    public function trackTicket(string $no_tiket)
    {
        $permohonan = Permohonan::where('no_tiket', $no_tiket)
            ->with('layanan.seksi', 'currentSeksi', 'riwayatStatus', 'surveiRespon')
            ->first();

        if (!$permohonan) {
            return redirect()->route('guest.searchTicket')->withErrors(['no_tiket' => 'Nomor tiket tidak ditemukan.']);
        }

        return view('guest.show_ticket', compact('permohonan'));
    }

    // ==========================================================
    // Helper internal
    // ==========================================================

    private function simpanLampiranPersyaratan(Request $request, Permohonan $permohonan): void
    {
        $persyaratans = Persyaratan::where('layanan_id', $permohonan->layanan_id)->get();

        foreach ($persyaratans as $index => $persyaratan) {
            if ($request->hasFile('lampiran.' . $index)) {
                $file = $request->file('lampiran.' . $index);
                $path = $file->store('lampiran', 'public');
                LampiranPermohonan::create([
                    'permohonan_id' => $permohonan->id,
                    'persyaratan_id' => $persyaratan->id,
                    'file_path' => $path,
                    'tanggal_unggah' => now(),
                ]);
            }
        }
    }

    private function generateQrCode(Permohonan $permohonan): void
    {
        Storage::disk('public')->makeDirectory('qrcode');

        // Pakai format SVG (bukan PNG) supaya TIDAK butuh ekstensi PHP
        // Imagick yang sering tidak terpasang default di Windows/Laragon.
        $path = 'qrcode/' . $permohonan->no_tiket . '.svg';
        $url = route('guest.trackTicket', $permohonan->no_tiket);

        $svg = QrCode::format('svg')->size(300)->generate($url);
        Storage::disk('public')->put($path, $svg);

        $permohonan->update(['qr_code_path' => $path]);
    }

    private function generateBuktiPdf(Permohonan $permohonan): void
    {
        $pdf = Pdf::loadView('pemohon.permohonan.pdf', compact('permohonan'))->setPaper([0, 0, 252, 432], 'portrait');
        Storage::disk('public')->put('permohonan_pdf/' . $permohonan->no_tiket . '.pdf', $pdf->output());
    }
}
