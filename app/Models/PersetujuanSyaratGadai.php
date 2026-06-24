<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PersetujuanSyaratGadai extends Model
{
    use HasFactory;

    protected $table = 'tbl_persetujuan_syarat_gadai';

    protected $fillable = [
        'nasabah_id',
        'agreed_at',
    ];

    protected $casts = [
        'agreed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the nasabah that agreed to the terms.
     */
    public function nasabah(): BelongsTo
    {
        return $this->belongsTo(Nasabah::class, 'nasabah_id');
    }
}
