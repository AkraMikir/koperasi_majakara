<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\ForgotPasswordController;
use App\Http\Controllers\LandingPageController;
use App\Http\Controllers\Nasabah\DashboardController as NasabahDashboardController;
use App\Http\Controllers\Nasabah\TabunganController;
use App\Http\Controllers\Nasabah\DepositoController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;

Route::get('/fix-sumber-janji-temu', function() {
    $count = \Illuminate\Support\Facades\DB::table('petty_cash_saldo')
        ->where('role', 'admin')
        ->where('sumber', 'other')
        ->where('keterangan', 'like', 'Setoran dari Janji Temu %')
        ->update(['sumber' => 'tabungan']);
    return 'Updated ' . $count . ' records. Silakan kembali ke dashboard, data sudah diperbaiki.';
});

Route::get('/', function () {
    return view('welcome');
})->name('welcome');

// Landing Page Routes
Route::get('/layanan', [LandingPageController::class, 'layanan'])->name('landing.layanan');
Route::get('/keuntungan', [LandingPageController::class, 'keuntungan'])->name('landing.keuntungan');
Route::get('/testimoni', [LandingPageController::class, 'testimoni'])->name('landing.testimoni');
Route::get('/faq', [LandingPageController::class, 'faq'])->name('landing.faq');

// Authentication Routes
Route::get('/register', [RegisterController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [RegisterController::class, 'register'])->name('register.submit');
Route::post('/register/ocr', [RegisterController::class, 'processOcr'])->name('register.ocr');
Route::post('/register/check-unique', [RegisterController::class, 'checkUnique'])->name('register.check-unique');

Route::get('/login', [LoginController::class, 'showLoginForm'])->name('login');
Route::post('/login', [LoginController::class, 'login'])->name('login.submit');
Route::post('/login/verify-pin', [LoginController::class, 'verifyPin'])->name('login.verify-pin');
Route::post('/logout', [LoginController::class, 'logout'])->name('logout');

// Guest Forgot Password Routes
Route::get('/forgot-password', [ForgotPasswordController::class, 'showLinkRequestForm'])->name('password.request');
Route::post('/forgot-password', [ForgotPasswordController::class, 'sendOtp'])->name('password.email');
Route::get('/forgot-password/verify', [ForgotPasswordController::class, 'showVerifyForm'])->name('password.verify');
Route::post('/forgot-password/verify', [ForgotPasswordController::class, 'verifyOtpAndResetPassword'])->name('password.update');
Route::post('/forgot-password/resend-otp', [ForgotPasswordController::class, 'resendOtp'])->name('password.resend-otp');
Route::get('/forgot-password/otp-cooldown', [ForgotPasswordController::class, 'getOtpCooldown'])->name('password.otp-cooldown');

// Nasabah Routes (Protected with Auth Middleware)
Route::prefix('nasabah')->middleware('auth')->name('nasabah.')->group(function () {
    Route::get('/dashboard', [NasabahDashboardController::class, 'index'])->name('dashboard');
    Route::get('/profile', [NasabahDashboardController::class, 'profile'])->name('profile');
    Route::get('/pengajuan-pending', [NasabahDashboardController::class, 'pengajuanPending'])->name('pengajuan-pending');
    Route::get('/guide', [\App\Http\Controllers\Nasabah\GuideController::class, 'index'])->name('guide');
    Route::get('/notifications', [\App\Http\Controllers\Nasabah\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Nasabah\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Nasabah\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');
    Route::prefix('guide')->name('guide.')->group(function () {
        Route::get('/tabungan-setoran', [\App\Http\Controllers\Nasabah\GuideController::class, 'tabunganSetoran'])->name('tabungan-setoran');
        Route::get('/tabungan-penarikan', [\App\Http\Controllers\Nasabah\GuideController::class, 'tabunganPenarikan'])->name('tabungan-penarikan');
        Route::get('/pinjaman-pengajuan', [\App\Http\Controllers\Nasabah\GuideController::class, 'pinjamanPengajuan'])->name('pinjaman-pengajuan');
        Route::get('/pinjaman-pembayaran', [\App\Http\Controllers\Nasabah\GuideController::class, 'pinjamanPembayaran'])->name('pinjaman-pembayaran');
        Route::get('/deposito-pengajuan', [\App\Http\Controllers\Nasabah\GuideController::class, 'depositoPengajuan'])->name('deposito-pengajuan');
        Route::get('/gadai-pengajuan', [\App\Http\Controllers\Nasabah\GuideController::class, 'gadaiPengajuan'])->name('gadai-pengajuan');
        Route::get('/gadai-aktif', [\App\Http\Controllers\Nasabah\GuideController::class, 'gadaiAktif'])->name('gadai-aktif');
    });

    // Restricted Nasabah Routes (Require Account Verification)
    Route::middleware('nasabah.verified')->group(function () {
        Route::get('/riwayat-transaksi', [NasabahDashboardController::class, 'riwayatTransaksi'])->name('riwayat-transaksi');
        // PIN Management Routes (DEPRECATED - Moved to Setting)
        Route::prefix('pin')->name('pin.')->group(function () {
            Route::post('/update', [\App\Http\Controllers\Nasabah\PinController::class, 'updatePin'])->name('update');
            Route::post('/send-otp-lupa', [\App\Http\Controllers\Nasabah\PinController::class, 'sendOtpLupaPin'])->name('send-otp-lupa');
            Route::post('/resend-otp-lupa', [\App\Http\Controllers\Nasabah\PinController::class, 'resendOtpLupaPin'])->name('resend-otp-lupa');
            Route::post('/verify-otp-lupa', [\App\Http\Controllers\Nasabah\PinController::class, 'verifyOtpLupaPin'])->name('verify-otp-lupa');
            Route::get('/get-cooldown', [\App\Http\Controllers\Nasabah\PinController::class, 'getCooldown'])->name('get-cooldown');
        });
        
        // Setting Routes (Security & Privacy)
        Route::prefix('setting')->name('setting.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Nasabah\SettingController::class, 'index'])->name('index');
            
            // Password Management
            Route::post('/change-password', [\App\Http\Controllers\Nasabah\SettingController::class, 'changePassword'])->name('change-password');
            Route::post('/send-otp-password-reset', [\App\Http\Controllers\Nasabah\SettingController::class, 'sendOtpPasswordReset'])->name('send-otp-password-reset');
            Route::post('/verify-otp-reset-password', [\App\Http\Controllers\Nasabah\SettingController::class, 'verifyOtpAndResetPassword'])->name('verify-otp-reset-password');
            
            // PIN Management
            Route::post('/change-pin', [\App\Http\Controllers\Nasabah\SettingController::class, 'changePin'])->name('change-pin');
            Route::get('/otp-cooldown', [\App\Http\Controllers\Nasabah\SettingController::class, 'getOtpCooldown'])->name('otp-cooldown');
        });
        
        // Profile Update Routes
        Route::prefix('profile')->name('profile.')->group(function () {
            Route::post('/update-request', [\App\Http\Controllers\Nasabah\ProfileController::class, 'submitUpdateRequest'])->name('update-request');
            Route::delete('/cancel-request/{id}', [\App\Http\Controllers\Nasabah\ProfileController::class, 'cancelUpdateRequest'])->name('cancel-request');
        });
        
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
            Route::get('/status-janji-temu', [TabunganController::class, 'statusJanjiTemu'])->name('status-janji-temu');
            Route::get('/status-pengajuan-tarik', [TabunganController::class, 'statusPengajuanTarik'])->name('status-pengajuan-tarik');
            Route::get('/pengajuan-setor/{id}', [TabunganController::class, 'detailPengajuanSetor'])->name('detail-pengajuan-setor');
            Route::get('/pengajuan-tarik/{id}', [TabunganController::class, 'detailPengajuanTarik'])->name('detail-pengajuan-tarik');
            Route::get('/transaksi/{id}', [TabunganController::class, 'detailTransaksi'])->name('detail-transaksi');
            Route::get('/transaksi/{id}/struk', [\App\Http\Controllers\Nasabah\StrukController::class, 'transaksiTabungan'])->name('struk-transaksi');
            Route::get('/janji-temu/{id}', [TabunganController::class, 'detailJanjiTemu'])->name('detail-janji-temu');
            Route::post('/janji-temu/{id}/cancel', [TabunganController::class, 'cancelJanjiTemu'])->name('cancel-janji-temu');
            Route::post('/pengajuan-setor/{id}/cancel', [TabunganController::class, 'cancelPengajuanSetor'])->name('cancel-pengajuan-setor');
            Route::post('/pengajuan-tarik/{id}/cancel', [TabunganController::class, 'cancelPengajuanTarik'])->name('cancel-pengajuan-tarik');
        });
        
        // Pinjaman Routes
        Route::prefix('pinjaman')->middleware('emergency_contact')->name('pinjaman.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'index'])->name('index');
            Route::post('/agree-terms', [\App\Http\Controllers\Nasabah\PinjamanController::class, 'agreeTerms'])->name('agree-terms');
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
            Route::get('/pembayaran/{id}/struk', [\App\Http\Controllers\Nasabah\StrukController::class, 'pembayaranPinjaman'])->name('struk-pembayaran');
            Route::get('/pinjaman-aktif/{id}/struk-pencairan', [\App\Http\Controllers\Nasabah\StrukController::class, 'pencairanPinjaman'])->name('struk-pencairan');
            Route::get('/angsuran/{id}/struk', [\App\Http\Controllers\Nasabah\StrukController::class, 'angsuran'])->name('struk-angsuran');
        });

        // Struk Gadai
        Route::get('/gadai/{id}/struk', [\App\Http\Controllers\Nasabah\StrukController::class, 'gadaiActive'])->name('struk-gadai');
        Route::get('/deposito/{id}/struk', [\App\Http\Controllers\Nasabah\StrukController::class, 'depositoActive'])->name('struk-deposito');

        // Deposito Routes
        Route::prefix('deposito')->name('deposito.')->group(function () {
            Route::get('/', [DepositoController::class, 'index'])->name('index');
            Route::get('/riwayat', [DepositoController::class, 'riwayat'])->name('riwayat');
            Route::get('/pengajuan', [DepositoController::class, 'pengajuan'])->name('pengajuan');
            Route::post('/pengajuan', [DepositoController::class, 'submitPengajuan'])->name('submit-pengajuan');
            Route::post('/verify-pin', [DepositoController::class, 'verifyPin'])->name('verify-pin');
            Route::get('/pengajuan/{id}/status', [DepositoController::class, 'statusPengajuan'])->name('status-pengajuan');
            Route::get('/aktif/{id}', [DepositoController::class, 'detail'])->name('detail');
            // Ajukan pencairan deposito
            Route::post('/aktif/{id}/cairkan', [DepositoController::class, 'ajukanCairkan'])->name('ajukan-cairkan');
            // Ajukan cancel deposito
            Route::post('/aktif/{id}/cancel', [DepositoController::class, 'ajukanCancel'])->name('ajukan-cancel');
        });

        // Gadai Baru Routes
        Route::prefix('gadai_baru')->middleware('emergency_contact')->name('gadai_baru.')->group(function () {
            Route::get('/', [\App\Http\Controllers\NasabahGadaiBaruController::class, 'index'])->name('index');
            Route::post('/agree-terms', [\App\Http\Controllers\NasabahGadaiBaruController::class, 'agreeTerms'])->name('agree-terms');
            Route::get('/riwayat', [\App\Http\Controllers\NasabahGadaiBaruController::class, 'riwayat'])->name('riwayat');
            Route::get('/aktif/{id}', [\App\Http\Controllers\NasabahGadaiBaruController::class, 'showActiveDetail'])->name('aktif-detail');
            Route::get('/{kategori}/{item}', [\App\Http\Controllers\NasabahGadaiBaruController::class, 'show'])->name('show');
            
            // Pengajuan Pembayaran/Perpanjangan
            Route::get('/pengajuan/{id}/{jenis}', [\App\Http\Controllers\NasabahGadaiBaruController::class, 'createPengajuan'])->name('create-pengajuan');
            Route::post('/pengajuan/{id}/{jenis}', [\App\Http\Controllers\NasabahGadaiBaruController::class, 'storePengajuan'])->name('store-pengajuan');
            Route::get('/status-pengajuan', [\App\Http\Controllers\NasabahGadaiBaruController::class, 'statusPengajuan'])->name('status-pengajuan');
        });
    });
});

// Admin Routes - Protected with authentication and admin role check
Route::prefix('admin')->middleware(['auth', 'admin'])->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

    // Admin Profile & Settings
    Route::prefix('profile')->name('profile.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\ProfileController::class, 'index'])->name('index');
        Route::put('/update', [\App\Http\Controllers\Admin\ProfileController::class, 'update'])->name('update');
    });

    Route::prefix('setting')->name('setting.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\SettingController::class, 'index'])->name('index');
        Route::put('/update-password', [\App\Http\Controllers\Admin\SettingController::class, 'updatePassword'])->name('update-password');
        Route::put('/update-pin', [\App\Http\Controllers\Admin\SettingController::class, 'updatePin'])->name('update-pin');
    });

    // Settings Struk
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/struk', [\App\Http\Controllers\Admin\SettingsStrukController::class, 'index'])->name('struk');
        Route::post('/struk/update-header', [\App\Http\Controllers\Admin\SettingsStrukController::class, 'updateHeader'])->name('struk.update-header');
        Route::post('/struk/update-syarat-gadai', [\App\Http\Controllers\Admin\SettingsStrukController::class, 'updateSyaratGadai'])->name('struk.update-syarat-gadai');
        Route::post('/struk/update-syarat-pinjaman', [\App\Http\Controllers\Admin\SettingsStrukController::class, 'updateSyaratPinjaman'])->name('struk.update-syarat-pinjaman');
        Route::post('/struk/update-info-box-pinjaman', [\App\Http\Controllers\Admin\SettingsStrukController::class, 'updateInfoBoxPinjaman'])->name('struk.update-info-box-pinjaman');
        Route::post('/struk/update-extra-kehilangan', [\App\Http\Controllers\Admin\SettingsStrukController::class, 'updateExtraKehilangan'])->name('struk.update-extra-kehilangan');
        Route::post('/struk/preview-tabungan', [\App\Http\Controllers\Admin\SettingsStrukController::class, 'previewTabungan'])->name('struk.preview-tabungan');
        Route::post('/struk/preview-pinjaman', [\App\Http\Controllers\Admin\SettingsStrukController::class, 'previewPinjaman'])->name('struk.preview-pinjaman');
        Route::post('/struk/preview-deposito', [\App\Http\Controllers\Admin\SettingsStrukController::class, 'previewDeposito'])->name('struk.preview-deposito');
        Route::post('/struk/preview-gadai', [\App\Http\Controllers\Admin\SettingsStrukController::class, 'previewGadai'])->name('struk.preview-gadai');
    });

    // Struk Gadai
    Route::get('/gadai/{id}/struk', [\App\Http\Controllers\Admin\StrukController::class, 'gadaiActive'])->name('struk-gadai');
    Route::get('/deposito/{id}/struk', [\App\Http\Controllers\Admin\StrukController::class, 'depositoActive'])->name('struk-deposito');

    // Notifikasi Admin
    Route::get('/notifications', [\App\Http\Controllers\Admin\NotificationController::class, 'index'])->name('notifications.index');
    Route::post('/notifications/{id}/read', [\App\Http\Controllers\Admin\NotificationController::class, 'markAsRead'])->name('notifications.mark-read');
    Route::post('/notifications/read-all', [\App\Http\Controllers\Admin\NotificationController::class, 'markAllRead'])->name('notifications.mark-all-read');

    // Laporan Keuangan
    Route::prefix('laporan')->name('laporan.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\LaporanKeuanganController::class, 'index'])->name('index');
        Route::get('/rekapitulasi', [\App\Http\Controllers\Admin\LaporanKeuanganController::class, 'rekapitulasi'])->name('rekapitulasi');
        Route::get('/tabungan', [\App\Http\Controllers\Admin\LaporanKeuanganController::class, 'tabungan'])->name('tabungan');
        Route::get('/saldo-tabungan', [\App\Http\Controllers\Admin\LaporanKeuanganController::class, 'saldoTabungan'])->name('saldo-tabungan');
        Route::get('/pinjaman-aktif', [\App\Http\Controllers\Admin\LaporanKeuanganController::class, 'pinjamanAktif'])->name('pinjaman-aktif');
        Route::get('/angsuran-pinjaman', [\App\Http\Controllers\Admin\LaporanKeuanganController::class, 'angsuranPinjaman'])->name('angsuran-pinjaman');
        Route::get('/jatuh-tempo', [\App\Http\Controllers\Admin\LaporanKeuanganController::class, 'jatuhTempo'])->name('jatuh-tempo');
        Route::get('/pengajuan', [\App\Http\Controllers\Admin\LaporanKeuanganController::class, 'pengajuan'])->name('pengajuan');
    });
    
    // Tabungan Routes
    Route::prefix('tabungan')->name('tabungan.')->group(function () {
        // View routes - accessible by all admins
        Route::get('/', [\App\Http\Controllers\Admin\TabunganController::class, 'index'])->name('index');
        Route::get('/pengajuan-setor', [\App\Http\Controllers\Admin\TabunganController::class, 'pengajuanSetor'])->name('pengajuan-setor');
        Route::get('/pengajuan-setor/{id}', [\App\Http\Controllers\Admin\TabunganController::class, 'detailPengajuanSetor'])->name('detail-pengajuan-setor');
        Route::get('/pengajuan-tarik', [\App\Http\Controllers\Admin\TabunganController::class, 'pengajuanTarik'])->name('pengajuan-tarik');
        Route::get('/pengajuan-tarik/{id}', [\App\Http\Controllers\Admin\TabunganController::class, 'detailPengajuanTarik'])->name('detail-pengajuan-tarik');
        Route::get('/transaksi', [\App\Http\Controllers\Admin\TabunganController::class, 'transaksi'])->name('transaksi');
        // CRUD Transaksi routes - MUST be before /transaksi/{id} agar 'create' tidak tertangkap sebagai {id}
        Route::middleware('admin.permission:crud-tabungan')->group(function () {
            Route::get('/transaksi/create', [\App\Http\Controllers\Admin\TabunganController::class, 'createTransaksi'])->name('create-transaksi');
            Route::post('/transaksi', [\App\Http\Controllers\Admin\TabunganController::class, 'storeTransaksi'])->name('store-transaksi');
            Route::get('/transaksi/{id}/edit', [\App\Http\Controllers\Admin\TabunganController::class, 'editTransaksi'])->name('edit-transaksi');
            Route::put('/transaksi/{id}', [\App\Http\Controllers\Admin\TabunganController::class, 'updateTransaksi'])->name('update-transaksi');
            Route::delete('/transaksi/{id}', [\App\Http\Controllers\Admin\TabunganController::class, 'destroyTransaksi'])->name('destroy-transaksi');
            Route::post('/pengajuan-setor/{id}/edit', [\App\Http\Controllers\Admin\TabunganController::class, 'editPengajuanSetor'])->name('edit-pengajuan-setor');
            Route::delete('/pengajuan-setor/{id}', [\App\Http\Controllers\Admin\TabunganController::class, 'deletePengajuanSetor'])->name('delete-pengajuan-setor');
        });
        Route::get('/transaksi/{id}', [\App\Http\Controllers\Admin\TabunganController::class, 'detailTransaksi'])->name('detail-transaksi');
        Route::get('/transaksi/{id}/struk', [\App\Http\Controllers\Admin\StrukController::class, 'transaksiTabungan'])->name('struk-transaksi');
        Route::get('/transaksi/{id}/print-struk', [\App\Http\Controllers\Admin\StrukController::class, 'printTransaksiTabunganHtml'])->name('print-struk-transaksi');
        Route::get('/saldo-nasabah', [\App\Http\Controllers\Admin\TabunganController::class, 'saldoNasabah'])->name('saldo-nasabah');
        Route::get('/janji-temu/{id}', [\App\Http\Controllers\Admin\TabunganController::class, 'detailJanjiTemu'])->name('detail-janji-temu');

        // Approval routes - accessible by all admins (Admin Utama & Admin Operasional)
        Route::post('/pengajuan-setor/{id}/approve', [\App\Http\Controllers\Admin\TabunganController::class, 'approveSetor'])->name('approve-setor');
        Route::post('/pengajuan-setor/{id}/reject', [\App\Http\Controllers\Admin\TabunganController::class, 'rejectSetor'])->name('reject-setor');
        Route::post('/pengajuan-tarik/{id}/approve', [\App\Http\Controllers\Admin\TabunganController::class, 'approveTarik'])->name('approve-tarik');
        Route::post('/pengajuan-tarik/{id}/reject', [\App\Http\Controllers\Admin\TabunganController::class, 'rejectTarik'])->name('reject-tarik');
        Route::post('/janji-temu/{id}/create-trans', [\App\Http\Controllers\Admin\TabunganController::class, 'createTransFromJanjiTemu'])->name('create-trans-from-janji-temu');
    });
    
    // Pinjaman Routes
    Route::prefix('pinjaman')->name('pinjaman.')->group(function () {
        // View routes - accessible by all admins
        Route::get('/', [\App\Http\Controllers\Admin\PinjamanController::class, 'index'])->name('index');
        Route::get('/pengajuan', [\App\Http\Controllers\Admin\PinjamanController::class, 'pengajuan'])->name('pengajuan');
        Route::get('/pengajuan/{id}', [\App\Http\Controllers\Admin\PinjamanController::class, 'detailPengajuan'])->name('detail-pengajuan');
        Route::get('/pinjaman-aktif', [\App\Http\Controllers\Admin\PinjamanController::class, 'pinjamanAktif'])->name('pinjaman-aktif');
        Route::get('/limit', [\App\Http\Controllers\Admin\PinjamanController::class, 'limitIndex'])->name('limit.index');
        Route::get('/limit/{id_nasabah}/logs', [\App\Http\Controllers\Admin\PinjamanController::class, 'limitLogs'])->name('limit.logs');
        Route::post('/limit/{id_nasabah}/update', [\App\Http\Controllers\Admin\PinjamanController::class, 'limitUpdate'])->name('limit.update');
        // CRUD Pinjaman - MUST be before /pinjaman-aktif/{id} agar 'create' tidak tertangkap sebagai {id}
        Route::middleware('admin.permission:crud-pinjaman')->group(function () {
            Route::get('/pinjaman-aktif/create', [\App\Http\Controllers\Admin\PinjamanController::class, 'createPinjaman'])->name('create-pinjaman');
            Route::post('/pinjaman-aktif', [\App\Http\Controllers\Admin\PinjamanController::class, 'storePinjaman'])->name('store-pinjaman');
            Route::get('/pinjaman-aktif/{id}/edit', [\App\Http\Controllers\Admin\PinjamanController::class, 'editPinjaman'])->name('edit-pinjaman');
            Route::put('/pinjaman-aktif/{id}', [\App\Http\Controllers\Admin\PinjamanController::class, 'updatePinjaman'])->name('update-pinjaman');
            Route::delete('/pinjaman-aktif/{id}', [\App\Http\Controllers\Admin\PinjamanController::class, 'deletePinjaman'])->name('delete-pinjaman');
        });
        Route::get('/pinjaman-lunas', [\App\Http\Controllers\Admin\PinjamanController::class, 'pinjamanLunas'])->name('pinjaman-lunas');
        Route::get('/pinjaman-aktif/{id}', [\App\Http\Controllers\Admin\PinjamanController::class, 'detailPinjaman'])->name('detail-pinjaman');
        Route::get('/angsuran', [\App\Http\Controllers\Admin\PinjamanController::class, 'angsuran'])->name('angsuran');
        Route::get('/angsuran/{id}', [\App\Http\Controllers\Admin\PinjamanController::class, 'detailAngsuran'])->name('detail-angsuran');
        Route::get('/pembayaran', [\App\Http\Controllers\Admin\PinjamanController::class, 'pembayaran'])->name('pembayaran');
        Route::get('/pembayaran/{id}', [\App\Http\Controllers\Admin\PinjamanController::class, 'detailPembayaran'])->name('detail-pembayaran');
        Route::get('/pembayaran/{id}/struk', [\App\Http\Controllers\Admin\StrukController::class, 'pembayaranPinjaman'])->name('struk-pembayaran');
        Route::get('/pinjaman-aktif/{id}/struk-pencairan', [\App\Http\Controllers\Admin\StrukController::class, 'pencairanPinjaman'])->name('struk-pencairan');
        Route::get('/pinjaman-aktif/{id}/struk-pencairan-b5', [\App\Http\Controllers\Admin\StrukController::class, 'pencairanPinjamanB5'])->name('struk-pencairan-b5');
        Route::get('/angsuran/{id}/struk', [\App\Http\Controllers\Admin\StrukController::class, 'angsuran'])->name('struk-angsuran');

        // Approval & Cairkan routes - accessible by all admins (Admin Utama & Admin Operasional)
        Route::post('/pengajuan/{id}/approve', [\App\Http\Controllers\Admin\PinjamanController::class, 'approvePengajuan'])->name('approve-pengajuan');
        Route::post('/pengajuan/{id}/reject', [\App\Http\Controllers\Admin\PinjamanController::class, 'rejectPengajuan'])->name('reject-pengajuan');
        Route::post('/pengajuan/{id}/cairkan', [\App\Http\Controllers\Admin\PinjamanController::class, 'cairkanPinjaman'])->name('cairkan-pinjaman');
        Route::post('/pembayaran/{id}/approve', [\App\Http\Controllers\Admin\PinjamanController::class, 'approvePembayaran'])->name('approve-pembayaran');
        Route::post('/pembayaran/{id}/reject', [\App\Http\Controllers\Admin\PinjamanController::class, 'rejectPembayaran'])->name('reject-pembayaran');
        Route::post('/pembayaran/{id}/konfirmasi', [\App\Http\Controllers\Admin\PinjamanController::class, 'konfirmasiPembayaran'])->name('konfirmasi-pembayaran');
        Route::post('/pembayaran/{id}/upload-serah-terima', [\App\Http\Controllers\Admin\PinjamanController::class, 'uploadSerahTerima'])->name('upload-serah-terima');

        // Pelunasan Dipercepat - ONLY Admin Utama
        Route::middleware('admin.permission:pelunasan-dipercepat')->group(function () {
            Route::post('/pinjaman-aktif/{id}/pelunasan-dipercepat', [\App\Http\Controllers\Admin\PinjamanController::class, 'pelunasanDipercepat'])->name('pelunasan-dipercepat');
        });

        // Janji Temu Pinjaman — detail view & proses (cairkan)
        Route::prefix('janji-temu')->name('janji-temu.')->group(function () {
            Route::get('/detail-pinjaman/{id}', [\App\Http\Controllers\Admin\JanjiTemuController::class, 'detailPinjaman'])->name('detail-pinjaman');
            Route::post('/proses-pinjaman/{id}', [\App\Http\Controllers\Admin\PinjamanController::class, 'prosesJanjiTemuPinjaman'])->name('proses-pinjaman');
        });
    });
    
    // Master Data Routes - View accessible by all admins, CRUD only for Admin Utama
    Route::prefix('master-data')->name('master-data.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'index'])->name('index');
        
        // Item Gadai Baru
        Route::prefix('item-gadai')->name('item-gadai.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterItemGadaiController::class, 'index'])->name('index');
            Route::middleware('admin.permission:crud-item-gadai')->group(function () {
                Route::get('/create', [\App\Http\Controllers\Admin\MasterItemGadaiController::class, 'create'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\MasterItemGadaiController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterItemGadaiController::class, 'edit'])->name('edit');
                Route::put('/{id}', [\App\Http\Controllers\Admin\MasterItemGadaiController::class, 'update'])->name('update');
                Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterItemGadaiController::class, 'destroy'])->name('destroy');
            });
        });

        // Inap Kendaraan
        Route::prefix('inap-kendaraan')->name('inap-kendaraan.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterInapKendaraanController::class, 'index'])->name('index');
            Route::middleware('admin.permission:crud-master-data')->group(function () {
                Route::get('/create', [\App\Http\Controllers\Admin\MasterInapKendaraanController::class, 'create'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\MasterInapKendaraanController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterInapKendaraanController::class, 'edit'])->name('edit');
                Route::put('/{id}', [\App\Http\Controllers\Admin\MasterInapKendaraanController::class, 'update'])->name('update');
                Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterInapKendaraanController::class, 'destroy'])->name('destroy');
            });
        });
        
        // Bunga Pinjaman
        Route::prefix('bunga-pinjaman')->name('bunga-pinjaman.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'bungaPinjamanIndex'])->name('index');
            
            // CRUD routes - ONLY Admin Utama
            Route::middleware('admin.permission:crud-master-data')->group(function () {
                Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'bungaPinjamanCreate'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'bungaPinjamanStore'])->name('store');
                Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'bungaPinjamanEdit'])->name('edit');
                Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'bungaPinjamanUpdate'])->name('update');
                Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'bungaPinjamanDestroy'])->name('destroy');
                Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\MasterDataController::class, 'bungaPinjamanToggleStatus'])->name('toggle-status');
            });
        });

        // Tujuan Pinjaman - CRUD (Admin Utama & Operasional)
        Route::prefix('tujuan-pinjaman')->name('tujuan-pinjaman.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'tujuanPinjamanIndex'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'tujuanPinjamanCreate'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'tujuanPinjamanStore'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'tujuanPinjamanEdit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'tujuanPinjamanUpdate'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'tujuanPinjamanDestroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\MasterDataController::class, 'tujuanPinjamanToggleStatus'])->name('toggle-status');
        });
        
        // Denda Pinjaman
        Route::prefix('denda-pinjaman')->name('denda-pinjaman.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaPinjamanIndex'])->name('index');
            
            Route::middleware('admin.permission:crud-master-data')->group(function () {
                Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaPinjamanCreate'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaPinjamanStore'])->name('store');
                Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaPinjamanEdit'])->name('edit');
                Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaPinjamanUpdate'])->name('update');
                Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaPinjamanDestroy'])->name('destroy');
                Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaPinjamanToggleStatus'])->name('toggle-status');
            });
        });
        
        // Denda Deposito
        Route::prefix('denda-deposito')->name('denda-deposito.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaDepositoIndex'])->name('index');
            
            Route::middleware('admin.permission:crud-master-data')->group(function () {
                Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaDepositoCreate'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaDepositoStore'])->name('store');
                Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaDepositoEdit'])->name('edit');
                Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaDepositoUpdate'])->name('update');
                Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaDepositoDestroy'])->name('destroy');
                Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\MasterDataController::class, 'dendaDepositoToggleStatus'])->name('toggle-status');
            });
        });
        
        // Suku Bunga Tabungan
        Route::prefix('suku-bunga-tabungan')->name('suku-bunga-tabungan.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaTabunganIndex'])->name('index');
            
            Route::middleware('admin.permission:crud-master-data')->group(function () {
                Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaTabunganCreate'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaTabunganStore'])->name('store');
                Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaTabunganEdit'])->name('edit');
                Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaTabunganUpdate'])->name('update');
                Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaTabunganDestroy'])->name('destroy');
            });
        });

        // Tenor Deposito
        Route::prefix('tenor-deposito')->name('tenor-deposito.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'tenorDepositoIndex'])->name('index');
            
            Route::middleware('admin.permission:crud-master-data')->group(function () {
                Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'tenorDepositoCreate'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'tenorDepositoStore'])->name('store');
                Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'tenorDepositoEdit'])->name('edit');
                Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'tenorDepositoUpdate'])->name('update');
                Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'tenorDepositoDestroy'])->name('destroy');
                Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\MasterDataController::class, 'tenorDepositoToggleStatus'])->name('toggle-status');
            });
        });
        
        // Kategori Deposito
        Route::prefix('kategori-deposito')->name('kategori-deposito.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\KategoriDepositoController::class, 'index'])->name('index');
            
            Route::middleware('admin.permission:crud-master-data')->group(function () {
                Route::get('/create', [\App\Http\Controllers\Admin\KategoriDepositoController::class, 'create'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\KategoriDepositoController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [\App\Http\Controllers\Admin\KategoriDepositoController::class, 'edit'])->name('edit');
                Route::put('/{id}', [\App\Http\Controllers\Admin\KategoriDepositoController::class, 'update'])->name('update');
                Route::delete('/{id}', [\App\Http\Controllers\Admin\KategoriDepositoController::class, 'destroy'])->name('destroy');
            });
        });
        
        // Suku Bunga Deposito
        Route::prefix('suku-bunga-deposito')->name('suku-bunga-deposito.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaDepositoIndex'])->name('index');
            
            Route::middleware('admin.permission:crud-master-data')->group(function () {
                Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaDepositoCreate'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaDepositoStore'])->name('store');
                Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaDepositoEdit'])->name('edit');
                Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaDepositoUpdate'])->name('update');
                Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaDepositoDestroy'])->name('destroy');
                Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\MasterDataController::class, 'sukuBungaDepositoToggleStatus'])->name('toggle-status');
            });
        });
        
        // Barang Gadai
        Route::prefix('barang-gadai')->name('barang-gadai.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'barangGadaiIndex'])->name('index');
            
            Route::middleware('admin.permission:crud-master-data')->group(function () {
                Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'barangGadaiCreate'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'barangGadaiStore'])->name('store');
                Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'barangGadaiEdit'])->name('edit');
                Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'barangGadaiUpdate'])->name('update');
                Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'barangGadaiDestroy'])->name('destroy');
            });
        });

        // Kategori Gadai
        Route::prefix('kategori-gadai')->name('kategori-gadai.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterKategoriGadaiController::class, 'index'])->name('index');
            Route::middleware('admin.permission:crud-master-data')->group(function () {
                Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterKategoriGadaiController::class, 'edit'])->name('edit');
                Route::put('/{id}', [\App\Http\Controllers\Admin\MasterKategoriGadaiController::class, 'update'])->name('update');
            });
        });

        // Item Gadai
        Route::prefix('item-gadai')->name('item-gadai.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterItemGadaiController::class, 'index'])->name('index');
            Route::middleware('admin.permission:crud-item-gadai')->group(function () {
                Route::get('/create', [\App\Http\Controllers\Admin\MasterItemGadaiController::class, 'create'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\MasterItemGadaiController::class, 'store'])->name('store');
                Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterItemGadaiController::class, 'edit'])->name('edit');
                Route::put('/{id}', [\App\Http\Controllers\Admin\MasterItemGadaiController::class, 'update'])->name('update');
                Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterItemGadaiController::class, 'destroy'])->name('destroy');
            });
        });
        
        // Lokasi Perusahaan
        Route::prefix('lokasi-perusahaan')->name('lokasi-perusahaan.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'lokasiPerusahaanIndex'])->name('index');
            
            Route::middleware('admin.permission:crud-master-data')->group(function () {
                Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'lokasiPerusahaanCreate'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'lokasiPerusahaanStore'])->name('store');
                Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'lokasiPerusahaanEdit'])->name('edit');
                Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'lokasiPerusahaanUpdate'])->name('update');
                Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'lokasiPerusahaanDestroy'])->name('destroy');
                Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\MasterDataController::class, 'lokasiPerusahaanToggleStatus'])->name('toggle-status');
            });
        });

        // Slot Storage Grid (Master Data)
        Route::prefix('slot-storage')->name('slot-storage.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterSlotStorageController::class, 'index'])->name('index');
            Route::post('/', [\App\Http\Controllers\Admin\MasterSlotStorageController::class, 'store'])->name('store');
            Route::post('/reduce', [\App\Http\Controllers\Admin\MasterSlotStorageController::class, 'reduce'])->name('reduce');
        });
        
        // Jenis Deposito
        Route::prefix('jenis-deposito')->name('jenis-deposito.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'jenisDepositoIndex'])->name('index');
            
            Route::middleware('admin.permission:crud-master-data')->group(function () {
                Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'jenisDepositoCreate'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'jenisDepositoStore'])->name('store');
                Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'jenisDepositoEdit'])->name('edit');
                Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'jenisDepositoUpdate'])->name('update');
                Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'jenisDepositoDestroy'])->name('destroy');
                Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\MasterDataController::class, 'jenisDepositoToggleStatus'])->name('toggle-status');
            });
        });

        // Biaya Transfer
        Route::prefix('biaya-transfer')->name('biaya-transfer.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'biayaTransferIndex'])->name('index');
            
            Route::middleware('admin.permission:crud-biaya-transfer')->group(function () {
                Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'biayaTransferCreate'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'biayaTransferStore'])->name('store');
                Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'biayaTransferEdit'])->name('edit');
                Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'biayaTransferUpdate'])->name('update');
                Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'biayaTransferDestroy'])->name('destroy');
                Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\MasterDataController::class, 'biayaTransferToggleStatus'])->name('toggle-status');
            });
        });

        // Rekening Perusahaan (jns_bank)
        Route::prefix('rekening-perusahaan')->name('rekening-perusahaan.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'rekeningPerusahaanIndex'])->name('index');
            Route::middleware('admin.permission:crud-master-data')->group(function () {
                Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'rekeningPerusahaanCreate'])->name('create');
                Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'rekeningPerusahaanStore'])->name('store');
                Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'rekeningPerusahaanEdit'])->name('edit');
                Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'rekeningPerusahaanUpdate'])->name('update');
                Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'rekeningPerusahaanDestroy'])->name('destroy');
            });
        });

        // Manajemen Admin Operasional - ONLY Admin Utama (semua route)
        Route::prefix('admin-operasional')->name('admin-operasional.')->middleware('admin.permission:crud-master-data')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'adminOperasionalIndex'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\MasterDataController::class, 'adminOperasionalCreate'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'adminOperasionalStore'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\MasterDataController::class, 'adminOperasionalEdit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'adminOperasionalUpdate'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\MasterDataController::class, 'adminOperasionalDestroy'])->name('destroy');
            Route::post('/{id}/toggle-status', [\App\Http\Controllers\Admin\MasterDataController::class, 'adminOperasionalToggleStatus'])->name('toggle-status');
        });

        // Debugger Gadai (Testing Time Travel) - ONLY Admin Utama
        Route::prefix('gadai-debugger')->name('gadai-debugger.')->middleware('admin.utama')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'gadaiDebuggerIndex'])->name('index');
            Route::post('/maju-hari', [\App\Http\Controllers\Admin\MasterDataController::class, 'gadaiDebuggerMajuHari'])->name('maju-hari');
        });

        // Debugger Pinjaman (Testing Time Travel) - ONLY Admin Utama
        Route::prefix('pinjaman-debugger')->name('pinjaman-debugger.')->middleware('admin.utama')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'pinjamanDebuggerIndex'])->name('index');
            Route::post('/maju-hari', [\App\Http\Controllers\Admin\MasterDataController::class, 'pinjamanDebuggerMajuHari'])->name('maju-hari');
        });

        Route::prefix('deposito-debugger')->name('deposito-debugger.')->middleware('admin.utama')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'depositoDebuggerIndex'])->name('index');
            Route::post('/maju-hari', [\App\Http\Controllers\Admin\MasterDataController::class, 'depositoDebuggerMajuHari'])->name('maju-hari');
        });

        // Jns Fitur, Via, Transaksi View
        Route::get('/fitur', [\App\Http\Controllers\Admin\MasterDataController::class, 'fiturIndex'])->name('fitur.index');
        Route::get('/via', [\App\Http\Controllers\Admin\MasterDataController::class, 'viaIndex'])->name('via.index');
        Route::get('/transaksi', [\App\Http\Controllers\Admin\MasterDataController::class, 'transaksiIndex'])->name('transaksi.index');

        // Syarat & Ketentuan Layanan (T&C Jangka Panjang)
        Route::prefix('syarat-ketentuan-layanan')->name('syarat-ketentuan-layanan.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'syaratKetentuanLayananIndex'])->name('index');
            Route::middleware('admin.permission:crud-master-data')->group(function () {
                Route::post('/update', [\App\Http\Controllers\Admin\MasterDataController::class, 'syaratKetentuanLayananUpdate'])->name('update');
            });
        });

        // Syarat & Ketentuan Layanan Gadai (T&C Jangka Panjang Gadai)
        Route::prefix('syarat-ketentuan-layanan-gadai')->name('syarat-ketentuan-layanan-gadai.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\MasterDataController::class, 'syaratKetentuanLayananGadaiIndex'])->name('index');
            Route::middleware('admin.permission:crud-master-data')->group(function () {
                Route::post('/update', [\App\Http\Controllers\Admin\MasterDataController::class, 'syaratKetentuanLayananGadaiUpdate'])->name('update');
            });
        });
    });
    
    // Nasabah Management Routes - View accessible by all admins, Management only for Admin Utama
    Route::prefix('nasabah')->name('nasabah.')->group(function () {
        // View routes - accessible by all admins
        Route::get('/', [\App\Http\Controllers\Admin\NasabahManagementController::class, 'index'])->name('index');
        Route::get('/pending-changes/list', [\App\Http\Controllers\Admin\NasabahManagementController::class, 'pendingChanges'])->name('pending-changes');
        Route::get('/change/{id}', [\App\Http\Controllers\Admin\NasabahManagementController::class, 'showChangeDetail'])->name('change-detail');
        Route::get('/{id}', [\App\Http\Controllers\Admin\NasabahManagementController::class, 'show'])->name('show');
        Route::post('/verify-admin-pin', [\App\Http\Controllers\Admin\NasabahManagementController::class, 'verifyAdminPin'])->name('verify-admin-pin');
        
        // Management routes - ONLY Admin Utama (Admin Operasional CANNOT access)
        Route::middleware('admin.permission:manage-nasabah')->group(function () {
            Route::post('/change/{id}/approve', [\App\Http\Controllers\Admin\NasabahManagementController::class, 'approveChange'])->name('approve-change');
            Route::post('/change/{id}/reject', [\App\Http\Controllers\Admin\NasabahManagementController::class, 'rejectChange'])->name('reject-change');
            Route::get('/generate-pin/random', [\App\Http\Controllers\Admin\NasabahManagementController::class, 'generateRandomPin'])->name('generate-pin');
            Route::post('/{id}/reset-pin', [\App\Http\Controllers\Admin\NasabahManagementController::class, 'resetPin'])->name('reset-pin');
            Route::post('/{id}/verify', [\App\Http\Controllers\Admin\NasabahManagementController::class, 'verifyNasabah'])->name('verify');
        });
    });
    
    // Petty Cash Routes
    Route::prefix('petty-cash')->name('petty-cash.')->group(function () {
        // Dashboard (Owner)
        Route::get('/dashboard', [\App\Http\Controllers\Admin\PettyCashController::class, 'dashboard'])->name('dashboard');

        // Laporan
        Route::get('/laporan', [\App\Http\Controllers\Admin\PettyCashController::class, 'laporan'])->name('laporan');

        // ── Penerimaan Dana (Owner kirim → Admin terima) ──
        // Owner: form kirim dana
        Route::get('/penerimaan/create', [\App\Http\Controllers\Admin\PettyCashController::class, 'penerimaanCreate'])->name('penerimaan.create');
        Route::post('/penerimaan', [\App\Http\Controllers\Admin\PettyCashController::class, 'penerimaanStore'])->name('penerimaan.store');
        // Admin: daftar & ACC penerimaan
        Route::get('/penerimaan', [\App\Http\Controllers\Admin\PettyCashController::class, 'penerimaanIndex'])->name('penerimaan.index');
        Route::post('/penerimaan/{id}/approve', [\App\Http\Controllers\Admin\PettyCashController::class, 'penerimaanApprove'])->name('penerimaan.approve');
        Route::post('/penerimaan/{id}/reject', [\App\Http\Controllers\Admin\PettyCashController::class, 'penerimaanReject'])->name('penerimaan.reject');
        // Konfirmasi penerimaan dana deposito khusus (Admin)
        Route::post('/penerimaan/{id}/approve-deposito', [\App\Http\Controllers\Admin\PettyCashController::class, 'approvePenerimaanDeposito'])->name('penerimaan.approve-deposito');


        Route::post('/transaksi/{id}/approve-tf', [\App\Http\Controllers\Admin\PettyCashController::class, 'approveSetoranTf'])->name('transaksi.approve-tf');

        // ── Setoran Kantor (Admin setor ke Owner) ──
        Route::get('/setoran-kantor', [\App\Http\Controllers\Admin\PettyCashController::class, 'setoranKantorIndex'])->name('setoran-kantor.index');
        Route::post('/setoran-kantor', [\App\Http\Controllers\Admin\PettyCashController::class, 'setoranKantorStore'])->name('setoran-kantor.store');

        // ── Main Wallet Owner (Unified Ledger) ──
        Route::get('/owner-wallet', [\App\Http\Controllers\Admin\OwnerWalletController::class, 'index'])->name('owner-wallet.index');
        Route::post('/owner-wallet', [\App\Http\Controllers\Admin\OwnerWalletController::class, 'store'])->name('owner-wallet.store');
        Route::post('/owner-wallet/withdraw', [\App\Http\Controllers\Admin\OwnerWalletController::class, 'withdraw'])->name('owner-wallet.withdraw');
        Route::post('/owner-wallet/internal-transfer', [\App\Http\Controllers\Admin\OwnerWalletController::class, 'internalTransfer'])->name('owner-wallet.internal-transfer');
        Route::delete('/owner-wallet/{id}', [\App\Http\Controllers\Admin\OwnerWalletController::class, 'destroy'])->name('owner-wallet.destroy');

        // ── Verifikasi Setoran (Owner) ──
        Route::get('/setoran-approval', [\App\Http\Controllers\Admin\PettyCashController::class, 'setoranApprovalIndex'])->name('setoran-approval.index');
        Route::get('/setoran-approval/{id}', [\App\Http\Controllers\Admin\PettyCashController::class, 'setoranApprovalDetail'])->name('setoran-approval.detail');
        Route::post('/setoran-approval/{id}/approve', [\App\Http\Controllers\Admin\PettyCashController::class, 'setoranApprovalApprove'])->name('setoran-approval.approve');
        Route::post('/setoran-approval/{id}/reject', [\App\Http\Controllers\Admin\PettyCashController::class, 'setoranApprovalReject'])->name('setoran-approval.reject');
    });

    // ── Dashboard Bunga (Analisis Bunga - Admin Utama & Operasional) ──
    Route::prefix('bunga')->name('bunga.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\BungaController::class, 'index'])->name('index');
        Route::get('/pinjaman', [\App\Http\Controllers\Admin\BungaController::class, 'pinjaman'])->name('pinjaman');
        Route::get('/deposito', [\App\Http\Controllers\Admin\BungaController::class, 'deposito'])->name('deposito');
        Route::get('/gadai', [\App\Http\Controllers\Admin\BungaController::class, 'gadai'])->name('gadai');

        // Pembayaran Pajak (PPh) CRUD
        Route::prefix('pajak')->name('pajak.')->group(function () {
            Route::get('/hitung', [\App\Http\Controllers\Admin\PajakBungaController::class, 'hitung'])->name('hitung');
            Route::get('/', [\App\Http\Controllers\Admin\PajakBungaController::class, 'index'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\PajakBungaController::class, 'create'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\PajakBungaController::class, 'store'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\PajakBungaController::class, 'edit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\PajakBungaController::class, 'update'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\PajakBungaController::class, 'destroy'])->name('destroy');
        });
    });

    // ── Deposito ──
    Route::prefix('deposito')->name('deposito.')->group(function () {
        Route::get('/', [\App\Http\Controllers\Admin\DepositoController::class, 'index'])->name('index');
        Route::get('/pengajuan', [\App\Http\Controllers\Admin\DepositoController::class, 'pengajuanList'])->name('pengajuan-list');
        Route::get('/pengajuan/{id}', [\App\Http\Controllers\Admin\DepositoController::class, 'detailPengajuan'])->name('detail-pengajuan');
        Route::post('/pengajuan/{id}/approve', [\App\Http\Controllers\Admin\DepositoController::class, 'approve'])->name('approve');
        Route::post('/pengajuan/{id}/reject', [\App\Http\Controllers\Admin\DepositoController::class, 'reject'])->name('reject');
        Route::get('/list', [\App\Http\Controllers\Admin\DepositoController::class, 'depositoList'])->name('deposito-list');
        Route::get('/export-pdf', [\App\Http\Controllers\Admin\DepositoController::class, 'exportPdf'])->name('export-pdf');
        Route::get('/list/{id}', [\App\Http\Controllers\Admin\DepositoController::class, 'depositoDetail'])->name('deposito-detail');

        // ── Paket Deposito (Owner Only) ──
        Route::prefix('paket')->name('paket.')->middleware('admin.utama')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DepositoController::class, 'paketIndex'])->name('index');
            Route::get('/create', [\App\Http\Controllers\Admin\DepositoController::class, 'paketCreate'])->name('create');
            Route::post('/', [\App\Http\Controllers\Admin\DepositoController::class, 'paketStore'])->name('store');
            Route::get('/{id}/edit', [\App\Http\Controllers\Admin\DepositoController::class, 'paketEdit'])->name('edit');
            Route::put('/{id}', [\App\Http\Controllers\Admin\DepositoController::class, 'paketUpdate'])->name('update');
            Route::delete('/{id}', [\App\Http\Controllers\Admin\DepositoController::class, 'paketDestroy'])->name('destroy');
        });

        // ── Pencairan via Transfer (TF) ──
        Route::prefix('pencairan-tf')->name('pencairan-tf.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DepositoController::class, 'pencairanTfIndex'])->name('index');
            Route::get('/{id}/proses', [\App\Http\Controllers\Admin\DepositoController::class, 'pencairanTfFormShow'])->name('proses-form');
            Route::post('/{id}/proses', [\App\Http\Controllers\Admin\DepositoController::class, 'pencairanTfProses'])->name('proses');
            Route::post('/{id}/finish', [\App\Http\Controllers\Admin\DepositoController::class, 'selesaikanPencairanTf'])->name('finish');
        });

        // ── Pencairan ke Tabungan ──
        Route::prefix('pencairan-tabungan')->name('pencairan-tabungan.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DepositoController::class, 'pencairanTabunganIndex'])->name('index');
            Route::post('/{id}/proses', [\App\Http\Controllers\Admin\DepositoController::class, 'pencairanTabunganProses'])->name('proses');
            Route::post('/{id}/finish', [\App\Http\Controllers\Admin\DepositoController::class, 'selesaikanPencairanTabungan'])->name('finish');
        });

        // ── Pencairan via Petty Cash Operator (Tunai ke nasabah via Admin) ──
        Route::prefix('pencairan-petty-cash')->name('pencairan-petty-cash.')->group(function () {
            Route::post('/{id}/proses', [\App\Http\Controllers\Admin\DepositoController::class, 'pencairanPettyCashProses'])->name('proses');
            Route::post('/{id}/serahkan', [\App\Http\Controllers\Admin\PettyCashController::class, 'pencairanDepositoCash'])->name('serahkan');
        });

        // ── Peringatan Jatuh Tempo (Dashboard Owner) ──
        Route::prefix('peringatan')->name('peringatan.')->group(function () {
            Route::get('/', [\App\Http\Controllers\Admin\DepositoController::class, 'peringatanIndex'])->name('index');
            Route::post('/{id}/update', [\App\Http\Controllers\Admin\DepositoController::class, 'updatePersiapanCair'])->name('update');
            Route::post('/{id}/send-dana', [\App\Http\Controllers\Admin\DepositoController::class, 'sendDanaPersiapan'])->name('send-dana');
        });
    });

    // Gadai Baru Routes
    Route::prefix('gadai_baru')->name('gadai_baru.')->group(function () {
        Route::get('/', [\App\Http\Controllers\AdminGadaiBaruController::class, 'index'])->name('index');
        Route::get('/storage', [\App\Http\Controllers\AdminGadaiBaruController::class, 'storage'])->name('storage');
        Route::post('/storage/empty-auction', [\App\Http\Controllers\AdminGadaiBaruController::class, 'emptyAuction'])->name('storage.empty-auction');
        Route::get('/create', [\App\Http\Controllers\AdminGadaiBaruController::class, 'create'])->name('create');
        Route::post('/store', [\App\Http\Controllers\AdminGadaiBaruController::class, 'store'])->name('store');
        Route::get('/{id}', [\App\Http\Controllers\AdminGadaiBaruController::class, 'detail'])->name('detail');
        Route::post('/{id}/ambil', [\App\Http\Controllers\AdminGadaiBaruController::class, 'ambilBarang'])->name('ambil');
        Route::post('/{id}/perpanjang', [\App\Http\Controllers\GadaiBaruActionController::class, 'perpanjang'])->name('perpanjang');
        Route::post('/{id}/lunas', [\App\Http\Controllers\GadaiBaruActionController::class, 'lunas'])->name('lunas');
        Route::post('/{id}/lelang', [\App\Http\Controllers\GadaiBaruActionController::class, 'lelang'])->name('lelang');

        // Struk Gadai Detail Routes
        Route::prefix('struk-detail')->name('struk-detail.')->group(function () {
            Route::get('/{id}/awal', [\App\Http\Controllers\Admin\StrukController::class, 'gadaiAwal'])->name('awal');
            Route::get('/{id}/awal-b5', [\App\Http\Controllers\Admin\StrukController::class, 'gadaiAwalB5'])->name('awal-b5');
            Route::get('/{id}/syarat', [\App\Http\Controllers\Admin\StrukController::class, 'gadaiSyarat'])->name('syarat');
            Route::get('/{id}/loker', [\App\Http\Controllers\Admin\StrukController::class, 'gadaiLoker'])->name('loker');
            Route::get('/{id}/perpanjangan/{pengajuan_id}', [\App\Http\Controllers\Admin\StrukController::class, 'gadaiPerpanjangan'])->name('perpanjangan');
            Route::get('/{id}/selesai-cash', [\App\Http\Controllers\Admin\StrukController::class, 'gadaiSelesaiCash'])->name('selesai-cash');
            Route::get('/{id}/selesai-tf/{pengajuan_id}', [\App\Http\Controllers\Admin\StrukController::class, 'gadaiSelesaiTf'])->name('selesai-tf');
            Route::get('/{id}/pengembalian', [\App\Http\Controllers\Admin\StrukController::class, 'gadaiPengembalian'])->name('pengembalian');
        });

        // Pengajuan Nasabah (Antrean Verifikasi)
        Route::get('/pengajuan/list', [\App\Http\Controllers\Admin\AdminPengajuanGadaiController::class, 'index'])->name('pengajuan.index');
        Route::post('/pengajuan/{id}/approve', [\App\Http\Controllers\Admin\AdminPengajuanGadaiController::class, 'approve'])->name('pengajuan.approve');
        Route::post('/pengajuan/{id}/reject', [\App\Http\Controllers\Admin\AdminPengajuanGadaiController::class, 'reject'])->name('pengajuan.reject');
    });

    Route::get('/gadai', function () { return view('admin.gadai.index'); })->name('gadai.index');
    Route::get('/janji-temu-universal', [\App\Http\Controllers\Admin\JanjiTemuController::class, 'index'])->name('janji-temu.index');
    Route::post('/janji-temu/tabungan/{id}/cancel', [\App\Http\Controllers\Admin\JanjiTemuController::class, 'cancelTabungan'])->name('janji-temu.cancel-tabungan');
    Route::post('/janji-temu/pinjaman/{id}/cancel', [\App\Http\Controllers\Admin\JanjiTemuController::class, 'cancelPinjaman'])->name('janji-temu.cancel-pinjaman');
    // Route::get('/nasabah', function () { return view('admin.nasabah.index'); })->name('nasabah.index'); // DEPRECATED - Use NasabahManagementController
    // Halaman Pengajuan dihapus - redirect ke dashboard bila ada yang akses langsung
    Route::get('/pengajuan', fn () => redirect()->route('admin.dashboard', [], 301))->name('pengajuan.index');

    // Log Aktivitas - hanya Admin Utama yang bisa akses
    Route::prefix('activity-log')->name('activity-log.')->middleware('admin.utama')->group(function () {
        Route::get('/nasabah', [\App\Http\Controllers\Admin\ActivityLogController::class, 'nasabah'])->name('nasabah');
        Route::get('/admin-operasional', [\App\Http\Controllers\Admin\ActivityLogController::class, 'adminOperasional'])->name('admin-operasional');
    });
});

// ============================================
// TEST ROUTE - Remove in production
// ===========================================
Route::get('/test-whatsapp', function () {
    $whatsAppService = app(\App\Services\WhatsAppService::class);
    
    try {
        echo "<h1>Test Fonnte WhatsApp API</h1>";
        
        // Test connection first
        echo "<h2>1. Test Connection</h2>";
        $connectionTest = $whatsAppService->testConnection();
        echo "<pre>";
        print_r($connectionTest);
        echo "</pre>";
        
        // Test send OTP
        $testPhone = '089512543086'; // Nomor dari screenshot
        $testOTP = '123456';
        
        echo "<h2>2. Test Send OTP</h2>";
        echo "<p>Sending to: <strong>$testPhone</strong></p>";
        echo "<p>OTP Code: <strong>$testOTP</strong></p>";
        
        $result = $whatsAppService->sendOTP($testPhone, $testOTP);
        
        echo "<h3>Result:</h3>";
        echo "<pre>";
        print_r($result);
        echo "</pre>";
        
        if ($result['success']) {
            echo "<p style='color: green; font-weight: bold; font-size: 20px;'>✅ SUCCESS! Check WhatsApp!</p>";
        } else {
            echo "<p style='color: red; font-weight: bold; font-size: 20px;'>❌ FAILED: " . $result['message'] . "</p>";
        }
        
        // Show config
        echo "<h2>3. Configuration</h2>";
        echo "<pre>";
        echo "API Key: " . (config('services.fonnte.api_key') ? substr(config('services.fonnte.api_key'), 0, 15) . '...' : 'NOT SET') . "\n";
        echo "API URL: " . config('services.fonnte.api_url') . "\n";
        echo "Sender: " . config('services.fonnte.sender_number') . "\n";
        echo "</pre>";
        
        // Check Laravel Log
        echo "<h2>4. Check Logs</h2>";
        echo "<p>Check <code>storage/logs/laravel.log</code> for detailed API response</p>";
        
    } catch (\Exception $e) {
        echo "<h2 style='color: red;'>Exception</h2>";
        echo "<p style='color: red;'>" . $e->getMessage() . "</p>";
        echo "<pre>" . $e->getTraceAsString() . "</pre>";
    }
});
Route::post('/fonnte-webhook', [\App\Http\Controllers\FonnteWebhookController::class, 'handle']);
