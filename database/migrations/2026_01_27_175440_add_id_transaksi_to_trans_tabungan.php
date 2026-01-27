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
        // Check if columns don't exist before adding
        if (!Schema::hasColumn('trans_tabungan', 'id_transaksi')) {
            Schema::table('trans_tabungan', function (Blueprint $table) {
                $table->string('id_transaksi', 50)->nullable()->after('id');
            });
        }

        if (!Schema::hasColumn('trans_tabungan', 'id_jns_akun')) {
            Schema::table('trans_tabungan', function (Blueprint $table) {
                $table->foreignId('id_jns_akun')->nullable()->after('id_anggota')->constrained('jns_akun')->nullOnDelete();
            });
        }

        // Populate id_transaksi for existing records that don't have it
        $transaksi = \App\Models\TransTabungan::whereNull('id_transaksi')->orWhere('id_transaksi', '')->get();
        foreach ($transaksi as $index => $trans) {
            $date = $trans->created_at->format('Ymd');
            $seq = str_pad($index + 1, 3, '0', STR_PAD_LEFT);
            $trans->id_transaksi = "{$date}-{$seq}-TAB";
            $trans->save();
        }

        // Add unique constraint if not exists
        try {
            Schema::table('trans_tabungan', function (Blueprint $table) {
                $table->string('id_transaksi', 50)->unique()->change();
            });
        } catch (\Exception $e) {
            // Unique constraint might already exist
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('trans_tabungan', function (Blueprint $table) {
            $table->dropForeign(['id_jns_akun']);
            $table->dropColumn(['id_transaksi', 'id_jns_akun']);
        });
    }
};
