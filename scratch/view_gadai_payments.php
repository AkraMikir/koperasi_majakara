<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\GadaiPaymentLog;

echo "All rows in tbl_gadai_payment_log:\n";
foreach (GadaiPaymentLog::all() as $p) {
    echo "ID: {$p->id}, gadai_active_id: {$p->gadai_active_id}, jenis_pembayaran: {$p->jenis_pembayaran}, nominal: {$p->nominal}, created_at: {$p->created_at}\n";
}
