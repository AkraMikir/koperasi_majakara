<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\PinjamanH;
use App\Models\DepositoH;
use App\Models\GadaiActive;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class BungaController extends Controller
{
    /**
     * Dashboard Utama Bunga (ringkasan semua modul)
     */
    public function index()
    {
        // Totals
        $pinjamanTotal = PinjamanH::whereHas('pengajuan', function($q) {
            $q->whereIn('status', ['3', '4']);
        })->where(function($q) {
            $q->where('lunas', '!=', 'lunas')->orWhereNull('lunas');
        })->sum('bunga_rp');
        
        $gadaiTotal = GadaiActive::whereIn('status', ['active', 'extended', 'expired_grace', 'expired_final'])->sum(DB::raw('biaya_jasa + biaya_inap'));
        
        $depositoData = DepositoH::with('persiapanCair')->whereIn('status', ['aktif', 'selesai'])->get();
        $depositoTotalKotor = 0;
        $depositoPajak = 0;
        foreach($depositoData as $depo) {
            $persiapan = $depo->persiapanCair->last();
            if ($persiapan) {
                $depositoTotalKotor += $persiapan->bunga_kotor;
                $depositoPajak += $persiapan->pajak;
            }
        }
        $depositoTotal = $depositoTotalKotor - $depositoPajak;

        $netMargin = ($pinjamanTotal + $gadaiTotal) - $depositoTotal;

        // Trend 6 Bulan
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
        
        $pinjamanItems = PinjamanH::where('tgl_pinjam', '>=', $sixMonthsAgo)->get();
        $gadaiItems = GadaiActive::where('tgl_mulai', '>=', $sixMonthsAgo)->get();
        $depositoItems = DepositoH::with('persiapanCair')->where('tgl_mulai', '>=', $sixMonthsAgo)->get();

        $pinjamanTrend = $pinjamanItems->groupBy(function($item) {
            return Carbon::parse($item->tgl_pinjam)->format('Y-m');
        })->map->sum('bunga_rp');

        $gadaiTrend = $gadaiItems->groupBy(function($item) {
            return Carbon::parse($item->tgl_mulai)->format('Y-m');
        })->map(function($items) {
            return $items->sum('biaya_jasa') + $items->sum('biaya_inap');
        });

        $depositoTrend = [];
        foreach($depositoItems->groupBy(function($item) { return Carbon::parse($item->tgl_mulai)->format('Y-m'); }) as $month => $items) {
            $totalMonth = 0;
            foreach($items as $item) {
                $p = $item->persiapanCair->last();
                if ($p) {
                    $totalMonth += $p->bunga_bersih;
                }
            }
            $depositoTrend[$month] = $totalMonth;
        }

        $labels = [];
        $pemasukanData = [];
        $pengeluaranData = [];

        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $labels[] = $date->translatedFormat('M');
            
            $pemasukanData[] = ($pinjamanTrend[$monthKey] ?? 0) + ($gadaiTrend[$monthKey] ?? 0);
            $pengeluaranData[] = $depositoTrend[$monthKey] ?? 0;
        }

        $data = compact('pinjamanTotal', 'gadaiTotal', 'depositoTotal', 'netMargin', 'labels', 'pemasukanData', 'pengeluaranData');

        return view('admin.bunga.index', $data);
    }

    /**
     * Dashboard Bunga Pinjaman
     */
    public function pinjaman()
    {
        $query = PinjamanH::whereHas('pengajuan', function($q) {
            $q->whereIn('status', ['3', '4']);
        })->where(function($q) {
            $q->where('lunas', '!=', 'lunas')->orWhereNull('lunas');
        });

        $totalBunga = (clone $query)->sum('bunga_rp');
        $pinjamanAktif = (clone $query)->count();
        
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Bunga dari Pinjaman Bulanan yang dibayar bulan ini
        $angsuranBulanan = \App\Models\TempoPinjamanB::with('pinjaman')
            ->whereHas('pinjaman.pengajuan', function($q) {
                $q->whereIn('status', ['3', '4']);
            })
            ->where('status_bayar', 'lunas')
            ->whereMonth('tgl_bayar', $currentMonth)
            ->whereYear('tgl_bayar', $currentYear)
            ->get();

        $bungaBulanIni = 0;
        foreach ($angsuranBulanan as $angsuran) {
            $pinjaman = $angsuran->pinjaman;
            if ($pinjaman && $pinjaman->lama_pinjam > 0) {
                $bungaBulanIni += ($pinjaman->bunga_rp / $pinjaman->lama_pinjam);
            }
        }

        // Bunga dari Pinjaman Mingguan yang dibayar bulan ini
        $angsuranMingguan = \App\Models\TempoPinjamanM::with('pinjaman')
            ->whereHas('pinjaman.pengajuan', function($q) {
                $q->whereIn('status', ['3', '4']);
            })
            ->where('status_bayar', 'lunas')
            ->whereMonth('tgl_bayar', $currentMonth)
            ->whereYear('tgl_bayar', $currentYear)
            ->get();

        foreach ($angsuranMingguan as $angsuran) {
            $pinjaman = $angsuran->pinjaman;
            if ($pinjaman && $pinjaman->lama_pinjam > 0) {
                $bungaBulanIni += ($pinjaman->bunga_rp / $pinjaman->lama_pinjam);
            }
        }
        
        $proyeksiBulanDepan = $totalBunga * 1.05; // Estimasi 5% growth
        
        $listPinjaman = (clone $query)->with('nasabah.user')->orderByDesc('bunga_rp')->take(10)->get();

        // Chart trend
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
        $pinjamanItems = PinjamanH::where('tgl_pinjam', '>=', $sixMonthsAgo)->get();
        $trendGrouped = $pinjamanItems->groupBy(function($item) {
            return Carbon::parse($item->tgl_pinjam)->format('Y-m');
        })->map->sum('bunga_rp');

        $labels = [];
        $trendData = [];
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $labels[] = $date->translatedFormat('M');
            $trendData[] = $trendGrouped[$date->format('Y-m')] ?? 0;
        }

        // Distribusi durasi
        $durasiGrouped = $query->get()->groupBy('lama_pinjam');
        $durasiLabels = [];
        $durasiData = [];
        foreach($durasiGrouped as $durasi => $items) {
            $durasiLabels[] = $durasi . ' Bln';
            $durasiData[] = $items->count();
        }

        $data = compact('totalBunga', 'pinjamanAktif', 'bungaBulanIni', 'proyeksiBulanDepan', 'listPinjaman', 'labels', 'trendData', 'durasiLabels', 'durasiData');

        return view('admin.bunga.pinjaman', $data);
    }

    public function deposito()
    {
        $depositoAktifList = DepositoH::with(['nasabah.user', 'persiapanCair'])->where('status', 'aktif')->get();
        $depositoAktif = $depositoAktifList->count();
        
        $jatuhTempoBulanIni = DepositoH::where('status', 'aktif')
            ->whereMonth('tgl_jatuh_tempo', Carbon::now()->month)
            ->whereYear('tgl_jatuh_tempo', Carbon::now()->year)
            ->count();

        $totalKotor = 0;
        $totalPajak = 0;
        
        foreach($depositoAktifList as $depo) {
            // Ambil data bunga dari tabel persiapan cair
            $persiapan = $depo->persiapanCair->last();
            
            $bungaKotor = $persiapan ? $persiapan->bunga_kotor : 0;
            $pajak = $persiapan ? $persiapan->pajak : 0;
            $bungaBersih = $persiapan ? $persiapan->bunga_bersih : 0;
            
            $depo->bunga_kotor_rp = $bungaKotor;
            $depo->pajak_rp = $pajak;
            $depo->bunga_bersih_rp = $bungaBersih;
            
            $totalKotor += $bungaKotor;
            $totalPajak += $pajak;
        }
        $totalBersih = $totalKotor - $totalPajak;
        $listDeposito = $depositoAktifList->sortByDesc('bunga_kotor_rp')->take(10);

        // Chart trend kotor vs bersih (Mengambil data dari persiapan cair)
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
        $depositoItems = DepositoH::with('persiapanCair')->where('tgl_mulai', '>=', $sixMonthsAgo)->get();
        
        $labels = [];
        $kotorData = [];
        $bersihData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $labels[] = $date->translatedFormat('M');
            
            $monthItems = $depositoItems->filter(function($d) use ($monthKey) {
                return Carbon::parse($d->tgl_mulai)->format('Y-m') === $monthKey;
            });
            
            $mKotor = 0;
            $mBersih = 0;
            foreach($monthItems as $item) {
                $p = $item->persiapanCair->last();
                if ($p) {
                    $mKotor += $p->bunga_kotor;
                    $mBersih += $p->bunga_bersih;
                }
            }
            $kotorData[] = $mKotor;
            $bersihData[] = $mBersih;
        }

        // Distribusi tenor
        $tenorLabels = [];
        $tenorData = [];
        foreach($depositoAktifList->groupBy(function($item) { return max(1, round(Carbon::parse($item->tgl_mulai)->diffInDays(Carbon::parse($item->tgl_jatuh_tempo ?? $item->tgl_mulai->addMonths(1)))/30)); }) as $m => $items) {
            $tenorLabels[] = $m . ' Bulan';
            $tenorData[] = $items->count();
        }

        $data = compact('totalBersih', 'depositoAktif', 'totalPajak', 'jatuhTempoBulanIni', 'listDeposito', 'labels', 'kotorData', 'bersihData', 'tenorLabels', 'tenorData');

        return view('admin.bunga.deposito', $data);
    }

    /**
     * Dashboard Bunga Gadai
     */
    public function gadai()
    {
        $gadaiAktifList = GadaiActive::with(['nasabah.user', 'kategori', 'item'])->whereIn('status', ['active', 'extended', 'expired_grace', 'expired_final'])->get();
        
        $biayaJasa = $gadaiAktifList->sum('biaya_jasa');
        $biayaInap = $gadaiAktifList->sum('biaya_inap'); 
        $totalPendapatanProyeksi = $biayaJasa + $biayaInap;
        
        $gadaiAktif = $gadaiAktifList->count();
        
        // Calculate Real Pendapatan Bulan Ini from GadaiPaymentLog
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        $payments = \App\Models\GadaiPaymentLog::with('gadaiActive')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->get();
            
        $pendapatanBulanIni = 0;
        foreach ($payments as $payment) {
            if ($payment->jenis_pembayaran === 'tebus') {
                $gadai = $payment->gadaiActive;
                $bunga = $payment->nominal - ($gadai ? $gadai->nominal_deal : 0);
                $pendapatanBulanIni += max(0, $bunga); 
            } else {
                $pendapatanBulanIni += $payment->nominal;
            }
        }
        
        $listGadai = $gadaiAktifList->sortByDesc(function($g) {
            return $g->biaya_jasa + $g->biaya_inap;
        })->take(10);
        
        foreach($listGadai as $g) {
            $g->sim_total = $g->biaya_jasa + $g->biaya_inap;
        }

        // Chart trend
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
        $gadaiItems = GadaiActive::where('tgl_mulai', '>=', $sixMonthsAgo)->get();
        
        $labels = [];
        $jasaData = [];
        $inapData = [];
        
        for ($i = 5; $i >= 0; $i--) {
            $date = Carbon::now()->subMonths($i);
            $monthKey = $date->format('Y-m');
            $labels[] = $date->translatedFormat('M');
            
            $monthItems = $gadaiItems->filter(function($d) use ($monthKey) {
                return Carbon::parse($d->tgl_mulai)->format('Y-m') === $monthKey;
            });
            
            $mJasa = $monthItems->sum('biaya_jasa');
            $mInap = $monthItems->sum('biaya_inap');
            
            $jasaData[] = $mJasa;
            $inapData[] = $mInap;
        }

        // Kategori barang
        $katLabels = [];
        $katData = [];
        foreach($gadaiAktifList->groupBy(function($item) {
            return optional($item->kategori)->nama_kategori ?? 'Lainnya';
        }) as $k => $items) {
            $katLabels[] = $k;
            $katData[] = $items->count();
        }

        $data = compact('totalPendapatanProyeksi', 'pendapatanBulanIni', 'biayaJasa', 'biayaInap', 'gadaiAktif', 'listGadai', 'labels', 'jasaData', 'inapData', 'katLabels', 'katData');

        return view('admin.bunga.gadai', $data);
    }
}
