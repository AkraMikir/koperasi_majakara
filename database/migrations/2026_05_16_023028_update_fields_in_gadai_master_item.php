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
        Schema::table('tbl_gadai_master_item', function (Blueprint $table) {
            if (Schema::hasColumn('tbl_gadai_master_item', 'nama_item')) {
                $table->renameColumn('nama_item', 'head_1');
            }
            if (Schema::hasColumn('tbl_gadai_master_item', 'foto_item')) {
                $table->renameColumn('foto_item', 'file_pic');
            }
        });

        Schema::table('tbl_gadai_master_item', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_gadai_master_item', 'head_2')) {
                $table->string('head_2')->nullable()->after('head_1');
            }
        });

        Schema::table('tbl_gadai_master_item', function (Blueprint $table) {
            if (!Schema::hasColumn('tbl_gadai_master_item', 'nominal_real')) {
                $table->decimal('nominal_real', 15, 2)->default(0)->after('head_2');
            }
            if (!Schema::hasColumn('tbl_gadai_master_item', 'bunga_low')) {
                $table->decimal('bunga_low', 5, 2)->default(0)->after('nominal_real');
            }
            if (!Schema::hasColumn('tbl_gadai_master_item', 'nominal_low')) {
                $table->decimal('nominal_low', 15, 2)->default(0)->after('bunga_low');
            }
            if (!Schema::hasColumn('tbl_gadai_master_item', 'bunga_high')) {
                $table->decimal('bunga_high', 5, 2)->default(0)->after('nominal_low');
            }
            if (!Schema::hasColumn('tbl_gadai_master_item', 'nominal_high')) {
                $table->decimal('nominal_high', 15, 2)->default(0)->after('bunga_high');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('tbl_gadai_master_item', function (Blueprint $table) {
            $table->renameColumn('head_1', 'nama_item');
            $table->renameColumn('file_pic', 'foto_item');
            $table->dropColumn(['head_2', 'nominal_real', 'bunga_low', 'nominal_low', 'bunga_high', 'nominal_high']);
        });
    }
};
