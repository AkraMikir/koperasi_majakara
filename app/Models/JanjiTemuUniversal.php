<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

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

    /**
     * Unified Status logic for Admin & Nasabah
     */
    public function getStatusDisplayAttribute(): array
    {
        $dateTime = Carbon::parse($this->tanggal_janji_temu);
        if ($this->waktu_janji_temu) {
            $time = Carbon::parse($this->waktu_janji_temu);
            $dateTime->setTime($time->hour, $time->minute);
        }

        if ($this->status == '2') {
            return [
                'label' => 'Terlaksana',
                'class' => 'bg-green-100 text-green-800'
            ];
        }
        
        if ($this->status == '3') {
            return [
                'label' => 'Dibatalkan',
                'class' => 'bg-red-100 text-red-800'
            ];
        }

        if ($dateTime->isPast()) {
            return [
                'label' => 'Terlewat',
                'class' => 'bg-gray-100 text-gray-800'
            ];
        }

        return [
            'label' => 'Akan Datang',
            'class' => 'bg-blue-100 text-blue-800'
        ];
    }
}
