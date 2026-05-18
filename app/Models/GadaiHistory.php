<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class GadaiHistory extends Model
{
    use HasFactory;

    protected $table = 'tbl_gadai_history';

    protected $fillable = [
        'gadai_active_id',
        'aksi',
        'catatan'
    ];

    public function gadaiActive()
    {
        return $this->belongsTo(GadaiActive::class, 'gadai_active_id');
    }
}
