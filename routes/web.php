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
        Route::get('/pengajuan-transfer', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'pengajuanTransfer'])->name('pengajuan-transfer');
        Route::post('/pengajuan-transfer', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'submitPengajuanTransfer'])->name('submit-pengajuan-transfer');
        Route::post('/simulasi-angsuran', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'simulasiAngsuran'])->name('simulasi-angsuran');
        Route::get('/janji-temu', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'janjiTemuPinjaman'])->name('janji-temu');
        Route::post('/janji-temu', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'submitJanjiTemuPinjaman'])->name('submit-janji-temu');
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
        Route::get('/transaksi/create', [\App\Http\Controllers\Admin\TabunganController::class, 'createTransaksi'])->name('create-transaksi');
        Route::post('/transaksi', [\App\Http\Controllers\Admin\TabunganController::class, 'storeTransaksi'])->name('store-transaksi');
        Route::get('/transaksi/{id}', [\App\Http\Controllers\Admin\TabunganController::class, 'detailTransaksi'])->name('detail-transaksi');
        Route::get('/transaksi/{id}/edit', [\App\Http\Controllers\Admin\TabunganController::class, 'editTransaksi'])->name('edit-transaksi');
        Route::put('/transaksi/{id}', [\App\Http\Controllers\Admin\TabunganController::class, 'updateTransaksi'])->name('update-transaksi');
        Route::delete('/transaksi/{id}', [\App\Http\Controllers\Admin\TabunganController::class, 'destroyTransaksi'])->name('destroy-transaksi');
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
        Route::post('/pengajuan/{id}/cairkan', [\App\Http\Controllers\Admin\PinjamanController::class, 'cairkanPinjaman'])->name('cairkan-pinjaman');
        Route::get('/pinjaman-aktif', [\App\Http\Controllers\Admin\PinjamanController::class, 'pinjamanAktif'])->name('pinjaman-aktif');
        Route::get('/pinjaman-aktif/create', [\App\Http\Controllers\Admin\PinjamanController::class, 'createPinjaman'])->name('create-pinjaman');
        Route::post('/pinjaman-aktif', [\App\Http\Controllers\Admin\PinjamanController::class, 'storePinjaman'])->name('store-pinjaman');
        Route::get('/pinjaman-aktif/{id}', [\App\Http\Controllers\Admin\PinjamanController::class, 'detailPinjaman'])->name('detail-pinjaman');
        Route::get('/pinjaman-aktif/{id}/edit', [\App\Http\Controllers\Admin\PinjamanController::class, 'editPinjaman'])->name('edit-pinjaman');
        Route::put('/pinjaman-aktif/{id}', [\App\Http\Controllers\Admin\PinjamanController::class, 'updatePinjaman'])->name('update-pinjaman');
        Route::delete('/pinjaman-aktif/{id}', [\App\Http\Controllers\Admin\PinjamanController::class, 'deletePinjaman'])->name('delete-pinjaman');
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
    
    // Master Data Routes
    Route::prefix('master-data')->name('master-data.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'index'])->name('index');
        
        // Bunga Pinjaman
        Route::prefix('bunga-pinjaman')->name('bunga-pinjaman.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'bungaPinjamanIndex'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'bungaPinjamanCreate'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'bungaPinjamanStore'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'bungaPinjamanEdit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'bungaPinjamanUpdate'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'bungaPinjamanDestroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\MasterDataController::class, 'bungaPinjamanToggleStatus'])->name('toggle-status');
        });
        
        // Denda Pinjaman
        Route::prefix('denda-pinjaman')->name('denda-pinjaman.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaPinjamanIndex'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaPinjamanCreate'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaPinjamanStore'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaPinjamanEdit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaPinjamanUpdate'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaPinjamanDestroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaPinjamanToggleStatus'])->name('toggle-status');
        });
        
        // Suku Bunga Tabungan (REMOVED)
        // Route::prefix('suku-bunga-tabungan')->name('suku-bunga-tabungan.')->group(function () { ... });
        
        // Tenor Deposito
        Route::prefix('tenor-deposito')->name('tenor-deposito.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'tenorDepositoIndex'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'tenorDepositoCreate'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'tenorDepositoStore'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'tenorDepositoEdit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'tenorDepositoUpdate'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'tenorDepositoDestroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\MasterDataController::class, 'tenorDepositoToggleStatus'])->name('toggle-status');
        });
        
        // Suku Bunga Deposito
        Route::prefix('suku-bunga-deposito')->name('suku-bunga-deposito.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaDepositoIndex'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaDepositoCreate'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaDepositoStore'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaDepositoEdit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaDepositoUpdate'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaDepositoDestroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaDepositoToggleStatus'])->name('toggle-status');
        });
        
        // Barang Gadai
        Route::prefix('barang-gadai')->name('barang-gadai.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'barangGadaiIndex'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'barangGadaiCreate'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'barangGadaiStore'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'barangGadaiEdit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'barangGadaiUpdate'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'barangGadaiDestroy'])->name('destroy');
        });
        
        // Lokasi Perusahaan
        Route::prefix('lokasi-perusahaan')->name('lokasi-perusahaan.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'lokasiPerusahaanIndex'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'lokasiPerusahaanCreate'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'lokasiPerusahaanStore'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'lokasiPerusahaanEdit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'lokasiPerusahaanUpdate'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'lokasiPerusahaanDestroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\MasterDataController::class, 'lokasiPerusahaanToggleStatus'])->name('toggle-status');
        });
        
        // Jenis Deposito
        Route::prefix('jenis-deposito')->name('jenis-deposito.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'jenisDepositoIndex'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'jenisDepositoCreate'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'jenisDepositoStore'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'jenisDepositoEdit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'jenisDepositoUpdate'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'jenisDepositoDestroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\MasterDataController::class, 'jenisDepositoToggleStatus'])->name('toggle-status');
        });

        // Jenis Akun (REMOVED)
        // Route::prefix('jns-akun')->name('jns-akun.')->group(function () { ... });

        // Biaya Transfer
        Route::prefix('biaya-transfer')->name('biaya-transfer.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'biayaTransferIndex'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'biayaTransferCreate'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'biayaTransferStore'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'biayaTransferEdit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'biayaTransferUpdate'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'biayaTransferDestroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\MasterDataController::class, 'biayaTransferToggleStatus'])->name('toggle-status');
        });
    });
    
    Route::get('/deposito', function () { return view('admin.deposito.index'); })->name('deposito.index');
    Route::get('/gadai', function () { return view('admin.gadai.index'); })->name('gadai.index');
    Route::get('/janji-temu-universal', [\App\Http\Controllers\Admin\JanjiTemuController::class, 'index'])->name('janji-temu.index'); // New Universal Janji Temu
    Route::get('/nasabah', function () { return view('admin.nasabah.index'); })->name('nasabah.index');
    Route::get('/pengajuan', function () { return view('admin.pengajuan.index'); })->name('pengajuan.index');
});
