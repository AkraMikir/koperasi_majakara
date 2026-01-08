<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Nasabah\DashboardController as NasabahDashboardController;
use App\Http\Controllers\Nasabah\TabunganController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

Route::get('/', function () {
    return view('welcome');
});

// Authentication Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Nasabah Routes (Frontend Only - No Auth Required)
Route::prefix('nasabah')->name('nasabah.')->group(function () {
    Route::get('/dashboard', [NasabahDashboardController::class, 'index'])->name('dashboard');
    
    // Tabungan Routes
    Route::prefix('tabungan')->name('tabungan.')->group(function () {
        Route::get('/', [TabunganController::class, 'index'])->name('index');
        Route::get('/nabung-sekarang', [TabunganController::class, 'nabungSekarang'])->name('nabung-sekarang');
        Route::get('/penarikan', [TabunganController::class, 'penarikanTabungan'])->name('penarikan');
    });
});

// Admin Routes
Route::prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');
    
    // Tabungan Routes
    Route::prefix('tabungan')->name('tabungan.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\TabunganController::class, 'index'])->name('index');
        Route::get('/pengajuan-setor', [\App\Http\Controllers\Admin\TabunganController::class, 'pengajuanSetor'])->name('pengajuan-setor');
        Route::get('/pengajuan-setor/{id}', [\App\Http\Controllers\Admin\TabunganController::class, 'detailPengajuanSetor'])->name('detail-pengajuan-setor');
        Route::post('/pengajuan-setor/{id}/approve', [\App\Http\Controllers\Admin\TabunganController::class, 'approveSetor'])->name('approve-setor');
        Route::post('/pengajuan-setor/{id}/reject', [\App\Http\Controllers\Admin\TabunganController::class, 'rejectSetor'])->name('reject-setor');
        Route::get('/pengajuan-tarik', [\App\Http\Controllers\Admin\TabunganController::class, 'pengajuanTarik'])->name('pengajuan-tarik');
        Route::get('/pengajuan-tarik/{id}', [\App\Http\Controllers\Admin\TabunganController::class, 'detailPengajuanTarik'])->name('detail-pengajuan-tarik');
        Route::post('/pengajuan-tarik/{id}/approve', [\App\Http\Controllers\Admin\TabunganController::class, 'approveTarik'])->name('approve-tarik');
        Route::post('/pengajuan-tarik/{id}/reject', [\App\Http\Controllers\Admin\TabunganController::class, 'rejectTarik'])->name('reject-tarik');
        Route::get('/transaksi', [\App\Http\Controllers\Admin\TabunganController::class, 'transaksi'])->name('transaksi');
        Route::get('/janji-temu', [\App\Http\Controllers\Admin\TabunganController::class, 'janjiTemu'])->name('janji-temu');
    });
    
    // Placeholder routes untuk menu lainnya
    Route::get('/pinjaman', function () { return view('admin.pinjaman.index'); })->name('pinjaman.index');
    Route::get('/deposito', function () { return view('admin.deposito.index'); })->name('deposito.index');
    Route::get('/gadai', function () { return view('admin.gadai.index'); })->name('gadai.index');
    Route::get('/nasabah', function () { return view('admin.nasabah.index'); })->name('nasabah.index');
    Route::get('/pengajuan', function () { return view('admin.pengajuan.index'); })->name('pengajuan.index');
});
