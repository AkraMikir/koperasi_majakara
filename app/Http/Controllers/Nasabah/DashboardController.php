<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\TransTabungan;
use App\Models\PinjamanH;
use App\Models\DepositoH;
use App\Models\GadaiH;
use App\Models\Nasabah;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    /**
     * Show the nasabah dashboard.
     */
    public function index()
    {
        // TODO: Get from auth
        $idAnggota = 1;
        
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

        // Calculate Tabungan Stats
        $totalSetoran = TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'setoran')
            ->sum('nominal') ?? 0;
        $totalPenarikan = TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'penarikan')
            ->sum('nominal') ?? 0;
        $saldoTabungan = max(0, $totalSetoran - $totalPenarikan);

        // Get Pinjaman Aktif
        $pinjamanAktif = PinjamanH::where('id_anggota', $idAnggota)
            ->whereIn('status', ['pencairan', 'telaksana'])
            ->where('lunas', 'belum')
            ->get();
        $totalPinjaman = $pinjamanAktif->sum('jumlah_pinjam') ?? 0;
        $sisaPinjaman = $pinjamanAktif->sum('saldo_lebih') ?? 0;

        // Get Deposito Aktif
        $depositoAktif = DepositoH::where('id_nasabah', $idAnggota)
            ->where('status', 'aktif')
            ->get();
        $totalDeposito = $depositoAktif->sum('nominal_awal') ?? 0;
        $depositoTerdekat = $depositoAktif->sortBy('tgl_jatuh_tempo')->first();

        // Get Gadai Aktif
        $gadaiAktif = GadaiH::where('id_nasabah', $idAnggota)
            ->where('status', 'aktif')
            ->get();
        $totalGadai = $gadaiAktif->sum('jumlah_pinjaman') ?? 0;

        // Get Recent Transactions
        $transaksiTerbaru = TransTabungan::where('id_anggota', $idAnggota)
            ->latest('tgl_transaksi')
            ->take(5)
            ->get();

        // Calculate Total Assets
        $totalAssets = $saldoTabungan + $totalDeposito;

        // Stats for cards
        $stats = [
            'saldo_tabungan' => $saldoTabungan,
            'total_pinjaman' => $totalPinjaman,
            'sisa_pinjaman' => $sisaPinjaman,
            'total_deposito' => $totalDeposito,
            'total_gadai' => $totalGadai,
            'total_assets' => $totalAssets,
            'pinjaman_aktif_count' => $pinjamanAktif->count(),
            'deposito_aktif_count' => $depositoAktif->count(),
            'gadai_aktif_count' => $gadaiAktif->count(),
            'deposito_terdekat' => $depositoTerdekat,
        ];
        
        return view('nasabah.dashboard', [
            'user' => $dummyUser,
            'dummyNasabah' => $dummyNasabah,
            'stats' => $stats,
            'transaksiTerbaru' => $transaksiTerbaru,
        ]);
    }

    /**
     * Show the nasabah profile page.
     */
    public function profile()
    {
        // TODO: Get from auth
        $idAnggota = 1;
        
        // Get nasabah data with all relationships
        $nasabah = Nasabah::with(['user', 'pekerjaan', 'dataKtp', 'dataRek', 'darurat', 'transTabungan'])
            ->findOrFail($idAnggota);

        // Calculate saldo tabungan
        $totalSetoran = \App\Models\TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'setoran')
            ->sum('nominal') ?? 0;
        $totalPenarikan = \App\Models\TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'penarikan')
            ->sum('nominal') ?? 0;
        $saldoTabungan = max(0, $totalSetoran - $totalPenarikan);

        return view('nasabah.profile', compact('nasabah', 'saldoTabungan'));
    }
}
