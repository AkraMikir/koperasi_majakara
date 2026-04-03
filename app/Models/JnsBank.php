<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JnsBank extends Model
{
    protected $table = 'jns_bank';

    protected $fillable = [
        'pemilik',
        'nama',
        'no_rek',
        'bank',
        'cabang',
        'kode_bank',
        'status',
        'logo',
    ];

    /**
     * The accessors to append to the model's array form.
     */
    protected $appends = ['logo_url'];

    /**
     * Get the logo URL, falling back to LogoBank master table if empty.
     */
    public function getLogoUrlAttribute()
    {
        // 1. Jika logo di tabel jns_bank ada isinya, gunakan itu
        if ($this->logo) {
            return $this->logo;
        }

        // 2. Jika tidak, cari di tabel logo_bank berdasarkan nama bank
        // Menggunakan cache atau static untuk efisiensi jika perlu, tapi sementara ambil langsung
        $masterLogo = LogoBank::where('nama_bank', $this->bank)->first();
        if ($masterLogo) {
            return $masterLogo->logo_url;
        }

        // 3. Fallback: UI Avatars dengan inisial nama bank
        return 'https://ui-avatars.com/api/?name=' . urlencode($this->bank) . '&color=674c1d&background=fff6e5&font-size=0.5&bold=true';
    }
}
