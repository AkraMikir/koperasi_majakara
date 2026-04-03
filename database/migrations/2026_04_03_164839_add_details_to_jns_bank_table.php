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
        Schema::table('jns_bank', function (Blueprint $table) {
            $table->string('cabang')->after('no_rek')->nullable()->comment('Kantor cabang bank');
            $table->string('kode_bank')->after('bank')->nullable()->comment('Kode transfer bank');
            $table->string('status')->default('aktif')->after('bank')->comment('Status keaktifan rekening');
            $table->string('logo')->nullable()->after('status')->comment('Path logo bank');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('jns_bank', function (Blueprint $table) {
            $table->dropColumn(['cabang', 'kode_bank', 'status', 'logo']);
        });
    }
};
