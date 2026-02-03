<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JanjiTemuUniversal extends Model
{
    use HasFactory;

    protected $table = 'v_janji_temu_universal';
    public $incrementing = false;
    protected $keyType = 'string';
    protected $primaryKey = 'id_view'; // Dummy UUID

    // Karena ini View, disable timestamps management
    public $timestamps = false;
    
    // Read only
    protected $guarded = ['*'];

    protected $casts = [
        'tanggal_janji_temu' => 'date',
        'created_at' => 'datetime',
        'nominal' => 'decimal:2',
    ];
}
