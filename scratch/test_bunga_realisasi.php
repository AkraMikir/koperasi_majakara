<?php
// Load Laravel
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Carbon\Carbon;
use App\Models\PinjamanH;
use App\Models\DepositoH;
use App\Models\GadaiActive;
use App\Models\DepositoPersiapanCair;
use App\Models\GadaiPaymentLog;
use App\Models\TempoPinjamanB;
use App\Models\TempoPinjamanM;

$currentMonth = 5;
$currentYear = 2026;

echo "Current Month: $currentMonth, Year: $currentYear\n\n";

// 1. Realisasi Pinjaman
$angsuranBulanan = TempoPinjamanB::with('pinjaman')
    ->whereHas('pinjaman.pengajuan', function($q) {
        $q->whereIn('status', ['3', '4']);
    })
    ->where('status_bayar', 'lunas')
    ->whereMonth('tgl_bayar', $currentMonth)
    ->whereYear('tgl_bayar', $currentYear)
    ->get();

$pinjamanRealisasi = 0;
foreach ($angsuranBulanan as $angsuran) {
    $pinjaman = $angsuran->pinjaman;
    if ($pinjaman && $pinjaman->lama_pinjam > 0) {
        $pinjamanRealisasi += ($pinjaman->bunga_rp / $pinjaman->lama_pinjam);
    }
}

$angsuranMingguan = TempoPinjamanM::with('pinjaman')
    ->whereHas('pinjaman.pengajuan', function($q) {
        $q->whereIn('status', ['3', '4']);
    })
    ->where('status_bayar', 'lunas')
    ->whereMonth('tgl_bayar', $currentMonth)
    ->whereYear('tgl_bayar', $currentYear)
    ->get();

foreach ($angsuranMingguan as $angsuran) {
    $pinjaman = $angsuran->pinjaman;
    if ($pinjaman && $pinjaman->lama_pinjam > 0) {
        $pinjamanRealisasi += ($pinjaman->bunga_rp / $pinjaman->lama_pinjam);
    }
}

echo "1. Realisasi Pinjaman: Rp " . number_format($pinjamanRealisasi, 2) . "\n";

// 2. Realisasi Gadai
$payments = GadaiPaymentLog::with('gadaiActive')
    ->whereMonth('created_at', $currentMonth)
    ->whereYear('created_at', $currentYear)
    ->get();
    
$gadaiRealisasi = 0;
foreach ($payments as $payment) {
    if ($payment->jenis_pembayaran === 'tebus') {
        $gadai = $payment->gadaiActive;
        $bunga = $payment->nominal - ($gadai ? $gadai->nominal_deal : 0);
        $gadaiRealisasi += max(0, $bunga); 
    } else {
        $gadaiRealisasi += $payment->nominal;
    }
}

echo "2. Realisasi Gadai: Rp " . number_format($gadaiRealisasi, 2) . "\n";

// 3. Realisasi Deposito (Pencairan selesai)
$depositoRealisasi = DepositoPersiapanCair::where('status', 'selesai')
    ->whereMonth('updated_at', $currentMonth)
    ->whereYear('updated_at', $currentYear)
    ->sum('bunga_bersih');

echo "3. Realisasi Deposito: Rp " . number_format($depositoRealisasi, 2) . "\n";
echo "Done.\n";
