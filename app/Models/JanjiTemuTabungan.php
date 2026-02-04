<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JanjiTemuTabungan extends Model
{
    use HasFactory;

    protected $table = 'tbl_janji_temu_tabungan';
    
    // ✅ ID is now string (generated)
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',                    // ✅ NEW
        'id_nasabah',
        'lokasi_temu',
        'nominal',
        'tanggal_janji_temu',
        'waktu_janji_temu',
        'keterangan',
        'keterangan_admin',      // ✅ NEW
        'status',                // ✅ NEW
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_janji_temu' => 'datetime',
        'waktu_janji_temu' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(JnsLokasiPerusahaan::class, 'lokasi_temu');
    }
    
    // ✅ REMOVED: pengajuan() relation - no longer needed
    // ✅ REMOVED: transTabungan() relation - no longer needed
}
