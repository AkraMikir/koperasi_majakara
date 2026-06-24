<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\GadaiPaymentLog;

$payments = GadaiPaymentLog::with('gadaiActive')
    ->whereMonth('created_at', 6)
    ->whereYear('created_at', 2026)
    ->get();

echo "Found " . $payments->count() . " payments in June 2026:\n";
foreach ($payments as $i => $payment) {
    echo "[$i] ID: " . $payment->id . "\n";
    echo "    Jenis Pembayaran: " . $payment->jenis_pembayaran . "\n";
    echo "    Nominal: " . $payment->nominal . "\n";
    echo "    Gadai Active ID: " . $payment->gadai_active_id . "\n";
    $gadai = $payment->gadaiActive;
    if ($gadai) {
        echo "    Gadai Active Found: ID=" . $gadai->id . ", Nominal Deal=" . $gadai->nominal_deal . ", Status=" . $gadai->status . "\n";
    } else {
        echo "    Gadai Active NOT FOUND (null)\n";
    }
}
