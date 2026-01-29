<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JnsAkun extends Model
{
    use HasFactory;

    protected $table = 'jns_akun';

    protected $fillable = [
        'kode_akun',
        'nama_akun',
        'deskripsi',
        'prefix_id',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get transaksi tabungan for this jenis akun
     */
    public function transaksiTabungan()
    {
        return $this->hasMany(TransTabungan::class, 'id_jns_akun');
    }
}
