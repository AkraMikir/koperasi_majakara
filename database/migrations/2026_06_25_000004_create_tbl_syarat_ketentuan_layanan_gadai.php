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
        Schema::create('tbl_syarat_ketentuan_layanan_gadai', function (Blueprint $table) {
            $table->id();
            $table->longText('konten');
            $table->timestamps();
        });

        // Seed default T&C Layanan Gadai
        $html = '<h3>KETENTUAN PENGGUNAAN LAYANAN MAJAKARAGADAI</h3>
<p><b>1. KETENTUAN UMUM</b></p>
<p>1.1. Layanan <b>MajakaraGadai</b> (termasuk setiap penyebutan (branding) lainnya sebagaimana diberitahukan dari waktu ke waktu) merupakan suatu layanan dalam Platform, Website, dan/atau Mitra Platform Resmi yang merupakan pemberian fasilitas pinjaman dana dengan jaminan berupa barang berharga (emas, elektronik, kendaraan, dll) yang diserahkan oleh Nasabah kepada Koperasi Majakara selaku Penerima Gadai.</p>
<p>1.2. Sebelum menggunakan layanan <b>MajakaraGadai</b>, Anda wajib menyetujui Ketentuan Penggunaan ini secara sadar dan sukarela.</p>
<p>1.3. Penaksiran nilai barang jaminan dilakukan oleh tim penilai Koperasi Majakara secara independen, objektif, dan profesional berdasarkan kondisi fisik, fungsi, kelengkapan, serta harga pasar wajar yang berlaku pada saat penaksiran dilakukan.</p>
<p>1.4. Koperasi Majakara berhak untuk menyetujui atau menolak permohonan gadai setelah dilakukan proses penaksiran fisik barang jaminan di kantor cabang Koperasi Majakara.</p>
<p>1.5. Hubungan hukum yang timbul terkait transaksi gadai adalah antara Anda selaku Pemberi Gadai (Nasabah) dan Koperasi Majakara selaku Penerima Gadai, yang diatur secara rinci dalam Surat Bukti Gadai (SBG).</p>
<p>1.6. Koperasi Majakara berkewajiban menjaga, merawat, dan menyimpan barang jaminan dengan aman di tempat penyimpanan (safety box / loker / gudang) yang layak selama masa kontrak gadai berlangsung.</p>
<p>1.7. Jangka waktu gadai (masa gadai) adalah 30 hari kalender, atau jangka waktu lain yang disepakati secara tertulis, dengan hak perpanjangan gadai setelah nasabah melunasi biaya jasa sewa modal berjalan.</p>
<p>1.8. Biaya jasa sewa modal (bunga gadai) dihitung berdasarkan persentase tertentu dari nilai pinjaman deal per tenor, ditambah biaya inap kendaraan/barang (bila ada) sesuai ketentuan tarif yang berlaku.</p>
<p>1.9. Apabila Nasabah tidak melakukan penebusan atau perpanjangan gadai hingga melewati tanggal jatuh tempo dan masa tenggang (grace period) berakhir, maka Koperasi Majakara berhak secara hukum untuk mengeksekusi barang jaminan tersebut melalui mekanisme penjualan langsung atau lelang guna melunasi sisa kewajiban pinjaman Nasabah.</p>';

        DB::table('tbl_syarat_ketentuan_layanan_gadai')->insert([
            'konten' => $html,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('tbl_syarat_ketentuan_layanan_gadai');
    }
};
