<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        Schema::table('trans_tabungan', function (Blueprint $table) {
            $table->string('metode_bayar', 30)->nullable()->after('admin_pengelola_id');
        });
    }

    public function down()
    {
        Schema::table('trans_tabungan', function (Blueprint $table) {
            $table->dropColumn('metode_bayar');
        });
    }
};
