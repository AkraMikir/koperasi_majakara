<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$controller = new App\Http\Controllers\Admin\StrukController();
$response = $controller->gadaiAwalB5(1);
$pdfContent = $response->getFile()->getPathname(); // if it's a file download response, or if it's a direct response
file_put_contents('struk_test.pdf', $response->getContent() ?: file_get_contents($pdfContent));
echo "PDF successfully saved to struk_test.pdf!\n";
