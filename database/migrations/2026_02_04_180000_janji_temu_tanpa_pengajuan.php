<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Janji temu tabungan bisa dibuat tanpa pengajuan (langsung ke tbl_janji_temu_tabungan).
     * id_pengajuan dibuat nullable; trans_tabungan bisa link ke janji temu via id_janji_temu_tabungan.
     */
    public function up(): void
    {
        // Drop FK id_pengajuan (nama constraint bisa beda per environment)
        $fk = DB::selectOne("
            SELECT CONSTRAINT_NAME as name FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_janji_temu_tabungan'
            AND COLUMN_NAME = 'id_pengajuan' AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        if ($fk && !empty($fk->name)) {
            DB::statement("ALTER TABLE tbl_janji_temu_tabungan DROP FOREIGN KEY `" . $fk->name . "`");
        }
        Schema::table('tbl_janji_temu_tabungan', function (Blueprint $table) {
            $table->string('id_pengajuan', 30)->nullable()->change();
        });
        Schema::table('tbl_janji_temu_tabungan', function (Blueprint $table) {
            $table->foreign('id_pengajuan')->references('id')->on('tbl_pengajuan_tabungan')->onDelete('set null');
        });

        Schema::table('trans_tabungan', function (Blueprint $table) {
            $table->unsignedBigInteger('id_janji_temu_tabungan')->nullable()->after('id_pengajuan_setor');
            $table->foreign('id_janji_temu_tabungan')->references('id')->on('tbl_janji_temu_tabungan')->onDelete('set null');
        });
    }

    public function down(): void
    {
        Schema::table('trans_tabungan', function (Blueprint $table) {
            $table->dropForeign(['id_janji_temu_tabungan']);
            $table->dropColumn('id_janji_temu_tabungan');
        });

        $fk = DB::selectOne("
            SELECT CONSTRAINT_NAME as name FROM information_schema.KEY_COLUMN_USAGE
            WHERE TABLE_SCHEMA = DATABASE() AND TABLE_NAME = 'tbl_janji_temu_tabungan'
            AND COLUMN_NAME = 'id_pengajuan' AND REFERENCED_TABLE_NAME IS NOT NULL
        ");
        if ($fk && !empty($fk->name)) {
            DB::statement("ALTER TABLE tbl_janji_temu_tabungan DROP FOREIGN KEY `" . $fk->name . "`");
        }
        Schema::table('tbl_janji_temu_tabungan', function (Blueprint $table) {
            $table->string('id_pengajuan', 30)->nullable(false)->change();
        });
        Schema::table('tbl_janji_temu_tabungan', function (Blueprint $table) {
            $table->foreign('id_pengajuan')->references('id')->on('tbl_pengajuan_tabungan')->onDelete('cascade');
        });
    }
};
