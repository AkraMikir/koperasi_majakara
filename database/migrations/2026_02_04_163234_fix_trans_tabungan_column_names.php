<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('trans_tabungan', function (Blueprint $table) {
            // Rename id_jns_trans to id_jns_transaksi
            $table->renameColumn('id_jns_trans', 'id_jns_transaksi');
            
            // Rename id_via to id_jns_via
            $table->renameColumn('id_via', 'id_jns_via');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trans_tabungan', function (Blueprint $table) {
            // Revert back to original names
            $table->renameColumn('id_jns_transaksi', 'id_jns_trans');
            $table->renameColumn('id_jns_via', 'id_via');
        });
    }
};
