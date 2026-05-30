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
        Schema::table('tbl_gadai_active', function (Blueprint $table) {
            $table->decimal('extra_pinjaman_nominal', 15, 2)
                  ->default(0)
                  ->after('denda_aktif');

            $table->text('extra_pinjaman_reason')->nullable()
                  ->after('extra_pinjaman_nominal');

            $table->unsignedBigInteger('extra_pinjaman_admin_id')->nullable()
                  ->after('extra_pinjaman_reason');

            $table->foreign('extra_pinjaman_admin_id')
                  ->references('id')
                  ->on('users')
                  ->onDelete('set null');

            $table->dateTime('extra_pinjaman_set_at')->nullable()
                  ->after('extra_pinjaman_admin_id');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_gadai_active', function (Blueprint $table) {
            $table->dropForeign(['extra_pinjaman_admin_id']);
            $table->dropColumn([
                'extra_pinjaman_nominal',
                'extra_pinjaman_reason',
                'extra_pinjaman_admin_id',
                'extra_pinjaman_set_at',
            ]);
        });
    }
};
