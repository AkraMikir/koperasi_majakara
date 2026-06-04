<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();
$depositoAktifList = App\Models\DepositoH::with(["nasabah.user", "persiapanCair"])->where("status", "aktif")->get();
foreach($depositoAktifList as $depo) {
    $persiapan = $depo->persiapanCair->last();
    $depo->bunga_kotor_rp = $persiapan ? $persiapan->bunga_kotor : 0;
}
$listDeposito = $depositoAktifList->sortByDesc("bunga_kotor_rp")->take(10);
echo "Count listDeposito: " . count($listDeposito) . "\n";
echo "Count original: " . count($depositoAktifList) . "\n";

