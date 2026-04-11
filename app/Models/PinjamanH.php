<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

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
        return $this->hasMany(TempoPinjamanB::class, 'pinjaman_id');
    }

    public function tempoBulanan(): HasMany
    {
        return $this->hasMany(TempoPinjamanB::class, 'pinjaman_id');
    }

    public function tempoMingguan(): HasMany
    {
        return $this->hasMany(TempoPinjamanM::class, 'pinjaman_id');
    }

    public function buktiFoto()
    {
        return $this->hasMany(BuktiFoto::class, 'owner_id', 'id');
    }

    public function pettyCashTransaksi(): \Illuminate\Database\Eloquent\Relations\HasOne
    {
        return $this->hasOne(PettyCashTransaksiNasabah::class, 'petty_cash_ref', 'id');
    }

    /**
     * Get displayable status for the loan based on its pengajuan status.
     * Status 3: Disetujui (Approved but not yet disbursed)
     * Status 4: Dicairkan (Disbursed)
     */
    public function getStatusDisplayAttribute(): array
    {
        $status = optional($this->pengajuan)->status ?? '3';
        
        $config = [
            '3' => [
                'label' => 'Disetujui',
                'class' => 'bg-blue-100 text-blue-700',
            ],
            '4' => [
                'label' => 'Dicairkan',
                'class' => 'bg-green-100 text-green-700',
            ],
            // Fallback
            'default' => [
                'label' => 'Aktif',
                'class' => 'bg-gray-100 text-gray-700',
            ]
        ];

        return $config[$status] ?? $config['default'];
    }

    /**
     * Get payment progress string (e.g. "2 / 12").
     */
    public function getPaymentProgressAttribute(): string
    {
        $total = $this->lama_pinjam ?? 0;
        $lunas = 0;

        if ($this->jenis === 'bulanan') {
            $lunas = $this->tempoBulanan()->where(function($q) {
                $q->where('status_bayar', 'lunas')
                  ->orWhere('jumlah_terbayar', '>=', DB::raw('jumlah_tagihan'));
            })->count();
        } else {
            $lunas = $this->tempoMingguan()->where(function($q) {
                $q->where('status_bayar', 'lunas')
                  ->orWhere('jumlah_terbayar', '>=', DB::raw('jumlah_tagihan'));
            })->count();
        }

        return "{$lunas} / {$total}";
    }
}
