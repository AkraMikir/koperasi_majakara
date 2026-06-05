<?php
require __DIR__.'/../vendor/autoload.php';
$app = require_once __DIR__.'/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Auth;

$user = User::where('email', 'test.lock@email.com')->first();
Auth::login($user);

// Mock route for CLI compatibility
request()->setRouteResolver(function () {
    $route = new \Illuminate\Routing\Route('GET', '/nasabah/profile', []);
    $route->action['as'] = 'nasabah.profile';
    return $route;
});

try {
    $html = view('nasabah.profile', [
        'nasabah' => $user->nasabah,
        'saldoTabungan' => 0,
        'pendingRequests' => collect()
    ])->render();
    
    file_put_contents(__DIR__ . '/output.html', $html);
    echo "RENDER SUCCESS\n";
    echo "kontak-darurat-section: " . (strpos($html, 'kontak-darurat-section') !== false ? 'FOUND' : 'NOT FOUND') . "\n";
} catch (\Throwable $e) {
    echo "RENDER ERROR: " . $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
