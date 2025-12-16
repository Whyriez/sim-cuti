<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\PublicCutiController;

// 1. Halaman Depan (Landing Page + Modal Login)
Route::get('/', [PublicCutiController::class, 'landing'])->name('landing');

// 2. Halaman Form Pengajuan (Saat klik 'Ajukan Cuti')
Route::get('/buat-pengajuan', [PublicCutiController::class, 'index'])->name('pengajuan.form');
Route::get('/api/pegawai/{nip}', [PublicCutiController::class, 'checkNip']);
Route::post('/pengajuan/store', [PublicCutiController::class, 'store'])->name('pengajuan.store');

// 3. Proses Login (Dari Modal)
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// 4. Halaman Admin (Dashboard)
Route::middleware(['auth'])->group(function () {

    Route::get('/dashboard', [AdminController::class, 'index'])->name('dashboard');

    // Import
    Route::post('/pegawai/import', [AdminController::class, 'importPegawai'])->name('pegawai.import');

    // Approval
    Route::post('/pengajuan/{id}/update', [AdminController::class, 'updateStatus'])->name('pengajuan.update');
    Route::get('/pengajuan/{id}/cetak', [AdminController::class, 'cetakPDF'])->name('pengajuan.cetak');

    // -- MENU BARU --
    // Unit Kerja
    Route::get('/unit-kerja', [AdminController::class, 'indexUnitKerja'])->name('unit-kerja.index');
    Route::post('/unit-kerja/{id}/update', [AdminController::class, 'updateUnitKerja'])->name('unit-kerja.update');

    // CRUD Pegawai
    Route::get('/data-pegawai', [AdminController::class, 'indexPegawai'])->name('pegawai.index');
    Route::post('/data-pegawai/store', [AdminController::class, 'storePegawai'])->name('pegawai.store');
    Route::put('/data-pegawai/{id}', [AdminController::class, 'updatePegawai'])->name('pegawai.update');
    Route::delete('/data-pegawai/{id}', [AdminController::class, 'destroyPegawai'])->name('pegawai.destroy');

    // Settings Instansi (Bisa ditambahkan nanti)
    Route::get('/settings', [AdminController::class, 'indexSettings'])->name('settings.index');
    Route::post('/settings', [AdminController::class, 'updateSettings'])->name('settings.update');
});
