<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('tbl_deposito_h', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_deposito_h', 'status_peringatan')) {
                $table->enum('status_peringatan', ['tidak_perlu', 'tentatif', 'need_prepare'])
                      ->default('tidak_perlu')
                      ->after('status');
            }
            if (!Schema::hasColumn('tbl_deposito_h', 'tgl_peringatan')) {
                $table->date('tgl_peringatan')->nullable()->after('status_peringatan');
            }
        });
    }

    public function down(): void
    {
        Schema::table('tbl_deposito_h', function (Blueprint $table) {
            $table->dropColumn(['status_peringatan', 'tgl_peringatan']);
        });
    }
};
