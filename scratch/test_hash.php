<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use Illuminate\Support\Facades\Hash;

$raw = '123456';
$hashed = Hash::make($raw);

echo "Raw: $raw\n";
echo "Hashed: $hashed\n";

$user = new User();
// Test setting raw PIN
$user->pin = $raw;
echo "User PIN after setting raw: " . $user->pin . "\n";

// Test setting already hashed PIN
$user->pin = $hashed;
echo "User PIN after setting hashed: " . $user->pin . "\n";

if (Hash::check($raw, $user->pin)) {
    echo "Check raw against model value: SUCCESS\n";
} else {
    echo "Check raw against model value: FAILED (Double Hashed!)\n";
}
