<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class JnsLokasiPerusahaan extends Model
{
    use HasFactory;

    protected $table = 'jns_lokasi_perusahaan';

    protected $fillable = [
        'nama_lokasi',
        'alamat_lengkap',
        'google_maps_embed',
        'kota',
        'provinsi',
        'tipe_lokasi',
        'status_aktif',
    ];

    protected $casts = [
        'status_aktif' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];
}



