<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GadaiGridGold extends Model
{
    use HasFactory;

    protected $table = 'tbl_gadai_grid_gold';

    protected $fillable = [
        'kode_slot',
        'baris',
        'kolom',
        'is_occupied',
        'active_gadai_id'
    ];

    public function activeGadai()
    {
        return $this->belongsTo(GadaiActive::class, 'active_gadai_id');
    }
}
