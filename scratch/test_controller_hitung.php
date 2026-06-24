<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Http\Controllers\Admin\PajakBungaController;
use Illuminate\Http\Request;

$controller = new PajakBungaController();
$request = Request::create('/admin/bunga/pajak/hitung', 'GET', [
    'jenis' => 'pph_deposito',
    'bulan' => 6,
    'tahun' => 2026
]);

try {
    $response = $controller->hitung($request);
    echo "STATUS: " . $response->getStatusCode() . "\n";
    echo "CONTENT: " . $response->getContent() . "\n";
} catch (\Exception $e) {
    echo "ERROR: " . $e->getMessage() . "\n" . $e->getTraceAsString() . "\n";
}
