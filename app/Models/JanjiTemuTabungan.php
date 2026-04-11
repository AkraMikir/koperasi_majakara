<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Carbon\Carbon;

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
        'metode_bayar',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_janji_temu' => 'date',  // DATE column
        // waktu_janji_temu is TIMESTAMP, leave as string for manual parsing
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the status display label and CSS class.
     * Logic: Admin Success (2) > Admin Cancelled (3) > Past (Timeout) > Upcoming (Default)
     */
    public function getStatusDisplayAttribute(): array
    {
        $dateTime = Carbon::parse($this->tanggal_janji_temu);
        if ($this->waktu_janji_temu) {
            $time = Carbon::parse($this->waktu_janji_temu);
            $dateTime->setTime($time->hour, $time->minute);
        }

        if ($this->status == '2') {
            return [
                'label' => 'Terlaksana',
                'class' => 'bg-green-100 text-green-800'
            ];
        }
        
        if ($this->status == '3') {
            return [
                'label' => 'Dibatalkan',
                'class' => 'bg-red-100 text-red-800'
            ];
        }

        if ($dateTime->isPast()) {
            return [
                'label' => 'Terlewat',
                'class' => 'bg-gray-100 text-gray-800'
            ];
        }

        return [
            'label' => 'Akan Datang',
            'class' => 'bg-blue-100 text-blue-800'
        ];
    }

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
