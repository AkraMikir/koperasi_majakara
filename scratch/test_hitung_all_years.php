<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Admin\PajakBungaController;
use Illuminate\Http\Request;

$controller = new PajakBungaController();

echo "Testing hitung pph_gadai for multiple years and all months:\n";
foreach ([2023, 2024, 2025, 2026, 2027] as $y) {
    for ($m = 1; $m <= 12; $m++) {
        $request = Request::create('/admin/bunga/pajak/hitung', 'GET', [
            'jenis' => 'pph_gadai',
            'bulan' => $m,
            'tahun' => $y
        ]);

        try {
            $response = $controller->hitung($request);
            if ($response->getStatusCode() !== 200) {
                echo "Year $y Month $m: Status=" . $response->getStatusCode() . " Content=" . $response->getContent() . "\n";
            }
        } catch (\Exception $e) {
            echo "Year $y Month $m: ERROR - " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
        }
    }
}
echo "Done testing all years/months.\n";
