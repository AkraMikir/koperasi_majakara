<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class BuktiFotoTabungan extends Model
{
    use HasFactory;

    protected $table = 'tbl_bukti_foto_tabungan';

    protected $fillable = [
        'id_pengajuan',
        'file_photo',
        'jenis',
    ];

    protected $casts = [
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    public function pengajuan(): BelongsTo
    {
        return $this->belongsTo(PengajuanTabungan::class, 'id_pengajuan');
    }
}



