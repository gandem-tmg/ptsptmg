<?php

use App\Http\Controllers\DashboardController;
use App\Http\Controllers\LayananController;
use App\Http\Controllers\PermohonanController;
use App\Http\Controllers\PersyaratanController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Guest permohonan routes
Route::get('/permohonan/biodata', [PermohonanController::class, 'guestBiodata'])->name('guest.permohonan.biodata');
Route::post('/permohonan/biodata', [PermohonanController::class, 'storeBiodata'])->name('guest.permohonan.storeBiodata');
Route::get('/permohonan/create', [PermohonanController::class, 'guestCreate'])->name('guest.permohonan.create');
Route::post('/permohonan', [PermohonanController::class, 'guestStore'])->name('guest.permohonan.store');
Route::get('/permohonan/{permohonan}/pdf', [PermohonanController::class, 'downloadPdf'])->name('guest.permohonan.pdf');
Route::get('/cari-tiket', [PermohonanController::class, 'searchTicket'])->name('guest.searchTicket');
Route::post('/cari-tiket', [PermohonanController::class, 'showTicket'])->name('guest.showTicket');

Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::middleware(['auth'])->group(function () {
    // Admin routes
    Route::middleware(['role:admin'])->prefix('admin')->name('admin.')->group(function () {
        Route::resource('users', UserController::class);
        Route::resource('layanan', LayananController::class);
        Route::resource('persyaratan', PersyaratanController::class);
        Route::resource('permohonan', PermohonanController::class)->only(['index', 'show', 'edit', 'update', 'destroy']);
    });

    // Petugas routes
    Route::middleware(['role:petugas'])->prefix('petugas')->name('petugas.')->group(function () {
        Route::resource('layanan', LayananController::class)->only(['index', 'show']);
        Route::resource('persyaratan', PersyaratanController::class)->only(['index', 'show']);
        Route::get('permohonan', [PermohonanController::class, 'index'])->name('permohonan.index');
        Route::get('permohonan/{permohonan}', [PermohonanController::class, 'show'])->name('permohonan.show');
        Route::patch('permohonan/{permohonan}/status', [PermohonanController::class, 'updateStatus'])->name('permohonan.updateStatus');
        Route::delete('permohonan/{permohonan}', [PermohonanController::class, 'destroy'])->name('permohonan.destroy');
    });

    // Pemohon routes
    Route::middleware(['role:pemohon'])->prefix('pemohon')->name('pemohon.')->group(function () {
        Route::get('permohonan', [PermohonanController::class, 'index'])->name('permohonan.index');
        Route::get('permohonan/create', [PermohonanController::class, 'create'])->name('permohonan.create');
        Route::post('permohonan', [PermohonanController::class, 'store'])->name('permohonan.store');
        Route::get('permohonan/{permohonan}', [PermohonanController::class, 'show'])->name('permohonan.show');
        Route::get('permohonan/{permohonan}/pdf', [PermohonanController::class, 'downloadPdf'])->name('permohonan.pdf');
    });

    // Shared routes
    Route::get('dashboard', [DashboardController::class, 'index'])->name('dashboard');
});

require __DIR__.'/auth.php';
