<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Admin\PajakBungaController;
use Illuminate\Http\Request;

$controller = new PajakBungaController();

echo "Testing hitung pph_gadai for all months of 2026:\n";
for ($m = 1; $m <= 12; $m++) {
    $request = Request::create('/admin/bunga/pajak/hitung', 'GET', [
        'jenis' => 'pph_gadai',
        'bulan' => $m,
        'tahun' => 2026
    ]);

    try {
        $response = $controller->hitung($request);
        echo "Month $m: Status=" . $response->getStatusCode() . " Content=" . $response->getContent() . "\n";
    } catch (\Exception $e) {
        echo "Month $m: ERROR - " . $e->getMessage() . "\n";
    }
}
