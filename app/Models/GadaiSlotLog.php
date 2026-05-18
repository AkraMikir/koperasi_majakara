<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GadaiSlotLog extends Model
{
    use HasFactory;

    protected $table = 'tbl_gadai_slot_log';

    protected $fillable = [
        'slot_kode',
        'kategori',
        'aksi',
        'gadai_active_id'
    ];
}
