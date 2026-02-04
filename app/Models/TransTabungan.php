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
        'id_janji_temu_tabungan',
        'id_pengajuan_tarik',
        'id_anggota',
        'id_jns_via',
        'id_jns_transaksi',
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

    protected $appends = ['jenis', 'via'];

    /**
     * Jenis transaksi untuk tampilan: setoran | penarikan (dari jns_transaksi.kode).
     */
    public function getJenisAttribute(): ?string
    {
        $kode = $this->jnsTransaksi?->kode;
        if ($kode === 'STR') {
            return 'setoran';
        }
        if ($kode === 'PNR') {
            return 'penarikan';
        }
        return null;
    }

    /**
     * Via transaksi untuk tampilan (dari jns_via.nama).
     */
    public function getViaAttribute(): ?string
    {
        return $this->jnsVia?->nama;
    }

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

    public function janjiTemuTabungan(): BelongsTo
    {
        return $this->belongsTo(JanjiTemuTabungan::class, 'id_janji_temu_tabungan');
    }

    // New Relationships to Master Tables
    // TransTabungan tidak perlu jnsFitur karena fiturnya pasti Tabungan
    
    public function jnsVia(): BelongsTo
    {
        return $this->belongsTo(JnsVia::class, 'id_jns_via');
    }
    
    public function jnsTransaksi(): BelongsTo
    {
        return $this->belongsTo(JnsTransaksi::class, 'id_jns_transaksi');
    }
}



