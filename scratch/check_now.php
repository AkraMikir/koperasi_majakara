<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Carbon\Carbon;
echo "Now: " . Carbon::now()->format('Y-m-d H:i:s') . "\n";
echo "Month: " . Carbon::now()->month . "\n";
echo "Year: " . Carbon::now()->year . "\n";
