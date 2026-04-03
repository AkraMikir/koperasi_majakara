<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('trans_tabungan', function (Blueprint $table) {
            // Referensi ke petty_cash_transaksi_nasabah
            $table->string('petty_cash_ref', 30)->nullable()->after('id_janji_temu_tabungan');

            // Flag apakah transaksi ini bagian dari sistem petty cash
            $table->tinyInteger('is_petty_cash')->default(0)->after('petty_cash_ref');

            // Admin yang memegang cash nasabah ini (untuk kebutuhan penarikan ke admin yg sama)
            $table->foreignId('admin_pengelola_id')
                  ->nullable()
                  ->after('is_petty_cash')
                  ->constrained('users')
                  ->onDelete('set null');

            $table->index('petty_cash_ref');
            $table->index('admin_pengelola_id');
            $table->index('is_petty_cash');
        });
    }

    public function down(): void
    {
        Schema::table('trans_tabungan', function (Blueprint $table) {
            $table->dropForeign(['admin_pengelola_id']);
            $table->dropIndex(['petty_cash_ref']);
            $table->dropIndex(['admin_pengelola_id']);
            $table->dropIndex(['is_petty_cash']);
            $table->dropColumn(['petty_cash_ref', 'is_petty_cash', 'admin_pengelola_id']);
        });
    }
};
