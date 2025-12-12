<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class GadaiH extends Model
{
    use HasFactory;

    protected $table = 'tbl_gadai_h';

    protected $fillable = [
        'id_pengajuan',
        'id_nasabah',
        'id_item_gadai',
        'nomor_gadai',
        'jumlah_pinjaman',
        'bunga',
        'bunga_rp',
        'tgl_mulai',
        'tgl_jatuh_tempo',
        'status',
        'metode_pencairan',
    ];

    protected $casts = [
        'jumlah_pinjaman' => 'decimal:2',
        'bunga' => 'decimal:4',
        'bunga_rp' => 'decimal:2',
        'tgl_mulai' => 'datetime',
        'tgl_jatuh_tempo' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_nasabah');
    }

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanGadai::class, 'id_pengajuan');
    }

    public function itemGadai(): BelongsTo
    {
        return $this->belongsTo(ItemGadai::class, 'id_item_gadai');
    }

    public function tempo(): HasMany
    {
        return $this->hasMany(TempoGadai::class, 'gadai_id');
    }

    public function transGadai(): HasMany
    {
        return $this->hasMany(TransGadai::class, 'gadai_id');
    }

    public function lelang(): HasOne
    {
        return $this->hasOne(LelangGadai::class, 'gadai_id');
    }

    public function janjiTemu(): HasOne
    {
        return $this->hasOne(JanjiTemuGadai::class, 'gadai_id');
    }
}



