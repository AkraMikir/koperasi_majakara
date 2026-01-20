<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class PinjamanH extends Model
{
    use HasFactory;

    protected $table = 'tbl_pinjaman_h';

    protected $fillable = [
        'id_anggota',
        'id_pengajuan',
        'jumlah_pinjam',
        'lama_pinjam',
        'jenis',
        'bunga',
        'bunga_rp',
        'denda_persen',
        'ags_bulan',
        'ags_minggu',
        'tgl_pinjam',
        'saldo_lebih',
        'foto_bukti_transfer',
        'foto_serah_terima',
        'status',
        'lunas',
    ];

    protected $casts = [
        'jumlah_pinjam' => 'decimal:2',
        'bunga' => 'decimal:4',
        'bunga_rp' => 'decimal:2',
        'denda_persen' => 'decimal:2',
        'saldo_lebih' => 'decimal:2',
        'tgl_pinjam' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_anggota');
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanPinjaman::class, 'id_pengajuan');
    }

    public function tempoBulanan(): HasMany
    {
        return $this->hasMany(TempoPinjamanB::class, 'pinjaman_id');
    }

    public function tempoMingguan(): HasMany
    {
        return $this->hasMany(TempoPinjamanM::class, 'pinjaman_id');
    }

    public function buktiFoto(): HasMany
    {
        return $this->hasMany(BuktiFotoPinjaman::class, 'id_pinjaman');
    }
}



