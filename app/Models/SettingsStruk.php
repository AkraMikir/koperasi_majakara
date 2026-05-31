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
            ]);
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
