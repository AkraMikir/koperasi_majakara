<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\GadaiActive;

echo "All rows in tbl_gadai_active:\n";
foreach (GadaiActive::all() as $g) {
    echo "ID: {$g->id}, status: {$g->status}, nominal_deal: {$g->nominal_deal}\n";
}
