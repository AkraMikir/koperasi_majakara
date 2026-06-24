<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\GadaiPaymentLog;

$bulan = 6;
$tahun = 2026;

try {
    $payments = GadaiPaymentLog::with('gadaiActive')
        ->whereMonth('created_at', $bulan)
        ->whereYear('created_at', $tahun)
        ->get();

    echo "Count of payments in June 2026: " . $payments->count() . "\n";

    $total = 0;
    foreach ($payments as $payment) {
        if ($payment->jenis_pembayaran === 'tebus') {
            $gadai = $payment->gadaiActive;
            $bunga = $payment->nominal - ($gadai ? $gadai->nominal_deal : 0);
            $total += max(0, $bunga);
            echo "Tebus payment: nominal=" . $payment->nominal . ", deal=" . ($gadai ? $gadai->nominal_deal : 'null') . ", bunga=" . $bunga . "\n";
        } else {
            $total += $payment->nominal;
            echo "Non-tebus payment (" . $payment->jenis_pembayaran . "): nominal=" . $payment->nominal . "\n";
        }
    }
    echo "Total: " . $total . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
