<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Carbon\Carbon;

class PajakBungaPayment extends Model
{
    protected $table = 'pajak_bunga_payments';

    protected $fillable = [
        'jenis_pajak',
        'periode_bulan',
        'periode_tahun',
        'jumlah_kotor',
        'tarif_persen',
        'jumlah_pajak',
        'jumlah_bersih',
        'tanggal_bayar',
        'keterangan',
        'bukti_bayar',
        'status',
        'dibuat_oleh',
    ];

    protected $casts = [
        'jumlah_kotor'  => 'decimal:2',
        'tarif_persen'  => 'decimal:2',
        'jumlah_pajak'  => 'decimal:2',
        'jumlah_bersih' => 'decimal:2',
        'tanggal_bayar' => 'date',
    ];

    // Accessors
    public function getJenisLabelAttribute(): string
    {
        return match ($this->jenis_pajak) {
            'pph_pinjaman' => 'PPh Pinjaman',
            'pph_gadai'    => 'PPh Gadai',
            'pph_deposito' => 'PPh Deposito',
            default        => ucfirst($this->jenis_pajak),
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            'belum_bayar' => 'Belum Dibayar',
            'sudah_bayar' => 'Sudah Dibayar',
            default       => $this->status,
        };
    }

    public function getPeriodeLabelAttribute(): string
    {
        $bulan = Carbon::createFromDate($this->periode_tahun, $this->periode_bulan, 1)
            ->translatedFormat('F Y');
        return $bulan;
    }

    public function getTarifColorAttribute(): string
    {
        return match ($this->jenis_pajak) {
            'pph_deposito' => 'red',
            default        => 'amber',
        };
    }

    // Relationships
    public function dibuatOleh(): BelongsTo
    {
        return $this->belongsTo(User::class, 'dibuat_oleh');
    }
}
