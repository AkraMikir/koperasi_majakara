<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Nasabah\DashboardController as NasabahDashboardController;
use App\Http\Controllers\Nasabah\TabunganController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Landing Page Routes
Route::get('/layanan', [\App\Http\Controllers\LandingPageController::class, 'layanan'])->name('landing.layanan');
Route::get('/keuntungan', [\App\Http\Controllers\LandingPageController::class, 'keuntungan'])->name('landing.keuntungan');
Route::get('/testimoni', [\App\Http\Controllers\LandingPageController::class, 'testimoni'])->name('landing.testimoni');
Route::get('/faq', [\App\Http\Controllers\LandingPageController::class, 'faq'])->name('landing.faq');

// Authentication Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
Route::post('/register/ocr', [RegisterController::class, 'processOcr'])->name('register.ocr');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/login/verify-pin', [LoginController::class, 'verifyPin'])->name('login.verify-pin');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Nasabah Routes (Protected with Auth Middleware)
Route::prefix('nasabah')->middleware('auth')->name('nasabah.')->group(function () {
    Route::get('/dashboard', [NasabahDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [NasabahDashboardController::class, 'profile'])->name('profile');
    Route::get('/pengajuan-pending', [NasabahDashboardController::class, 'pengajuanPending'])->name('pengajuan-pending');
    
    // Tabungan Routes
    Route::prefix('tabungan')->name('tabungan.')->group(function () {
        Route::get('/', [TabunganController::class, 'index'])->name('index');
        Route::get('/nabung-sekarang', [TabunganController::class, 'nabungSekarang'])->name('nabung-sekarang');
        Route::get('/pengajuan-transfer', [TabunganController::class, 'pengajuanTransfer'])->name('pengajuan-transfer');
        Route::post('/pengajuan-transfer', [TabunganController::class, 'submitSetoran'])->name('submit-setoran');
        Route::get('/penarikan', [TabunganController::class, 'penarikanTabungan'])->name('penarikan');
        Route::post('/penarikan', [TabunganController::class, 'submitPenarikan'])->name('submit-penarikan');
        Route::get('/janji-temu', [TabunganController::class, 'janjiTemu'])->name('janji-temu');
        Route::post('/janji-temu', [TabunganController::class, 'submitJanjiTemu'])->name('submit-janji-temu');
        Route::post('/verify-pin', [TabunganController::class, 'verifyPin'])->name('verify-pin');
        Route::get('/status-pengajuan-setor', [TabunganController::class, 'statusPengajuanSetor'])->name('status-pengajuan-setor');
        Route::get('/status-pengajuan-tarik', [TabunganController::class, 'statusPengajuanTarik'])->name('status-pengajuan-tarik');
        Route::get('/pengajuan-setor/{id}', [TabunganController::class, 'detailPengajuanSetor'])->name('detail-pengajuan-setor');
        Route::get('/pengajuan-tarik/{id}', [TabunganController::class, 'detailPengajuanTarik'])->name('detail-pengajuan-tarik');
        Route::get('/transaksi/{id}', [TabunganController::class, 'detailTransaksi'])->name('detail-transaksi');
        Route::get('/janji-temu/{id}', [TabunganController::class, 'detailJanjiTemu'])->name('detail-janji-temu');
    });
    
    // Pinjaman Routes
    Route::prefix('pinjaman')->name('pinjaman.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'index'])->name('index');
        Route::get('/pengajuan', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'pengajuanPinjaman'])->name('pengajuan');
        Route::post('/pengajuan', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'submitPengajuan'])->name('submit-pengajuan');
        Route::post('/verify-pin', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'verifyPin'])->name('verify-pin');
        Route::get('/status-pengajuan', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'statusPengajuan'])->name('status-pengajuan');
        Route::get('/pengajuan/{id}', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'detailPengajuan'])->name('detail-pengajuan');
        Route::get('/pinjaman-aktif', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'pinjamanAktif'])->name('pinjaman-aktif');
        Route::get('/pinjaman-aktif/{id}', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'detailPinjaman'])->name('detail-pinjaman');
        Route::get('/angsuran', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'angsuran'])->name('angsuran');
        Route::get('/angsuran/{id}', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'detailAngsuran'])->name('detail-angsuran');
        Route::get('/pembayaran', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'pembayaran'])->name('pembayaran');
        Route::post('/pembayaran/transfer', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'submitPembayaranTransfer'])->name('submit-pembayaran-transfer');
        Route::post('/pembayaran/janji-temu', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'submitJanjiTemuPembayaran'])->name('submit-janji-temu-pembayaran');
        Route::get('/status-pembayaran', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'statusPembayaran'])->name('status-pembayaran');
        Route::get('/pembayaran/{id}', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'detailPembayaran'])->name('detail-pembayaran');
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
        Route::post('/pengajuan-setor/{id}/edit', [\App\Http\Controllers\Admin\TabunganController::class, 'editPengajuanSetor'])->name('edit-pengajuan-setor');
        Route::delete('/pengajuan-setor/{id}', [\App\Http\Controllers\Admin\TabunganController::class, 'deletePengajuanSetor'])->name('delete-pengajuan-setor');
        Route::get('/pengajuan-tarik', [\App\Http\Controllers\Admin\TabunganController::class, 'pengajuanTarik'])->name('pengajuan-tarik');
        Route::get('/pengajuan-tarik/{id}', [\App\Http\Controllers\Admin\TabunganController::class, 'detailPengajuanTarik'])->name('detail-pengajuan-tarik');
        Route::post('/pengajuan-tarik/{id}/approve', [\App\Http\Controllers\Admin\TabunganController::class, 'approveTarik'])->name('approve-tarik');
        Route::post('/pengajuan-tarik/{id}/reject', [\App\Http\Controllers\Admin\TabunganController::class, 'rejectTarik'])->name('reject-tarik');
        Route::get('/transaksi', [\App\Http\Controllers\Admin\TabunganController::class, 'transaksi'])->name('transaksi');
        Route::get('/transaksi/{id}', [\App\Http\Controllers\Admin\TabunganController::class, 'detailTransaksi'])->name('detail-transaksi');
        Route::get('/janji-temu', [\App\Http\Controllers\Admin\TabunganController::class, 'janjiTemu'])->name('janji-temu');
        Route::get('/janji-temu/{id}', [\App\Http\Controllers\Admin\TabunganController::class, 'detailJanjiTemu'])->name('detail-janji-temu');
        Route::post('/janji-temu/{id}/create-trans', [\App\Http\Controllers\Admin\TabunganController::class, 'createTransFromJanjiTemu'])->name('create-trans-from-janji-temu');
        Route::get('/saldo-nasabah', [\App\Http\Controllers\Admin\TabunganController::class, 'saldoNasabah'])->name('saldo-nasabah');
    });
    
    // Pinjaman Routes
    Route::prefix('pinjaman')->name('pinjaman.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\PinjamanController::class, 'index'])->name('index');
        Route::get('/pengajuan', [\App\Http\Controllers\Admin\PinjamanController::class, 'pengajuan'])->name('pengajuan');
        Route::get('/pengajuan/{id}', [\App\Http\Controllers\Admin\PinjamanController::class, 'detailPengajuan'])->name('detail-pengajuan');
        Route::post('/pengajuan/{id}/approve', [\App\Http\Controllers\Admin\PinjamanController::class, 'approvePengajuan'])->name('approve-pengajuan');
        Route::post('/pengajuan/{id}/reject', [\App\Http\Controllers\Admin\PinjamanController::class, 'rejectPengajuan'])->name('reject-pengajuan');
        Route::get('/pinjaman-aktif', [\App\Http\Controllers\Admin\PinjamanController::class, 'pinjamanAktif'])->name('pinjaman-aktif');
        Route::get('/pinjaman-aktif/{id}', [\App\Http\Controllers\Admin\PinjamanController::class, 'detailPinjaman'])->name('detail-pinjaman');
        Route::get('/angsuran', [\App\Http\Controllers\Admin\PinjamanController::class, 'angsuran'])->name('angsuran');
        Route::get('/angsuran/{id}', [\App\Http\Controllers\Admin\PinjamanController::class, 'detailAngsuran'])->name('detail-angsuran');
        Route::post('/angsuran/{id}/bayar', [\App\Http\Controllers\Admin\PinjamanController::class, 'updatePembayaranAngsuran'])->name('update-pembayaran-angsuran');
        Route::post('/pinjaman-aktif/{id}/pelunasan-dipercepat', [\App\Http\Controllers\Admin\PinjamanController::class, 'pelunasanDipercepat'])->name('pelunasan-dipercepat');
        Route::get('/pembayaran', [\App\Http\Controllers\Admin\PinjamanController::class, 'pembayaran'])->name('pembayaran');
        Route::get('/pembayaran/{id}', [\App\Http\Controllers\Admin\PinjamanController::class, 'detailPembayaran'])->name('detail-pembayaran');
        Route::post('/pembayaran/{id}/approve', [\App\Http\Controllers\Admin\PinjamanController::class, 'approvePembayaran'])->name('approve-pembayaran');
        Route::post('/pembayaran/{id}/reject', [\App\Http\Controllers\Admin\PinjamanController::class, 'rejectPembayaran'])->name('reject-pembayaran');
        Route::post('/pembayaran/{id}/konfirmasi', [\App\Http\Controllers\Admin\PinjamanController::class, 'konfirmasiPembayaran'])->name('konfirmasi-pembayaran');
        Route::post('/pembayaran/{id}/upload-serah-terima', [\App\Http\Controllers\Admin\PinjamanController::class, 'uploadSerahTerima'])->name('upload-serah-terima');
    });
    Route::get('/deposito', function () { return view('admin.deposito.index'); })->name('deposito.index');
    Route::get('/gadai', function () { return view('admin.gadai.index'); })->name('gadai.index');
    Route::get('/nasabah', function () { return view('admin.nasabah.index'); })->name('nasabah.index');
    Route::get('/pengajuan', function () { return view('admin.pengajuan.index'); })->name('pengajuan.index');
});
