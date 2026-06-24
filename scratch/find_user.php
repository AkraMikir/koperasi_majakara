<?php
require __DIR__."/vendor/autoload.php";
$app = require_once __DIR__."/bootstrap/app.php";
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$user = Illuminate\Support\Facades\DB::table('users')->where('email', 'ridhoirianosudarma@gmail.com')->first();
if ($user) {
    echo "Found user:\n";
    print_r($user);
} else {
    echo "User not found in users table.\n";
}
