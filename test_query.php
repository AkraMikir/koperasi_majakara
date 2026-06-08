<?php

require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$n = App\Models\Nasabah::with('user')->first();
echo "Nasabah user name: " . ($n->user ? $n->user->name : 'No user') . "\n";
