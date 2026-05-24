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
use App\Models\TempoPinjamanB;
use App\Models\TempoPinjamanM;
use App\Models\TransDeposito;
use App\Models\TransGadai;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

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
        // Sumber Data Real dari Transaksi Modul: 
        // Masuk: Setoran Tabungan, Setor Awal Deposito, Angsuran Pinjaman, Pembayaran Gadai
        // Keluar: Penarikan Tabungan, Pencairan Pinjaman, Pencairan Deposito, Pencairan Gadai
        
        $startDate = now()->subDays(14)->startOfDay();
        $endDate = now()->endOfDay();

        // 1. Tabungan
        $tabunganMasuk = TransTabungan::whereHas('jnsTransaksi', function($q) { $q->where('kode', 'STR'); })
            ->whereBetween('tgl_transaksi', [$startDate, $endDate])
            ->selectRaw('DATE(tgl_transaksi) as date, SUM(nominal) as total')
            ->groupBy('date')->pluck('total', 'date')->toArray();

        $tabunganKeluar = TransTabungan::whereHas('jnsTransaksi', function($q) { $q->where('kode', 'PNR'); })
            ->whereBetween('tgl_transaksi', [$startDate, $endDate])
            ->selectRaw('DATE(tgl_transaksi) as date, SUM(nominal) as total')
            ->groupBy('date')->pluck('total', 'date')->toArray();

        // 2. Pinjaman
        $pinjamanKeluar = PinjamanH::whereIn('lunas', ['belum', 'lunas'])
            ->whereBetween('tgl_pinjam', [$startDate, $endDate])
            ->selectRaw('DATE(tgl_pinjam) as date, SUM(jumlah_pinjam) as total')
            ->groupBy('date')->pluck('total', 'date')->toArray();

        $angsuranMasukB = TempoPinjamanB::where('status_bayar', 'lunas')
            ->whereBetween('tgl_bayar', [$startDate, $endDate])
            ->selectRaw('DATE(tgl_bayar) as date, SUM(jumlah_terbayar) as total')
            ->groupBy('date')->pluck('total', 'date')->toArray();

        $angsuranMasukM = TempoPinjamanM::where('status_bayar', 'lunas')
            ->whereBetween('tgl_bayar', [$startDate, $endDate])
            ->selectRaw('DATE(tgl_bayar) as date, SUM(jumlah_terbayar) as total')
            ->groupBy('date')->pluck('total', 'date')->toArray();

        // 3. Deposito
        $depositoMasuk = TransDeposito::where('jenis', 'setor_awal')
            ->whereBetween('tgl_transaksi', [$startDate, $endDate])
            ->selectRaw('DATE(tgl_transaksi) as date, SUM(nominal) as total')
            ->groupBy('date')->pluck('total', 'date')->toArray();

        $depositoKeluar = TransDeposito::where('jenis', 'pencairan')
            ->whereBetween('tgl_transaksi', [$startDate, $endDate])
            ->selectRaw('DATE(tgl_transaksi) as date, SUM(nominal) as total')
            ->groupBy('date')->pluck('total', 'date')->toArray();

        // 4. Gadai
        $gadaiKeluar = GadaiH::whereIn('status', ['aktif', 'lunas', 'dilelang'])
            ->whereBetween('tgl_mulai', [$startDate, $endDate])
            ->selectRaw('DATE(tgl_mulai) as date, SUM(jumlah_pinjaman) as total')
            ->groupBy('date')->pluck('total', 'date')->toArray();

        $gadaiMasuk = TransGadai::whereIn('jenis', ['bunga', 'pelunasan', 'pelunasan_akhir', 'denda', 'lelang'])
            ->whereBetween('tgl_transaksi', [$startDate, $endDate])
            ->selectRaw('DATE(tgl_transaksi) as date, SUM(nominal) as total')
            ->groupBy('date')->pluck('total', 'date')->toArray();

        $labels     = [];
        $dataMasuk  = [];
        $dataKeluar = [];

        for ($i = 14; $i >= 0; $i--) {
            $date     = now()->subDays($i);
            $dateStr  = $date->toDateString();
            $labels[] = $date->format('d M');

            // Total Masuk
            $masuk = ($tabunganMasuk[$dateStr] ?? 0)
                   + ($angsuranMasukB[$dateStr] ?? 0)
                   + ($angsuranMasukM[$dateStr] ?? 0)
                   + ($depositoMasuk[$dateStr] ?? 0)
                   + ($gadaiMasuk[$dateStr] ?? 0);

            // Total Keluar
            $keluar = ($tabunganKeluar[$dateStr] ?? 0)
                    + ($pinjamanKeluar[$dateStr] ?? 0)
                    + ($depositoKeluar[$dateStr] ?? 0)
                    + ($gadaiKeluar[$dateStr] ?? 0);

            $dataMasuk[]  = (float) $masuk;
            $dataKeluar[] = (float) $keluar;
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
                'timestamp' => $t->created_at->timestamp,
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
                'timestamp' => $p->created_at->timestamp,
            ];
        }

        // Deposito Terbaru
        $deposito = TransDeposito::with('deposito.nasabah.user')
            ->latest('tgl_transaksi')
            ->take(5)
            ->get();
            
        foreach ($deposito as $d) {
            $jenis = str_replace('_', ' ', $d->jenis);
            $aktivitas[] = [
                'type' => 'deposito',
                'deskripsi' => ($d->deposito->nasabah->user->nama ?? 'Nasabah') . ' - Deposito (' . ucfirst($jenis) . ') Rp ' . number_format($d->nominal, 0, ',', '.'),
                'waktu' => $d->created_at->diffForHumans(),
                'timestamp' => $d->created_at->timestamp,
            ];
        }
        
        // Gadai Terbaru
        $gadai = TransGadai::with('nasabah.user')
            ->latest('tgl_transaksi')
            ->take(5)
            ->get();
            
        foreach ($gadai as $g) {
            $jenis = str_replace('_', ' ', $g->jenis);
            $aktivitas[] = [
                'type' => 'gadai',
                'deskripsi' => ($g->nasabah->user->nama ?? 'Nasabah') . ' - Gadai (' . ucfirst($jenis) . ') Rp ' . number_format($g->nominal, 0, ',', '.'),
                'waktu' => $g->created_at->diffForHumans(),
                'timestamp' => $g->created_at->timestamp,
            ];
        }

        // Sort by waktu terbaru
        usort($aktivitas, function($a, $b) {
            return $b['timestamp'] - $a['timestamp'];
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

