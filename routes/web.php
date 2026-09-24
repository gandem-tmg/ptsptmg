<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\LayananPublicController;
use App\Http\Controllers\PermohonanController;
use App\Http\Controllers\PersyaratanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\Auth\GoogleController;
use Illuminate\Support\Facades\Route;

Route::get('/', [LayananPublicController::class, 'index'])->name('home');

// Browse layanan publik — bisa dilihat siapa saja tanpa login.
Route::get('/layanan', [LayananPublicController::class, 'index'])->name('layanan.index');
Route::get('/layanan/{layanan}', [LayananPublicController::class, 'show'])->name('layanan.show');

// Baru diminta login di titik ini — begitu login, otomatis diarahkan
// balik ke form pengajuan dengan layanan yang sudah terpilih
// (memanfaatkan intended-redirect bawaan middleware 'auth' Laravel).
Route::get('/layanan/{layanan}/ajukan', function (\App\Models\Layanan $layanan) {
    return redirect()->route('pemohon.permohonan.create', ['layanan_id' => $layanan->id]);
})->middleware('auth')->name('layanan.ajukan');

// Guest permohonan routes (tanpa login — pemohon bisa browse & ajukan tanpa akun)
Route::get('/permohonan/biodata', [PermohonanController::class, 'guestBiodata'])->name('guest.permohonan.biodata');
Route::post('/permohonan/biodata', [PermohonanController::class, 'storeBiodata'])->name('guest.permohonan.storeBiodata');
Route::get('/permohonan/create', [PermohonanController::class, 'guestCreate'])->name('guest.permohonan.create');
Route::post('/permohonan', [PermohonanController::class, 'guestStore'])->name('guest.permohonan.store');
// Diakses lewat no_tiket (BUKAN ID numerik) — route publik ini sengaja tidak
// pakai {permohonan} (route model binding by ID) karena ID auto-increment
// gampang ditebak/diurutkan, artinya siapa saja bisa download bukti
// pengajuan (berisi NIK, alamat, no HP) milik orang lain tinggal ganti
// angka di URL. no_tiket sudah didesain acak (Permohonan::generateNoTiket())
// justru untuk mencegah pola ini, sama seperti trackTicket() di bawah.
Route::get('/tiket/{no_tiket}/pdf', [PermohonanController::class, 'downloadPdfGuest'])->name('guest.permohonan.pdf')->middleware('throttle:30,1');

// Lacak status via no_tiket itu publik & tanpa login by design (memang
// tujuannya bisa dicek siapa saja yang pegang tiketnya) — tapi throttle
// supaya nomor tiket yang sekuensial (YYMMDD-001, -002, dst, sengaja
// dibuat pendek & gampang diucapkan) tidak gampang di-scan massal buat
// mengintip data pemohon lain.
Route::get('/status-permohonan', [PermohonanController::class, 'searchTicket'])->name('guest.searchTicket');
Route::post('/status-permohonan', [PermohonanController::class, 'showTicket'])->name('guest.showTicket')->middleware('throttle:20,1');

// Tujuan link/QR code di bukti pengajuan — scan langsung tanpa ketik nomor tiket.
Route::get('/tiket/{no_tiket}', [PermohonanController::class, 'trackTicket'])->name('guest.trackTicket')->middleware('throttle:30,1');

// Survei per-tiket: khusus permohonan offline/walk-in (tidak punya akun),
// diisi dari halaman lacak tiket di atas begitu status 'selesai'.
Route::get('/tiket/{no_tiket}/survei', [\App\Http\Controllers\SurveiController::class, 'formPerTiket'])->name('guest.survei.perTiket')->middleware('throttle:20,1');
Route::post('/tiket/{no_tiket}/survei', [\App\Http\Controllers\SurveiController::class, 'submitPerTiket'])->name('guest.survei.perTiket.store')->middleware('throttle:10,1');

// Dashboard transparansi publik — agregat saja, tanpa login, tanpa data pribadi.
Route::get('/transparansi', [\App\Http\Controllers\TransparansiController::class, 'index'])->name('transparansi.index');

// Survei kepuasan masyarakat (umum) — siapa saja, login ataupun tidak.
// Throttle di POST-nya supaya tidak gampang di-spam script buat
// nge-skew data IKM/SKM resmi.
Route::get('/survei-kepuasan', [\App\Http\Controllers\SurveiController::class, 'formUmum'])->name('survei.umum.create');
Route::post('/survei-kepuasan', [\App\Http\Controllers\SurveiController::class, 'submitUmum'])->name('survei.umum.store')->middleware('throttle:10,1');

// Verifikasi keaslian surat via QR code — publik, tanpa login.
Route::get('/verifikasi-surat/{kode}', [\App\Http\Controllers\VerifikasiSuratController::class, 'show'])->name('verifikasi.surat.show');

// Socialite Google login routes
Route::get('auth/google/redirect', [GoogleController::class, 'redirect'])->name('auth.google.redirect');
Route::get('auth/google/callback', [GoogleController::class, 'callback'])->name('auth.google.callback');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    // Statistics route (accessible by authenticated users; role & scope
    // per seksi untuk petugas_seksi dicek/dikunci di StatisticsController)
    Route::get('/statistics', [App\Http\Controllers\StatisticsController::class, 'index'])->name('statistics.index');
    Route::get('/statistics/export', [App\Http\Controllers\StatisticsController::class, 'export'])->name('statistics.export');

    // Hasil SKM (admin/petugas/pimpinan) — otorisasi role dicek di controller,
    // sama seperti pola statistics di atas.
    Route::get('/survei/hasil', [App\Http\Controllers\SurveiHasilController::class, 'index'])->name('survei.hasil');
    Route::get('/survei/hasil/export', [App\Http\Controllers\SurveiHasilController::class, 'export'])->name('survei.hasil.export');

    // Download lampiran dengan nama file yang rapi — dipakai lintas role,
    // otorisasi dicek di controller berdasarkan akses ke permohonan terkait.
    Route::get('lampiran/{lampiran}/download', [PermohonanController::class, 'downloadLampiran'])->name('lampiran.download');
    Route::get('lampiran-hasil/{lampiran}/download', [PermohonanController::class, 'downloadLampiranHasil'])->name('lampiranHasil.download');

    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('layanan', LayananController::class);
        Route::resource('persyaratan', PersyaratanController::class);
        Route::resource('permohonan', PermohonanController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
        Route::resource('template-surat', \App\Http\Controllers\TemplateSuratController::class)->except(['show']);
        Route::resource('survei-pertanyaan', \App\Http\Controllers\SurveiPertanyaanController::class)->except(['show']);
    });

    // Petugas PTSP routes — loket, verifikasi awal, disposisi ke seksi, verifikasi akhir
    Route::middleware(['role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
        Route::resource('layanan', LayananController::class)->only(['index', 'show']);
        Route::resource('persyaratan', PersyaratanController::class)->only(['index', 'show']);

        Route::get('permohonan', [PermohonanController::class, 'index'])->name('permohonan.index');
        // Daftar Permohonan — tabel berisi SEMUA data permohonan lintas seksi,
        // terpisah dari "Permohonan Terbaru" (card view) di atas supaya
        // petugas punya tampilan ringkas untuk memantau antrean dan tampilan
        // tabel untuk menyisir/mencari data secara menyeluruh.
        Route::get('permohonan-daftar', [PermohonanController::class, 'daftarPetugas'])->name('permohonan.daftar');
        Route::get('permohonan/{permohonan}', [PermohonanController::class, 'show'])->name('permohonan.show');
        Route::get('permohonan/{permohonan}/pdf', [PermohonanController::class, 'downloadPdf'])->name('permohonan.pdf');

        // Alur disposisi
        Route::patch('permohonan/{permohonan}/disposisikan', [PermohonanController::class, 'disposisikan'])->name('permohonan.disposisikan');
        Route::patch('permohonan/{permohonan}/kembalikan', [PermohonanController::class, 'kembalikan'])->name('permohonan.kembalikan');
        Route::patch('permohonan/{permohonan}/verifikasi-akhir', [PermohonanController::class, 'verifikasiAkhir'])->name('permohonan.verifikasiAkhir');
        Route::patch('permohonan/{permohonan}/update-manual', [PermohonanController::class, 'updateManualPtsp'])->name('permohonan.updateManual');

        // Input permohonan untuk pemohon walk-in (datang langsung ke loket)
        // Permohonan Offline — buatkan tiket cepat untuk pemohon yang datang
        // langsung ke loket (pengganti alur walk-in lama yang terlalu detail).
        Route::get('permohonan-offline', [\App\Http\Controllers\PermohonanOfflineController::class, 'index'])->name('permohonan.offline.index');
        Route::get('permohonan-offline/{layanan}/create', [\App\Http\Controllers\PermohonanOfflineController::class, 'create'])->name('permohonan.offline.create');
        Route::post('permohonan-offline', [\App\Http\Controllers\PermohonanOfflineController::class, 'store'])->name('permohonan.offline.store');

        // Permohonan Offline untuk layanan yang TIDAK ADA di daftar layanan
        // resmi seksi (tapi masih tupoksi Kemenag) — nama & deskripsi
        // layanan diisi manual oleh petugas, bukan dipilih dari katalog.
        Route::get('permohonan-offline/manual/{seksi}/create', [\App\Http\Controllers\PermohonanOfflineController::class, 'manualCreate'])->name('permohonan.offline.manual.create');
        Route::post('permohonan-offline/manual', [\App\Http\Controllers\PermohonanOfflineController::class, 'manualStore'])->name('permohonan.offline.manual.store');
    });

    // Petugas Seksi routes — tindak lanjut permohonan yang didisposisikan ke seksinya
    Route::middleware(['role:petugas_seksi'])->prefix('seksi')->name('seksi.')->group(function () {
        // Layanan seksi sendiri — petugas seksi mengelola konten layanan
        // (deskripsi, persyaratan, standar pelayanan) untuk layanan yang
        // seksi_id-nya seksi ini. Otorisasi per-layanan dicek di controller.
        Route::resource('layanan', \App\Http\Controllers\SeksiLayananController::class)->only(['index', 'show', 'edit', 'update']);

        Route::get('permohonan', [PermohonanController::class, 'index'])->name('permohonan.index');
        Route::get('permohonan/{permohonan}', [PermohonanController::class, 'show'])->name('permohonan.show');
        Route::get('permohonan/{permohonan}/pdf', [PermohonanController::class, 'downloadPdf'])->name('permohonan.pdf');

        Route::patch('permohonan/{permohonan}/terima', [PermohonanController::class, 'terimaDisposisi'])->name('permohonan.terima');
        Route::patch('permohonan/{permohonan}/progres', [PermohonanController::class, 'updateProgresSeksi'])->name('permohonan.progres');
        Route::post('permohonan/{permohonan}/selesai', [PermohonanController::class, 'selesaikanSeksi'])->name('permohonan.selesai');

        // Generate & tandatangani surat
        Route::get('permohonan/{permohonan}/surat/create', [\App\Http\Controllers\SuratController::class, 'create'])->name('surat.create');
        Route::post('permohonan/{permohonan}/surat', [\App\Http\Controllers\SuratController::class, 'store'])->name('surat.store');
        Route::get('surat/{surat}', [\App\Http\Controllers\SuratController::class, 'show'])->name('surat.show');
        Route::get('surat/{surat}/download', [\App\Http\Controllers\SuratController::class, 'download'])->name('surat.download');
        Route::post('surat/{surat}/tandatangan-manual', [\App\Http\Controllers\SuratController::class, 'tandatanganManual'])->name('surat.tandatanganManual');
        Route::post('surat/{surat}/tandatangan-tte', [\App\Http\Controllers\SuratController::class, 'tandatanganTte'])->name('surat.tandatanganTte');
    });

    // Pimpinan routes — read-only, monitoring & penelusuran lintas seksi
    Route::middleware(['role:pimpinan'])->prefix('pimpinan')->name('pimpinan.')->group(function () {
        Route::get('monitoring', [PermohonanController::class, 'monitoring'])->name('monitoring.index');
        Route::get('permohonan', [PermohonanController::class, 'index'])->name('permohonan.index');
        Route::get('permohonan/{permohonan}', [PermohonanController::class, 'show'])->name('permohonan.show');
    });

    // Pemohon routes
    Route::middleware(['role:pemohon'])->prefix('pemohon')->name('pemohon.')->group(function () {
        Route::get('permohonan', [PermohonanController::class, 'index'])->name('permohonan.index');
        Route::get('permohonan/create', [PermohonanController::class, 'create'])->name('permohonan.create');
        Route::post('permohonan', [PermohonanController::class, 'store'])->name('permohonan.store');
        Route::get('permohonan/{permohonan}', [PermohonanController::class, 'show'])->name('permohonan.show');
        Route::get('permohonan/{permohonan}/pdf', [PermohonanController::class, 'downloadPdf'])->name('permohonan.pdf');
        Route::delete('permohonan/{permohonan}/batalkan', [PermohonanController::class, 'batalkan'])->name('permohonan.batalkan');
        Route::get('permohonan/{permohonan}/survei', [\App\Http\Controllers\SurveiController::class, 'formPerLayanan'])->name('permohonan.survei.create');
        Route::post('permohonan/{permohonan}/survei', [\App\Http\Controllers\SurveiController::class, 'submitPerLayanan'])->name('permohonan.survei.store');
        Route::get('survei', [\App\Http\Controllers\SurveiController::class, 'daftarPemohon'])->name('survei.index');
        Route::get('dokumen', [PermohonanController::class, 'dokumenSaya'])->name('dokumen.index');
    });

    // Shared routes
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
