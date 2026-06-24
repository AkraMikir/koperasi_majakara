<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SettingsStruk extends Model
{
    use HasFactory;

    protected $table = 'settings_struk';

    protected $fillable = [
        'nama_koperasi',
        'alamat_koperasi',
        'no_telp',
        'email',
        'website',
        'nama_pt',
        'format_no_struk',
        'syarat_ketentuan_gadai',
        'extra_nilai_kehilangan',
        'syarat_ketentuan_pinjaman',
        'info_box_pinjaman',
    ];

    protected $casts = [
        'extra_nilai_kehilangan' => 'decimal:2',
    ];

    /**
     * Get settings pertama (atau create default jika belum ada)
     */
    public static function getSettings()
    {
        $settings = self::first();
        
        $defaultSyaratPinjaman = "Yang bertanda tangan dibawah ini:\nBekasi, {tanggal}\nPetugas bagian kredit bertindak untuk dan atas nama MAJAKARA dengan nasabah membuat perjanjian sebagai berikut:\n\n1. Saya bersedia memberikan informasi data pribadi dan kontak darurat kepada PIHAK MAJAKARA.\n2. Nasabah wajib menyimpan Surat Bukti Pinjam MAJAKARA.\n3. Saya bersedia dan tidak ada TUNTUTAN DALAM BENTUK APAPUN, baik secara PIDANA/PERDATA kepada Pihak MAJAKARA, jika saya LALAI/tidak melakukan pembayaran sampai Tanggal Jatuh Tempo Saya Bersedia disita barang saya senilai pinjaman dan bunga oleh pihak MAJAKARA.\n4. Bunga dan biaya administrasi mengikuti ketentuan yang berlaku.\n5. Pihak Majakara berhak menolak barang yang tidak memenuhi syarat.\n6. Segala bentuk wanprestasi akan diselesaikan sesuai hukum yang berlaku.";
        $defaultInfoBoxPinjaman = "PINJAMAN BISA DIANGSUR\nHARI BESAR DAN HARI MINGGU TETAP BUKA\nJam Pengambilan Barang: 08.00 - 18.00\nBuka Jam: 08.00 - 20.00";

        if (!$settings) {
            $settings = self::create([
                'nama_koperasi' => 'Koperasi Majakara',
                'alamat_koperasi' => 'Jl. Contoh No. 123, Jakarta',
                'no_telp' => '021-12345678',
                'email' => 'info@koperaimajakara.com',
                'website' => 'https://koperaimajakara.com',
                'nama_pt' => 'PT Majakara Sejahtera',
                'format_no_struk' => 'STRK-{YYYYMMDD}-{XXXX}',
                'syarat_ketentuan_gadai' => "1. Barang diambil maksimal 30 hari setelah jatuh tempo\n2. Denda keterlambatan: sesuai rate_denda kategori\n3. Barang tidak diambil = lelang\n4. Struk hilang: biaya cetak ulang Rp " . number_format(50000, 0, ',', '.'),
                'extra_nilai_kehilangan' => 50000,
                'syarat_ketentuan_pinjaman' => $defaultSyaratPinjaman,
                'info_box_pinjaman' => $defaultInfoBoxPinjaman,
            ]);
        } else {
            $needsUpdate = false;
            if (is_null($settings->syarat_ketentuan_pinjaman)) {
                $settings->syarat_ketentuan_pinjaman = $defaultSyaratPinjaman;
                $needsUpdate = true;
            }
            if (is_null($settings->info_box_pinjaman)) {
                $settings->info_box_pinjaman = $defaultInfoBoxPinjaman;
                $needsUpdate = true;
            }
            if ($needsUpdate) {
                $settings->save();
            }
        }
        
        return $settings;
    }

    /**
     * Generate nomor struk sesuai format
     */
    public static function generateNoStruk($fitur = 'STR')
    {
        $settings = self::getSettings();
        $format = $settings->format_no_struk ?? 'STRK-{YYYYMMDD}-{XXXX}';
        
        $date = now()->format('Ymd');
        $random = str_pad(rand(0, 9999), 4, '0', STR_PAD_LEFT);
        
        return str_replace(
            ['{YYYYMMDD}', '{XXXX}'],
            [$date, $random],
            $format
        );
    }
}
