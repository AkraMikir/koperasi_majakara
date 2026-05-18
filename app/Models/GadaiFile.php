<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GadaiFile extends Model
{
    use HasFactory;

    protected $table = 'tbl_gadai_files';

    protected $fillable = [
        'gadai_active_id',
        'pengajuan_id',
        'path_file',
        'tipe_foto'
    ];

    public function gadaiActive()
    {
        return $this->belongsTo(GadaiActive::class, 'gadai_active_id');
    }

    public function pengajuan()
    {
        return $this->belongsTo(GadaiPengajuan::class, 'pengajuan_id');
    }
}
