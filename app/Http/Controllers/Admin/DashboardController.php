<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Nasabah;
use App\Models\TransTabungan;
use App\Models\PinjamanH;
use App\Models\DepositoH;
use App\Models\GadaiH;
use App\Models\PengajuanTabungan;
use App\Models\PengajuanPinjaman;
use App\Models\PengajuanDeposito;
use App\Models\PengajuanGadai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = auth()->id();

        // Statistik utama
        $totalSetoran = TransTabungan::whereHas('jnsTransaksi', function($q) {
                $q->where('kode', 'STR');
            })->sum('nominal') ?? 0;
            
        $totalPenarikan = TransTabungan::whereHas('jnsTransaksi', function($q) {
                $q->where('kode', 'PNR');
            })->sum('nominal') ?? 0;
        
        $stats = [
            'total_nasabah' => Nasabah::count(),
            'total_tabungan' => max(0, $totalSetoran - $totalPenarikan),
            'total_pinjaman' => PinjamanH::where('lunas', 'belum')->sum('jumlah_pinjam') ?? 0,
            'pinjaman_aktif' => PinjamanH::where('lunas', 'belum')->count(),
            'total_deposito' => DepositoH::where('status', 'aktif')->sum('nominal_awal') ?? 0,
            'deposito_aktif' => DepositoH::where('status', 'aktif')->count(),
            'total_gadai' => GadaiH::where('status', 'aktif')->sum('jumlah_pinjaman') ?? 0,
            'gadai_aktif' => GadaiH::where('status', 'aktif')->count(),
            'pengajuan_pending' => $this->getTotalPengajuanPending(),
            'pendapatan_bulan' => $this->getPendapatanBulanIni(),
        ];

        // Pengajuan pending dengan Eager Loading
        $pengajuan_pending = $this->getPengajuanPending();

        // Aktivitas terkini dengan Eager Loading
        $aktivitas_terkini = $this->getAktivitasTerkini();

        // Grafik Likuiditas (15 Hari Terakhir)
        // Sumber: petty_cash_saldo (role=owner) — mutasi positif = kas masuk, negatif = kas keluar
        // Ini mencakup: setoran tabungan, setoran deposito, angsuran pinjaman, pembayaran gadai (masuk)
        // dan: pencairan pinjaman, penarikan tabungan, pencairan deposito, dll (keluar)
        $labels     = [];
        $dataMasuk  = [];
        $dataKeluar = [];

        for ($i = 14; $i >= 0; $i--) {
            $date     = now()->subDays($i);
            $dateStr  = $date->toDateString();
            $labels[] = $date->format('d M');

            // KAS MASUK ke koperasi: semua mutasi POSITIF di saldo owner
            // Mencakup: deposito masuk, setoran tabungan, angsuran pinjaman, gadai, dll.
            $masuk = DB::table('petty_cash_saldo')
                ->where('role', 'owner')
                ->where('mutasi', '>', 0)
                ->whereDate('created_at', $dateStr)
                ->sum('mutasi');

            // KAS KELUAR dari koperasi: semua mutasi NEGATIF di saldo owner
            // Mencakup: pencairan pinjaman, pencairan deposito, penarikan tabungan, dll.
            $keluar = DB::table('petty_cash_saldo')
                ->where('role', 'owner')
                ->where('mutasi', '<', 0)
                ->whereDate('created_at', $dateStr)
                ->sum('mutasi'); // nilai negatif, dikonversi abs() di bawah

            $dataMasuk[]  = (float) $masuk;
            $dataKeluar[] = (float) abs($keluar); // jadikan positif untuk chart
        }

        $grafik_likuiditas = [
            'labels' => $labels,
            'masuk'  => $dataMasuk,
            'keluar' => $dataKeluar,
        ];

        return view('admin.dashboard', compact('stats', 'pengajuan_pending', 'aktivitas_terkini', 'grafik_likuiditas'));
    }

    private function getTotalPengajuanPending()
    {
        // Status '1' biasanya berarti pending untuk pengajuan tabungan dan deposito
        $tabungan = PengajuanTabungan::where('status', '1')->count();
        $pinjaman = PengajuanPinjaman::whereDoesntHave('pinjaman')->count();
        $deposito = PengajuanDeposito::where('status', '1')->count();
        // Cek status gadai - biasanya 'pending' atau '1'
        // Skip gadai try catch for now
        $gadai = 0;
        try {
            $gadai = PengajuanGadai::where(function($query) {
                $query->where('status', 'pending')
                      ->orWhere('status', '1');
            })->count();
        } catch (\Exception $e) {}

        return $tabungan + $pinjaman + $deposito + $gadai;
    }

    private function getPengajuanPending()
    {
        $pengajuan = [];

        // Pengajuan Tabungan (Setoran)
        $tabungan = PengajuanTabungan::where('status', '1')
            ->with('nasabah.user')
            ->latest()
            ->take(5)
            ->get();

        foreach ($tabungan as $t) {
            $pengajuan[] = [
                'id'            => $t->id,
                'type'          => 'tabungan',
                'label'         => 'Setoran',
                'nama'          => $t->nasabah->user->nama ?? 'N/A',
                'nominal'       => $t->nominal,
                'tanggal'       => $t->created_at->format('d M Y'),
                'route_approve' => route('admin.tabungan.approve-setor', $t->id),
                'route_reject'  => route('admin.tabungan.reject-setor', $t->id),
                'route_index'   => route('admin.tabungan.index'),
            ];
        }

        // Pengajuan Pinjaman
        $pinjaman = PengajuanPinjaman::whereDoesntHave('pinjaman')
            ->with('nasabah.user')
            ->latest()
            ->take(5)
            ->get();

        foreach ($pinjaman as $p) {
            $pengajuan[] = [
                'id'            => $p->id,
                'type'          => 'pinjaman',
                'label'         => 'Pinjaman',
                'nama'          => $p->nasabah->user->nama ?? 'N/A',
                'nominal'       => $p->nominal,
                'tanggal'       => $p->created_at->format('d M Y'),
                'route_approve' => route('admin.pinjaman.approve-pengajuan', $p->id),
                'route_reject'  => route('admin.pinjaman.reject-pengajuan', $p->id),
                'route_index'   => route('admin.pinjaman.index'),
            ];
        }

        // Pengajuan Deposito
        try {
            $deposito = PengajuanDeposito::where('status', '1')
                ->with('nasabah.user')
                ->latest()
                ->take(5)
                ->get();

            foreach ($deposito as $d) {
                $pengajuan[] = [
                    'id'            => $d->id,
                    'type'          => 'deposito',
                    'label'         => 'Deposito',
                    'nama'          => $d->nasabah->user->nama ?? 'N/A',
                    'nominal'       => $d->nominal,
                    'tanggal'       => $d->created_at->format('d M Y'),
                    'route_approve' => route('admin.deposito.approve', $d->id),
                    'route_reject'  => route('admin.deposito.reject', $d->id),
                    'route_index'   => route('admin.deposito.index'),
                ];
            }
        } catch (\Exception $e) {}

        // Pengajuan Gadai
        try {
        $gadai = PengajuanGadai::where(function($query) {
            $query->where('status', 'pending')
                  ->orWhere('status', '1');
        })
            ->with('nasabah.user')
            ->latest()
            ->take(5)
            ->get();

        foreach ($gadai as $g) {
            $pengajuan[] = [
                'id'            => $g->id,
                'type'          => 'gadai',
                'label'         => 'Gadai',
                'nama'          => $g->nasabah->user->nama ?? 'N/A',
                'nominal'       => $g->nominal_diajukan,
                'tanggal'       => $g->created_at->format('d M Y'),
                'route_approve' => route('admin.gadai_baru.pengajuan.approve', $g->id),
                'route_reject'  => route('admin.gadai_baru.pengajuan.reject', $g->id),
                'route_index'   => route('admin.gadai_baru.index'),
            ];
        }
        } catch (\Exception $e) {}

        // Sort by tanggal terbaru
        usort($pengajuan, function($a, $b) {
            return strtotime($b['tanggal']) - strtotime($a['tanggal']);
        });

        return array_slice($pengajuan, 0, 5);
    }

    private function getAktivitasTerkini()
    {
        $aktivitas = [];

        // Transaksi Tabungan Terbaru
        $transTabungan = TransTabungan::with(['nasabah.user', 'jnsTransaksi'])
            ->latest('tgl_transaksi')
            ->take(5)
            ->get();

        foreach ($transTabungan as $t) {
            $jenis = $t->jnsTransaksi ? $t->jnsTransaksi->nama : 'Transaksi';
            $aktivitas[] = [
                'type' => 'tabungan',
                'deskripsi' => ($t->nasabah->user->nama ?? 'Nasabah') . ' - ' . ucfirst($jenis) . ' Rp ' . number_format($t->nominal, 0, ',', '.'),
                'waktu' => $t->created_at->diffForHumans(),
            ];
        }

        // Pinjaman Terbaru
        $pinjaman = PinjamanH::with('nasabah.user')
            ->latest()
            ->take(5)
            ->get();

        foreach ($pinjaman as $p) {
            $aktivitas[] = [
                'type' => 'pinjaman',
                'deskripsi' => ($p->nasabah->user->nama ?? 'Nasabah') . ' - Pinjaman baru Rp ' . number_format($p->jumlah_pinjam, 0, ',', '.'),
                'waktu' => $p->created_at->diffForHumans(),
            ];
        }

        // Sort by waktu terbaru
        usort($aktivitas, function($a, $b) {
            return strtotime($b['waktu']) - strtotime($a['waktu']);
        });

        return array_slice($aktivitas, 0, 10);
    }

    private function getPendapatanBulanIni()
    {
        $bulanIni = now()->startOfMonth();
        
        // Bunga dari pinjaman
        $bungaPinjaman = PinjamanH::where('created_at', '>=', $bulanIni)
            ->sum('bunga_rp') ?? 0;

        // Bunga dari deposito (jika ada)
        $bungaDeposito = 0; // Implementasi sesuai kebutuhan

        // Bunga dari gadai
        $bungaGadai = GadaiH::where('created_at', '>=', $bulanIni)
            ->sum('bunga_rp') ?? 0;

        return ($bungaPinjaman ?? 0) + $bungaDeposito + ($bungaGadai ?? 0);
    }
}

