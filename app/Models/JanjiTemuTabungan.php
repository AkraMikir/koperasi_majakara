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
        'id',
        'id_nasabah',
        'lokasi_temu',
        'nominal',
        'tanggal_janji_temu',
        'waktu_janji_temu',
        'keterangan',
        'status',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_janji_temu' => 'date',  // DATE column
        // waktu_janji_temu is TIMESTAMP, leave as string for manual parsing
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
