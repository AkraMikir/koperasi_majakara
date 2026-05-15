<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class GadaiPengajuan extends Model
{
    use HasFactory;

    protected $table = 'tbl_gadai_pengajuan';

    protected $fillable = [
        'nasabah_id',
        'gadai_active_id',
        'jenis_pengajuan',
        'metode',
        'nominal',
        'tgl_janji_temu',
        'bukti_transfer',
        'keterangan',
        'admin_keterangan',
        'status',
        'admin_id',
        'processed_at'
    ];

    protected $casts = [
        'nominal' => 'decimal:2',
        'tgl_janji_temu' => 'datetime',
        'processed_at' => 'datetime',
    ];

    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class);
    }

    public function gadaiActive(): BelongsTo
    {
        return $this->belongsTo(GadaiActive::class, 'gadai_active_id');
    }

    public function admin(): BelongsTo
    {
        return $this->belongsTo(User::class, 'admin_id');
    }

    public function files()
    {
        return $this->hasMany(GadaiFile::class, 'pengajuan_id');
    }
}
