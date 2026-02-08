<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PengajuanPenarikanTabungan extends Model
{
    use HasFactory;

    protected $table = 'tbl_pengajuan_penarikan_tabungan';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_anggota',
        'tgl_pengajuan',
        'nominal',
        'metode_transfer',
        'no_rekening',
        'nama_bank',
        'foto_bukti_tf_admin',
        'lokasi_temu',
        'tanggal_janji_temu',
        'waktu_janji_temu',
        'keterangan',
        'keterangan_admin',
        'status',
    ];

    protected $casts = [
        'tgl_pengajuan' => 'datetime',
        'nominal' => 'decimal:2',
        'tanggal_janji_temu' => 'date',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_anggota');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(JnsLokasiPerusahaan::class, 'lokasi_temu');
    }

    public function transTabungan(): HasMany
    {
        return $this->hasMany(TransTabungan::class, 'id_pengajuan_tarik');
    }
}



