<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('jns_bank', function (Blueprint $table) {
            $table->id();
            $table->string('pemilik', 100)->comment('Yang punya rekening: admin, bang farhan, dll');
            $table->string('nama', 100)->comment('Nama bank / rekening');
            $table->string('no_rek', 30)->comment('Nomor rekening');
            $table->string('bank', 50)->comment('BCA, Mandiri, BNI, BRI, dll');
            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('jns_bank');
    }
};
