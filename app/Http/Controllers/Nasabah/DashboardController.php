<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the nasabah dashboard (Frontend Only).
     */
    public function index()
    {
        // Dummy data for frontend preview
        $dummyUser = (object) [
            'id' => 1,
            'nama' => 'Ahmad Rizki',
            'email' => 'ahmad.rizki@example.com',
            'nomor_hp' => '081234567890',
            'foto' => null,
            'role' => 'nasabah',
        ];
        
        $dummyNasabah = (object) [
            'pekerjaanTemp' => (object) [
                'pekerjaan' => 'Karyawan Swasta',
                'penghasilan' => 5000000,
                'nama_perusahaan' => 'PT Contoh Indonesia',
            ],
            'dataRekTemp' => (object) [
                'no_rekening' => '1234567890123456',
                'nama_pemilik_rekening' => 'Ahmad Rizki',
                'jenis_atm' => 'BCA',
            ],
        ];
        
        return view('nasabah.dashboard', [
            'user' => $dummyUser,
            'dummyNasabah' => $dummyNasabah,
        ]);
    }
}
