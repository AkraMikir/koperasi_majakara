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
        // 1. Proyeksi (Active Portfolio)
        $pinjamanTotal = PinjamanH::whereHas('pengajuan', function($q) {
            $q->whereIn('status', ['3', '4']);
        })->where(function($q) {
            $q->where('lunas', '!=', 'lunas')->orWhereNull('lunas');
        })->sum('bunga_rp');
        
        $gadaiTotal = GadaiActive::whereIn('status', ['active', 'extended', 'expired_grace', 'expired_final'])->sum(DB::raw('biaya_jasa + biaya_inap'));
        
        $depositoData = DepositoH::with(['persiapanCair', 'tenor'])->whereIn('status', ['aktif', 'selesai'])->get();
        $depositoTotalKotor = 0;
        $depositoPajak = 0;
        foreach($depositoData as $depo) {
            $persiapan = $depo->persiapanCair->last();
            if ($persiapan) {
                $depositoTotalKotor += $persiapan->bunga_kotor;
                $depositoPajak += $persiapan->pajak;
            } elseif ($depo->status === 'aktif') {
                // In-memory fallback
                $pokok = (float) $depo->nominal_awal;
                $bungaTahunan = (float) $depo->bunga;
                
                $tenorHari = 30;
                if ($depo->tenor) {
                    $tenorHari = (int) $depo->tenor->tenor_hari;
                } elseif ($depo->tgl_mulai && $depo->tgl_jatuh_tempo) {
                    $tenorHari = (int) $depo->tgl_mulai->diffInDays($depo->tgl_jatuh_tempo);
                }
                
                $tahunJatuhTempo = $depo->tgl_jatuh_tempo ? $depo->tgl_jatuh_tempo->year : Carbon::now()->year;
                $isLeap = ($tahunJatuhTempo % 4 === 0 && $tahunJatuhTempo % 100 !== 0) || ($tahunJatuhTempo % 400 === 0);
                $pembagi = $isLeap ? 366 : 365;
                
                $bKotor = $pokok * $bungaTahunan * ($tenorHari / $pembagi);
                $pjk = $bKotor * 0.20;
                
                $depositoTotalKotor += $bKotor;
                $depositoPajak += $pjk;
            }
        }
        $depositoTotal = $depositoTotalKotor - $depositoPajak;

        $netMargin = ($pinjamanTotal + $gadaiTotal) - $depositoTotal;

        // 2. Realisasi Kas (Bulan Ini)
        $currentMonth = Carbon::now()->month;
        $currentYear = Carbon::now()->year;

        // Realisasi Pinjaman Bulanan
        $angsuranBulanan = \App\Models\TempoPinjamanB::with('pinjaman')
            ->whereHas('pinjaman.pengajuan', function($q) {
                $q->whereIn('status', ['3', '4']);
            })
            ->where('status_bayar', 'lunas')
            ->whereMonth('tgl_bayar', $currentMonth)
            ->whereYear('tgl_bayar', $currentYear)
            ->get();

        $realisasiPinjaman = 0;
        foreach ($angsuranBulanan as $angsuran) {
            $pinjaman = $angsuran->pinjaman;
            if ($pinjaman && $pinjaman->lama_pinjam > 0) {
                $realisasiPinjaman += ($pinjaman->bunga_rp / $pinjaman->lama_pinjam);
            }
        }

        // Realisasi Pinjaman Mingguan
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
            // Mingguan: lama_pinjam dalam bulan, dikonversi ke minggu (x4)
            $jumlahMinggu = $pinjaman && $pinjaman->lama_pinjam > 0 ? ($pinjaman->lama_pinjam * 4) : 0;
            if ($jumlahMinggu > 0) {
                $realisasiPinjaman += ($pinjaman->bunga_rp / $jumlahMinggu);
            }
        }

        // Realisasi Gadai
        $payments = \App\Models\GadaiPaymentLog::with('gadaiActive')
            ->whereMonth('created_at', $currentMonth)
            ->whereYear('created_at', $currentYear)
            ->get();
            
        $realisasiGadai = 0;
        foreach ($payments as $payment) {
            if ($payment->jenis_pembayaran === 'tebus') {
                $gadai = $payment->gadaiActive;
                $bunga = $payment->nominal - ($gadai ? $gadai->nominal_deal : 0);
                $realisasiGadai += max(0, $bunga); 
            } else {
                $realisasiGadai += $payment->nominal;
            }
        }

        // Realisasi Deposito
        $realisasiDeposito = \App\Models\DepositoPersiapanCair::where('status', 'selesai')
            ->whereMonth('updated_at', $currentMonth)
            ->whereYear('updated_at', $currentYear)
            ->sum('bunga_bersih');

        $netRealisasi = ($realisasiPinjaman + $realisasiGadai) - $realisasiDeposito;

        // Trend 6 Bulan
        $sixMonthsAgo = Carbon::now()->subMonths(5)->startOfMonth();
        
        $pinjamanItems = PinjamanH::where('tgl_pinjam', '>=', $sixMonthsAgo)->get();
        $gadaiItems = GadaiActive::where('tgl_mulai', '>=', $sixMonthsAgo)->get();
        $depositoItems = DepositoH::with(['persiapanCair', 'tenor'])->where('tgl_mulai', '>=', $sixMonthsAgo)->get();

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
                } else if ($item->status === 'aktif') {
                    $pokok = (float) $item->nominal_awal;
                    $bungaTahunan = (float) $item->bunga;
                    
                    $tenorHari = 30;
                    if ($item->tenor) {
                        $tenorHari = (int) $item->tenor->tenor_hari;
                    } elseif ($item->tgl_mulai && $item->tgl_jatuh_tempo) {
                        $tenorHari = (int) $item->tgl_mulai->diffInDays($item->tgl_jatuh_tempo);
                    }
                    
                    $tahunJatuhTempo = $item->tgl_jatuh_tempo ? $item->tgl_jatuh_tempo->year : Carbon::now()->year;
                    $isLeap = ($tahunJatuhTempo % 4 === 0 && $tahunJatuhTempo % 100 !== 0) || ($tahunJatuhTempo % 400 === 0);
                    $pembagi = $isLeap ? 366 : 365;
                    
                    $bKotor = $pokok * $bungaTahunan * ($tenorHari / $pembagi);
                    $totalMonth += ($bKotor * 0.80);
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

        $data = compact(
            'pinjamanTotal', 'gadaiTotal', 'depositoTotal', 'netMargin',
            'realisasiPinjaman', 'realisasiGadai', 'realisasiDeposito', 'netRealisasi',
            'labels', 'pemasukanData', 'pengeluaranData'
        );

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

        $bungaMingguan = 0;
        foreach ($angsuranMingguan as $angsuran) {
            $pinjaman = $angsuran->pinjaman;
            // Mingguan: lama_pinjam dalam bulan, dikonversi ke minggu (x4)
            $jumlahMinggu = $pinjaman && $pinjaman->lama_pinjam > 0 ? ($pinjaman->lama_pinjam * 4) : 0;
            if ($jumlahMinggu > 0) {
                $bungaMingguan += ($pinjaman->bunga_rp / $jumlahMinggu);
            }
        }
        $bungaBulanIni += $bungaMingguan;
        
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
        $depositoAktifList = DepositoH::with(['nasabah.user', 'persiapanCair', 'tenor'])->where('status', 'aktif')->get();
        $depositoAktif = $depositoAktifList->count();
        
        $jatuhTempoBulanIni = DepositoH::where('status', 'aktif')
            ->whereMonth('tgl_jatuh_tempo', Carbon::now()->month)
            ->whereYear('tgl_jatuh_tempo', Carbon::now()->year)
            ->count();

        $totalKotor = 0;
        $totalPajak = 0;
        $totalEstimasiAkrual = 0; // Deposito aktif tanpa persiapanCair (fallback)
        $totalSiapCair = 0;       // Deposito yang sudah masuk persiapanCair
        
        foreach($depositoAktifList as $depo) {
            // Ambil data bunga dari tabel persiapan cair
            $persiapan = $depo->persiapanCair->last();
            
            if ($persiapan) {
                $bungaKotor = $persiapan->bunga_kotor;
                $pajak      = $persiapan->pajak;
                $bungaBersih = $persiapan->bunga_bersih;
                $totalSiapCair += $bungaBersih;
                $depo->sumber_bunga = 'siap_cair';
            } else {
                // In-memory fallback untuk deposito aktif yang belum masuk persiapan
                $pokok = (float) $depo->nominal_awal;
                $bungaTahunan = (float) $depo->bunga;

                $tenorHari = 30;
                if ($depo->tenor) {
                    $tenorHari = (int) $depo->tenor->tenor_hari;
                } elseif ($depo->tgl_mulai && $depo->tgl_jatuh_tempo) {
                    $tenorHari = (int) $depo->tgl_mulai->diffInDays($depo->tgl_jatuh_tempo);
                }

                $tahunJatuhTempo = $depo->tgl_jatuh_tempo ? $depo->tgl_jatuh_tempo->year : Carbon::now()->year;
                $isLeap = ($tahunJatuhTempo % 4 === 0 && $tahunJatuhTempo % 100 !== 0) || ($tahunJatuhTempo % 400 === 0);
                $pembagi = $isLeap ? 366 : 365;

                $bungaKotor  = $pokok * $bungaTahunan * ($tenorHari / $pembagi);
                $pajak       = $bungaKotor * 0.20;
                $bungaBersih = $bungaKotor - $pajak;
                $totalEstimasiAkrual += $bungaBersih;
                $depo->sumber_bunga = 'estimasi';
            }
            
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

        $data = compact(
            'totalBersih', 'depositoAktif', 'totalPajak', 'jatuhTempoBulanIni',
            'listDeposito', 'labels', 'kotorData', 'bersihData', 'tenorLabels', 'tenorData',
            'totalEstimasiAkrual', 'totalSiapCair'
        );

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
            
        $pendapatanBulanIni  = 0;
        $realisasiBungaMurni = 0; // dari tebus: selisih nominal - pokok gadai
        $realisasiAdminInap  = 0; // dari perpanjang / biaya inap lainnya
        foreach ($payments as $payment) {
            if ($payment->jenis_pembayaran === 'tebus') {
                $gadai = $payment->gadaiActive;
                $bunga = $payment->nominal - ($gadai ? $gadai->nominal_deal : 0);
                $bunga = max(0, $bunga);
                $pendapatanBulanIni  += $bunga;
                $realisasiBungaMurni += $bunga;
            } else {
                $pendapatanBulanIni += $payment->nominal;
                $realisasiAdminInap += $payment->nominal;
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

        $data = compact(
            'totalPendapatanProyeksi', 'pendapatanBulanIni',
            'realisasiBungaMurni', 'realisasiAdminInap',
            'biayaJasa', 'biayaInap', 'gadaiAktif',
            'listGadai', 'labels', 'jasaData', 'inapData', 'katLabels', 'katData'
        );

        return view('admin.bunga.gadai', $data);
    }
}
