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

$output = "";
foreach ($tables as $table) {
    $output .= "TABLE: $table\n";
    if (Schema::hasTable($table)) {
        $columns = Schema::getColumnListing($table);
        $output .= implode(', ', $columns) . "\n\n";
    } else {
        $output .= "Table not found!\n\n";
    }
}
file_put_contents('schema_dump.txt', $output);
echo "Dumped to schema_dump.txt\n";
