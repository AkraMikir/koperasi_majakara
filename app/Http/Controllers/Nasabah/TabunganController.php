<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class TabunganController extends Controller
{
    /**
     * Show the tabungan dashboard.
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

        // Dummy data for tabungan info
        $tabunganInfo = (object) [
            'saldo' => 5000000,
            'bunga' => 3.5,
            'status' => 'Aktif',
        ];

        // Dummy data for transaksi tabungan
        $transaksiTabungan = [
            (object) [
                'id' => 1,
                'tanggal' => '2025-01-15',
                'jumlah' => 1000000,
                'id_transaksi' => 'TRX001',
                'jenis' => 'Setoran',
                'via' => 'Transfer',
            ],
            (object) [
                'id' => 2,
                'tanggal' => '2025-01-10',
                'jumlah' => 500000,
                'id_transaksi' => 'TRX002',
                'jenis' => 'Setoran',
                'via' => 'Cash',
            ],
        ];

        // Dummy data for riwayat janji temu
        $riwayatJanjiTemu = [
            (object) [
                'id' => 1,
                'tanggal' => '2025-01-20',
                'waktu' => '10:00',
                'lokasi' => 'Kantor Pusat',
                'nominal' => 2000000,
                'status' => 'Menunggu',
            ],
            (object) [
                'id' => 2,
                'tanggal' => '2025-01-18',
                'waktu' => '14:00',
                'lokasi' => 'Cabang Utama',
                'nominal' => 1500000,
                'status' => 'Selesai',
            ],
        ];

        return view('nasabah.tabungan.index', [
            'user' => $dummyUser,
            'tabunganInfo' => $tabunganInfo,
            'transaksiTabungan' => $transaksiTabungan,
            'riwayatJanjiTemu' => $riwayatJanjiTemu,
        ]);
    }

    /**
     * Show the nabung sekarang page.
     */
    public function nabungSekarang()
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

        // Dummy data for riwayat tabungan
        $riwayatTabungan = [
            (object) [
                'id' => 1,
                'tanggal' => '2025-01-15',
                'jumlah' => 1000000,
                'id_transaksi' => 'TRX001',
                'via' => 'Transfer',
            ],
            (object) [
                'id' => 2,
                'tanggal' => '2025-01-10',
                'jumlah' => 500000,
                'id_transaksi' => 'TRX002',
                'via' => 'Cash',
            ],
        ];

        return view('nasabah.tabungan.nabung-sekarang', [
            'user' => $dummyUser,
            'riwayatTabungan' => $riwayatTabungan,
        ]);
    }

    /**
     * Show the penarikan tabungan page.
     */
    public function penarikanTabungan()
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

        // Dummy data for riwayat penarikan
        $riwayatPenarikan = [
            (object) [
                'id' => 1,
                'tanggal' => '2025-01-12',
                'jumlah' => 500000,
                'id_transaksi' => 'TRX003',
                'via' => 'Transfer',
            ],
        ];

        return view('nasabah.tabungan.penarikan-tabungan', [
            'user' => $dummyUser,
            'riwayatPenarikan' => $riwayatPenarikan,
        ]);
    }
}

