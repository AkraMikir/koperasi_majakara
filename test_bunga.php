<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$pinjaman_aktif = \App\Models\PinjamanH::whereHas('pengajuan', function($q) { $q->whereIn('status', ['3', '4']); })->where(function($q) { $q->where('lunas', '!=', 'lunas')->orWhereNull('lunas'); })->count();
$gadai_aktif = \App\Models\GadaiActive::whereIn('status', ['active', 'extended', 'expired_grace', 'expired_final'])->count();
$pinjaman_h = \App\Models\PinjamanH::count();
$pengajuan_pinjaman = \App\Models\PengajuanPinjaman::count();
$bunga_rp = \App\Models\PinjamanH::whereHas('pengajuan', function($q) { $q->whereIn('status', ['3', '4']); })->where(function($q) { $q->where('lunas', '!=', 'lunas')->orWhereNull('lunas'); })->sum('bunga_rp');

echo json_encode([
    'pinjaman_aktif' => $pinjaman_aktif,
    'gadai_aktif' => $gadai_aktif,
    'pinjaman_h' => $pinjaman_h,
    'pengajuan_pinjaman' => $pengajuan_pinjaman,
    'bunga_rp' => $bunga_rp
]);
