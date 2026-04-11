<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Carbon\Carbon;

class JanjiTemuPinjaman extends Model
{
    use HasFactory;

    protected $table = 'tbl_janji_temu_pinjaman';

    protected $fillable = [
        'id',
        'id_pengajuan',
        'id_nasabah',
        'lokasi_temu',
        'nominal',
        'tanggal_janji_temu',
        'waktu_janji_temu',
        'keterangan',
        'keterangan_admin',
        'status',
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tanggal_janji_temu' => 'datetime',
        'waktu_janji_temu' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Unified Status logic for Admin & Nasabah
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

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanPinjaman::class, 'id_pengajuan');
    }

    public function lokasi(): BelongsTo
    {
        return $this->belongsTo(JnsLokasiPerusahaan::class, 'lokasi_temu');
    }

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah');
    }

    public function buktiFoto(): HasMany
    {
        return $this->hasMany(BuktiFoto::class, 'owner_id', 'id');
    }
}
