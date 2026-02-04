<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class PengajuanPinjaman extends Model
{
    use HasFactory;

    protected $table = 'tbl_pengajuan_pinjaman';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'id',
        'id_anggota',
        'tgl_pengajuan',
        'nominal',
        'jenis',
        'durasi',
        'jenis_pencairan',
        'status',
        'keterangan',
        'keterangan_admin',
        'tgl_cair',
        'bunga_persen',
    ];

    // ID digenerate di controller via IdGenerator (format: DDMMYYYY + SEQ + P + TF/TN + PNJ)

    protected $casts = [
        'tgl_pengajuan' => 'datetime',
        'tgl_cair' => 'datetime',
        'nominal' => 'decimal:2',
        'bunga_persen' => 'decimal:2',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'id_anggota');
    }

    public function pinjaman(): HasOne
    {
        return $this->hasOne(PinjamanH::class, 'id_pengajuan');
    }

    public function janjiTemu(): HasOne
    {
        return $this->hasOne(JanjiTemuPinjaman::class, 'id_pengajuan');
    }

    public function buktiFoto()
    {
        return $this->hasMany(BuktiFoto::class, 'owner_id', 'id');
    }
}



