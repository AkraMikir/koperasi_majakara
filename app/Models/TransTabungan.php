<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransTabungan extends Model
{
    use HasFactory;

    protected $table = 'trans_tabungan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_pengajuan_setor',
        'id_pengajuan_tarik',
        'id_anggota',
        'id_via',
        'id_jns_trans',
        'nominal',
        'keterangan',
        'tgl_transaksi',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tgl_transaksi' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    protected static function boot()
    {
        parent::boot();
        // ID generated manual
    }

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_anggota');
    }

    public function pengajuanSetor(): BelongsTo
    {
        return $this->belongsTo(PengajuanTabungan::class, 'id_pengajuan_setor');
    }

    public function pengajuanTarik(): BelongsTo
    {
        return $this->belongsTo(PengajuanPenarikanTabungan::class, 'id_pengajuan_tarik');
    }

    // New Relationships to Master Tables
    // TransTabungan tidak perlu jnsFitur karena fiturnya pasti Tabungan
    
    public function jnsVia(): BelongsTo
    {
        return $this->belongsTo(JnsVia::class, 'id_via');
    }
    
    public function jnsTransaksi(): BelongsTo
    {
        return $this->belongsTo(JnsTransaksi::class, 'id_jns_trans');
    }
}



