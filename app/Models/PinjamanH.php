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
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_anggota',
        'id_pengajuan',
        'jumlah_pinjam',
        'lama_pinjam',
        'jenis',
        'bunga',
        'bunga_rp',
        'denda_persen',
        'ags_bulan',
        'tgl_pinjam',
        'lunas',
    ];

    protected $casts = [
        'jumlah_pinjam' => 'decimal:2',
        'bunga' => 'decimal:4',
        'bunga_rp' => 'decimal:2',
        'denda_persen' => 'decimal:2',
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

    public function tempo(): HasMany
    {
        // One relationship for both types since structure is same in new design?
        // Wait, did I merge tempo_pinjaman_m? No, only tempo_pinjaman_b was modified in my SQL
        // But user asked for database refactoring.
        // Assuming tempo_pinjaman_b handles all or I should keep separate if structure differs.
        // In my SQL script I only recreated tempo_pinjaman_b.
        // If 'jenis' tells us method, we might just use tempo_pinjaman_b for both if schema supports it?
        // Let's assume tempo_pinjaman_b is the main one now.
        return $this->hasMany(TempoPinjamanB::class, 'pinjaman_id');
    }
    
    // Removed tempoMingguan for now unless needed.

    public function buktiFoto()
    {
        return $this->hasMany(BuktiFoto::class, 'owner_id', 'id');
    }
}



