<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('settings_struk', function (Blueprint $table) {
            $table->id();
            
            // Header Settings
            $table->string('nama_koperasi')->nullable();
            $table->text('alamat_koperasi')->nullable();
            $table->string('no_telp')->nullable();
            $table->string('email')->nullable();
            $table->string('website')->nullable();
            $table->string('nama_pt')->nullable();
            $table->string('format_no_struk')->default('STRK-{YYYYMMDD}-{XXXX}');
            
            // Gadai Settings
            $table->text('syarat_ketentuan_gadai')->nullable();
            $table->decimal('extra_nilai_kehilangan', 15, 2)->default(0);
            $table->integer('slot_gadai')->default(0);
            $table->decimal('biaya_denda', 15, 2)->default(0);
            $table->decimal('bunga_admin_gadai', 5, 2)->default(0);
            
            $table->timestamps();
        });

        // Insert default values
        DB::table('settings_struk')->insert([
            'nama_koperasi' => 'Koperasi Majakara',
            'alamat_koperasi' => 'Jl. Contoh No. 123, Jakarta',
            'no_telp' => '021-12345678',
            'email' => 'info@koperaimajakara.com',
            'website' => 'https://koperaimajakara.com',
            'nama_pt' => 'PT Majakara Sejahtera',
            'format_no_struk' => 'STRK-{YYYYMMDD}-{XXXX}',
            'syarat_ketentuan_gadai' => "1. Barang diambil maksimal 30 hari setelah jatuh tempo\n2. Denda keterlambatan: 0.3%/hari\n3. Barang tidak diambil = lelang",
            'extra_nilai_kehilangan' => 50000,
            'slot_gadai' => 100,
            'biaya_denda' => 10000,
            'bunga_admin_gadai' => 2.5,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('settings_struk');
    }
};
