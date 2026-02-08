<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();

use Illuminate\Support\Facades\Schema;

$tables = [
    'tbl_janji_temu_tabungan',
    'tbl_janji_temu_pinjaman',
    'tbl_janji_temu_pembayaran_pinjaman'
];

foreach ($tables as $table) {
    echo "TABLE: $table\n";
    if (Schema::hasTable($table)) {
        $columns = Schema::getColumnListing($table);
        echo implode(', ', $columns) . "\n\n";
    } else {
        echo "Table not found!\n\n";
    }
}
