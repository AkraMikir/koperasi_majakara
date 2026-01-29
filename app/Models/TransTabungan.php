<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class TransTabungan extends Model
{
    use HasFactory;

    protected $table = 'trans_tabungan';

    protected $fillable = [
        'id_transaksi',
        'id_pengajuan_setor',
        'id_pengajuan_tarik',
        'id_anggota',
        'id_jns_akun',
        'nominal',
        'keterangan',
        'jenis',
        'via',
        'tgl_transaksi',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tgl_transaksi' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

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

    public function jnsAkun(): BelongsTo
    {
        return $this->belongsTo(JnsAkun::class, 'id_jns_akun');
    }

    /**
     * Generate unique transaction ID with format: YYYYMMDD-SEQ-TYPE
     * Example: 20260128-001-TAB
     */
    public static function generateIdTransaksi($jnsAkunPrefix = 'TAB')
    {
        $date = now()->format('Ymd'); // 20260128
        
        // Count today's transactions
        $count = self::whereDate('created_at', now())->count() + 1;
        
        // Pad sequence with zeros (001, 002, etc)
        $seq = str_pad($count, 3, '0', STR_PAD_LEFT);
        
        return "{$date}-{$seq}-{$jnsAkunPrefix}";
    }
}



