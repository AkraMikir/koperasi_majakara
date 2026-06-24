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
        Schema::create('tbl_syarat_ketentuan_layanan', function (Blueprint $table) {
            $table->id();
            $table->longText('konten');
            $table->timestamps();
        });

        // Seed default T&C Layanan (dari dokumen syarat & ketentuan panjang)
        $html = '<h3>KETENTUAN PENGGUNAAN LAYANAN MAJAKARAPINJAM</h3>
<p><b>1. KETENTUAN UMUM</b></p>
<p>1.1. Kecuali didefinisikan secara khusus dalam Ketentuan Penggunaan ini, istilah-istilah dalam huruf besar yang digunakan dalam Ketentuan Penggunaan ini harus ditafsirkan sesuai dengan istilah yang ada di dalam Ketentuan Penggunaan <b>Koperasi Majakara</b>.</p>
<p>1.2. Sebelum menggunakan layanan <b>MajakaraPinjam</b>, Anda wajib menyetujui Ketentuan Penggunaan ini dan melakukan pendaftaran melalui Platform.</p>
<p>1.3. Pada saat Anda:<br>
a. melakukan pendaftaran pertama kali sebagai pengguna layanan <b>MajakaraPinjam</b> atau calon Penerima Pinjaman;<br>
b. melakukan permohonan pencairan Pinjaman;<br>
c. menyetujui atau menandatangani Perjanjian Pinjaman;<br>
Anda wajib membaca, mengerti, memahami dan memeriksa Ketentuan Penggunaan dengan seksama dan Anda wajib mengikuti proses dan langkah-langkah otentikasi dan verifikasi yang berlaku.</p>
<p>1.4. <b>MajakaraPinjam</b> (termasuk setiap penyebutan (branding) lainnya sebagaimana diberitahukan dari waktu ke waktu) merupakan suatu layanan dalam Platform, Website, dan/atau Mitra Platform Resmi yang merupakan pemberian fasilitas pinjaman dana tunai yang disediakan oleh Pemberi Pinjaman kepada Penerima Pinjaman melalui <b>Koperasi Majakara</b> sebagai Penyelenggara.</p>
<p>1.5. Ketentuan-ketentuan yang diatur pada Ketentuan Penggunaan ini berlaku umum terhadap penggunaan layanan <b>MajakaraPinjam</b>, dengan ketentuan bahwa terhadap masing-masing Pinjaman akan tunduk pada syarat dan ketentuan yang dan diatur lebih lanjut dalam Perjanjian Pinjaman.</p>
<p>1.6. Dana Pinjaman yang disalurkan kepada Anda selaku Penerima Pinjaman adalah <b>sepenuhnya berasal dari dan dimiliki</b> oleh Pemberi Pinjaman yang terdaftar pada Koperasi Majakara. Koperasi Majakara <b>hanya memfasilitasi pemberian pinjaman dari Pemberi Pinjaman dengan cara meneruskan</b> dana Pinjaman tersebut kepada Anda selaku Penerima Pinjaman. Hubungan hukum yang timbul terkait Pinjaman adalah antara <b>Anda selaku Penerima Pinjaman dan Pemberi Pinjaman</b> dan segala risiko ditanggung sepenuhnya oleh Anda.</p>
<p>1.7. Persetujuan Anda atas Ketentuan Penggunaan ini akan dianggap sebagai dan merupakan permohonan perolehan layanan <b>MajakaraPinjam</b>, dan dalam hal disetujui berdasarkan penilaian oleh Koperasi Majakara dan/atau Pemberi Pinjaman, persetujuan atas permohonan Anda ditandai dengan perolehan Limit Pinjaman <b>MajakaraPinjam (“Fasilitas Pinjaman”)</b> yang dapat dicairkan sebagai pinjaman dana tunai (<b>“Pinjaman”</b>).</p>
<p>1.8. Anda mengakui, memahami dan menyetujui bahwa Koperasi Majakara (untuk kepentingan dan atas nama Pemberi Pinjaman) berhak untuk melakukan pemeriksaan, verifikasi, evaluasi, penilaian dan menentukan (menyetujui atau menolak) permohonan perolehan Fasilitas Pinjaman dan pencairan Pinjaman yang Anda ajukan.</p>
<p>1.9. Anda mengakui dan menyetujui bahwa Pemberi Pinjaman dapat memindahkan atau mengalihkan setiap Pinjaman yang masih terhutang dan Tagihan kepada pihak lain termasuk namun tidak terbatas pada bank, lembaga keuangan bukan bank atau institusi keuangan lainnya dengan tunduk pada ketentuan Hukum yang Berlaku...</p>
<p>1.10. Anda memahami bahwa kegagalan terhadap kewajiban pembayaran atau pelunasan Pinjaman oleh Penerima Pinjaman dapat berdampak pada (i) dilakukannya kegiatan penagihan oleh Koperasi Majakara, Pemberi Pinjaman atau pihak lain, kepada Penerima Pinjaman; (ii) dilaporkannya Penerima Pinjaman Pusat Pelaporan dan Analisis Transaksi Keuangan (“PPATK”); dan (iii) catatan atau peringkat kelayakan kredit Penerima Pinjaman pada Koperasi Majakara dan/atau Pemberi Pinjaman.</p>';

        DB::table('tbl_syarat_ketentuan_layanan')->insert([
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
        Schema::dropIfExists('tbl_syarat_ketentuan_layanan');
    }
};
