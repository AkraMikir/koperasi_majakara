<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\DepositoH;
use App\Models\Setting;
use Carbon\Carbon;

$depositoAktifList = DepositoH::with(['persiapanCair', 'tenor'])->where('status', 'aktif')->get();
$totalKotor = 0;
$totalPajak = 0;

foreach ($depositoAktifList as $depo) {
    $persiapan = $depo->persiapanCair->last();

    if ($persiapan) {
        $bungaKotor = (float) $persiapan->bunga_kotor;
        $pajak      = (float) $persiapan->pajak;
    } else {
        $pokok        = (float) $depo->nominal_awal;
        $bungaTahunan = (float) $depo->bunga;

        $tenorHari = 30;
        if ($depo->tenor) {
            $tenorHari = (int) $depo->tenor->tenor_hari;
        } elseif ($depo->tgl_mulai && $depo->tgl_jatuh_tempo) {
            $tenorHari = (int) $depo->tgl_mulai->diffInDays($depo->tgl_jatuh_tempo);
        }

        $tahunJT = $depo->tgl_jatuh_tempo ? $depo->tgl_jatuh_tempo->year : Carbon::now()->year;
        $isLeap  = ($tahunJT % 4 === 0 && $tahunJT % 100 !== 0) || ($tahunJT % 400 === 0);
        $pembagi = $isLeap ? 366 : 365;

        if ($pokok > 0 && $bungaTahunan > 0 && $tenorHari > 0) {
            $bungaKotor = $pokok * $bungaTahunan * ($tenorHari / $pembagi);
            $taxRate    = (float) (Setting::where('key', 'pajak_deposito')->value('value') ?? 0.20);
            $pajak      = $bungaKotor * $taxRate;
        } else {
            $bungaKotor = 0;
            $pajak      = 0;
        }
    }
    $totalKotor += $bungaKotor;
    $totalPajak += $pajak;
}

echo "TOTAL KOTOR: " . $totalKotor . "\n";
echo "TOTAL PAJAK: " . $totalPajak . "\n";
echo "TOTAL BERSIH: " . ($totalKotor - $totalPajak) . "\n";
