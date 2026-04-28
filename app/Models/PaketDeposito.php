<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PaketDeposito extends Model
{
    protected $table = 'paket_depositos';

    protected $fillable = [
        'nama_paket',
        'tenor_bulan',
        'suku_bunga',
        'minimal_nominal',
        'maksimal_nominal',
        'status',
        'kategori_id',
        'keterangan',
    ];

    public function kategori()
    {
        return $this->belongsTo(KategoriDeposito::class, 'kategori_id');
    }
}
