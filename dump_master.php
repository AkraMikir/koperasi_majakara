<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "JNS_TRANSAKSI:\n";
foreach (DB::table('jns_transaksi')->get() as $row) {
    echo "ID: {$row->id}, Kode: {$row->kode}, Nama: {$row->nama}\n";
}

echo "\nJNS_VIA:\n";
foreach (DB::table('jns_via')->get() as $row) {
    echo "ID: {$row->id}, Kode: {$row->kode}, Nama: {$row->nama}\n";
}

echo "\nJNS_FITUR:\n";
foreach (DB::table('jns_fitur')->get() as $row) {
    echo "ID: {$row->id}, Kode: {$row->kode}, Nama: {$row->nama}\n";
}
