<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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
        'jenis',            // ✅ NEW
        'nominal',
        'tanggal_janji_temu',
        'waktu_janji_temu',
        'keterangan',
        'keterangan_admin',   // ✅ NEW
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

    /**
     * Transaksi tabungan yang dibuat dari janji temu ini (jika sudah diproses).
     * Dipakai untuk menampilkan nominal yang benar (dari transaksi) setelah admin edit saat proses.
     */
    public function transTabungan(): HasOne
    {
        return $this->hasOne(TransTabungan::class, 'id_janji_temu_tabungan', 'id');
    }

    /**
     * Get bukti foto untuk janji temu (dari tbl_bukti_foto).
     */
    public function buktiFoto()
    {
        return $this->hasMany(BuktiFoto::class, 'owner_id', 'id')
            ->where('owner_fitur', 'T')
            ->where('owner_trans', 'JNJT');
    }
}
