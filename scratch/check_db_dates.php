<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\TempoPinjamanB;
use App\Models\TempoPinjamanM;
use App\Models\GadaiPaymentLog;
use App\Models\DepositoPersiapanCair;
use App\Models\PinjamanH;

echo "TempoPinjamanB tgl_bayar range:\n";
$b = TempoPinjamanB::where('status_bayar', 'lunas')->selectRaw('min(tgl_bayar) as min_tgl, max(tgl_bayar) as max_tgl, count(*) as cnt')->first();
print_r($b->toArray());

echo "\nTempoPinjamanM tgl_bayar range:\n";
$m = TempoPinjamanM::where('status_bayar', 'lunas')->selectRaw('min(tgl_bayar) as min_tgl, max(tgl_bayar) as max_tgl, count(*) as cnt')->first();
print_r($m->toArray());

echo "\nGadaiPaymentLog created_at range:\n";
$g = GadaiPaymentLog::selectRaw('min(created_at) as min_tgl, max(created_at) as max_tgl, count(*) as cnt')->first();
print_r($g->toArray());

echo "\nDepositoPersiapanCair updated_at range with status selesai:\n";
$d = DepositoPersiapanCair::where('status', 'selesai')->selectRaw('min(updated_at) as min_tgl, max(updated_at) as max_tgl, count(*) as cnt')->first();
print_r($d->toArray());

echo "\nTotal PinjamanH:\n";
echo PinjamanH::count() . "\n";
