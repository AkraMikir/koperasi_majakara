<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanTabungan;
use App\Models\PengajuanPenarikanTabungan;
use App\Models\TransTabungan;
use App\Models\JanjiTemuTabungan;
use App\Models\Nasabah;
use App\Models\BuktiFotoTabungan;
use Illuminate\Http\Request;

class TabunganController extends Controller
{
    /**
     * Display dashboard tabungan admin.
     */
    public function index()
    {
        // Statistik tabungan
        $stats = [
            'total_pengajuan_setor' => PengajuanTabungan::where('status', '1')->count(),
            'total_pengajuan_tarik' => PengajuanPenarikanTabungan::where('status', '1')->count(),
            'total_transaksi_hari_ini' => TransTabungan::whereDate('created_at', today())->count(),
            'total_setoran_hari_ini' => TransTabungan::where('jenis', 'setoran')->whereDate('created_at', today())->sum('nominal') ?? 0,
            'total_penarikan_hari_ini' => TransTabungan::where('jenis', 'penarikan')->whereDate('created_at', today())->sum('nominal') ?? 0,
            'total_janji_temu_pending' => JanjiTemuTabungan::where('tanggal_janji_temu', '>=', now())->count(),
        ];

        // Pengajuan setoran terbaru (pending)
        $pengajuan_setor_terbaru = PengajuanTabungan::where('status', '1')
            ->with(['nasabah.user', 'buktiFoto'])
            ->latest()
            ->take(5)
            ->get();

        // Pengajuan penarikan terbaru (pending)
        $pengajuan_tarik_terbaru = PengajuanPenarikanTabungan::where('status', '1')
            ->with('nasabah.user')
            ->latest()
            ->take(5)
            ->get();

        // Transaksi terbaru
        $transaksi_terbaru = TransTabungan::with('nasabah.user')
            ->latest()
            ->take(10)
            ->get();

        return view('admin.tabungan.index', compact(
            'stats',
            'pengajuan_setor_terbaru',
            'pengajuan_tarik_terbaru',
            'transaksi_terbaru'
        ));
    }

    /**
     * Display list of pengajuan setoran tabungan.
     */
    public function pengajuanSetor(Request $request)
    {
        $query = PengajuanTabungan::with(['nasabah.user', 'buktiFoto'])
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        } else {
            // Default show pending
            $query->where('status', '1');
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('nasabah.user', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pengajuan = $query->paginate(15);

        return view('admin.tabungan.pengajuan-setor', compact('pengajuan'));
    }

    /**
     * Display detail pengajuan setoran.
     */
    public function detailPengajuanSetor($id)
    {
        $pengajuan = PengajuanTabungan::with(['nasabah.user', 'nasabah.dataKtp', 'buktiFoto', 'janjiTemu.lokasi'])
            ->findOrFail($id);

        return view('admin.tabungan.detail-pengajuan-setor', compact('pengajuan'));
    }

    /**
     * Approve pengajuan setoran.
     */
    public function approveSetor(Request $request, $id)
    {
        $pengajuan = PengajuanTabungan::findOrFail($id);
        
        // Update status to approved (status '2')
        $pengajuan->update(['status' => '2']);

        // Get nominal from bukti foto atau janji temu
        $nominal = 0;
        if ($pengajuan->buktiFoto && $pengajuan->buktiFoto->count() > 0) {
            $nominal = $pengajuan->buktiFoto->sum('nominal');
        } elseif ($pengajuan->janjiTemu) {
            $nominal = $pengajuan->janjiTemu->nominal ?? 0;
        }

        // Create transaksi tabungan jika belum ada dan nominal > 0
        if ($nominal > 0 && $pengajuan->transTabungan->count() == 0) {
            TransTabungan::create([
                'id_pengajuan_setor' => $pengajuan->id,
                'id_anggota' => $pengajuan->id_anggota,
                'nominal' => $nominal,
                'keterangan' => $pengajuan->keterangan ?? 'Setoran tabungan disetujui',
                'jenis' => 'setoran',
                'via' => $request->via ?? 'transfer',
                'tgl_transaksi' => now(),
            ]);
        }

        return redirect()->route('admin.tabungan.pengajuan-setor')
            ->with('success', 'Pengajuan setoran berhasil disetujui');
    }

    /**
     * Reject pengajuan setoran.
     */
    public function rejectSetor(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string'
        ]);

        $pengajuan = PengajuanTabungan::findOrFail($id);
        $pengajuan->update([
            'status' => '3',
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('admin.tabungan.pengajuan-setor')
            ->with('success', 'Pengajuan setoran ditolak');
    }

    /**
     * Display list of pengajuan penarikan tabungan.
     */
    public function pengajuanTarik(Request $request)
    {
        $query = PengajuanPenarikanTabungan::with('nasabah.user')
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        } else {
            // Default show pending
            $query->where('status', '1');
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('nasabah.user', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pengajuan = $query->paginate(15);

        return view('admin.tabungan.pengajuan-tarik', compact('pengajuan'));
    }

    /**
     * Display detail pengajuan penarikan.
     */
    public function detailPengajuanTarik($id)
    {
        $pengajuan = PengajuanPenarikanTabungan::with(['nasabah.user', 'nasabah.dataKtp'])
            ->findOrFail($id);

        // Get saldo nasabah
        $saldo = $this->getSaldoNasabah($pengajuan->id_anggota);

        return view('admin.tabungan.detail-pengajuan-tarik', compact('pengajuan', 'saldo'));
    }

    /**
     * Approve pengajuan penarikan.
     */
    public function approveTarik(Request $request, $id)
    {
        $pengajuan = PengajuanPenarikanTabungan::findOrFail($id);
        
        // Check saldo
        $saldo = $this->getSaldoNasabah($pengajuan->id_anggota);
        
        if ($saldo < $pengajuan->nominal) {
            return redirect()->back()
                ->with('error', 'Saldo nasabah tidak mencukupi');
        }

        // Update status to approved (status '2')
        $pengajuan->update(['status' => '2']);

        // Create transaksi penarikan
        TransTabungan::create([
            'id_pengajuan_tarik' => $pengajuan->id,
            'id_anggota' => $pengajuan->id_anggota,
            'nominal' => $pengajuan->nominal,
            'keterangan' => $pengajuan->keterangan,
            'jenis' => 'penarikan',
            'via' => 'transfer', // Default atau dari request
            'tgl_transaksi' => now(),
        ]);

        return redirect()->route('admin.tabungan.pengajuan-tarik')
            ->with('success', 'Pengajuan penarikan berhasil disetujui');
    }

    /**
     * Reject pengajuan penarikan.
     */
    public function rejectTarik(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string'
        ]);

        $pengajuan = PengajuanPenarikanTabungan::findOrFail($id);
        $pengajuan->update([
            'status' => '3',
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('admin.tabungan.pengajuan-tarik')
            ->with('success', 'Pengajuan penarikan ditolak');
    }

    /**
     * Display list of transaksi tabungan.
     */
    public function transaksi(Request $request)
    {
        $query = TransTabungan::with('nasabah.user')
            ->latest();

        // Filter by jenis
        if ($request->has('jenis') && $request->jenis !== '') {
            $query->where('jenis', $request->jenis);
        }

        // Filter by date
        if ($request->has('tanggal_dari') && $request->tanggal_dari !== '') {
            $query->whereDate('tgl_transaksi', '>=', $request->tanggal_dari);
        }

        if ($request->has('tanggal_sampai') && $request->tanggal_sampai !== '') {
            $query->whereDate('tgl_transaksi', '<=', $request->tanggal_sampai);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('nasabah.user', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $transaksi = $query->paginate(20);

        return view('admin.tabungan.transaksi', compact('transaksi'));
    }

    /**
     * Display list of janji temu tabungan.
     */
    public function janjiTemu(Request $request)
    {
        $query = JanjiTemuTabungan::with(['pengajuan.nasabah.user', 'lokasi'])
            ->latest('tanggal_janji_temu');

        // Filter by date
        if ($request->has('tanggal_dari') && $request->tanggal_dari !== '') {
            $query->whereDate('tanggal_janji_temu', '>=', $request->tanggal_dari);
        }

        if ($request->has('tanggal_sampai') && $request->tanggal_sampai !== '') {
            $query->whereDate('tanggal_janji_temu', '<=', $request->tanggal_sampai);
        }

        $janjiTemu = $query->paginate(15);

        return view('admin.tabungan.janji-temu', compact('janjiTemu'));
    }

    /**
     * Display detail transaksi tabungan.
     */
    public function detailTransaksi($id)
    {
        $transaksi = TransTabungan::with(['nasabah.user', 'nasabah.dataKtp', 'pengajuanSetor.buktiFoto', 'pengajuanTarik'])
            ->findOrFail($id);

        return view('admin.tabungan.detail-transaksi', compact('transaksi'));
    }

    /**
     * Display detail janji temu.
     */
    public function detailJanjiTemu($id)
    {
        $janjiTemu = JanjiTemuTabungan::with(['pengajuan.nasabah.user', 'pengajuan.nasabah.dataKtp', 'lokasi'])
            ->findOrFail($id);

        return view('admin.tabungan.detail-janji-temu', compact('janjiTemu'));
    }

    /**
     * Display saldo nasabah per nasabah.
     */
    public function saldoNasabah(Request $request)
    {
        $query = Nasabah::with('user');

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('user', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $nasabah = $query->paginate(20);

        // Calculate saldo for each nasabah
        $nasabah->getCollection()->transform(function($item) {
            $item->saldo = $this->getSaldoNasabah($item->id);
            $item->total_setoran = TransTabungan::where('id_anggota', $item->id)
                ->where('jenis', 'setoran')
                ->sum('nominal') ?? 0;
            $item->total_penarikan = TransTabungan::where('id_anggota', $item->id)
                ->where('jenis', 'penarikan')
                ->sum('nominal') ?? 0;
            return $item;
        });

        return view('admin.tabungan.saldo-nasabah', compact('nasabah'));
    }

    /**
     * Get saldo nasabah.
     */
    private function getSaldoNasabah($idAnggota)
    {
        $totalSetoran = TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'setoran')
            ->sum('nominal') ?? 0;

        $totalPenarikan = TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'penarikan')
            ->sum('nominal') ?? 0;

        return max(0, $totalSetoran - $totalPenarikan);
    }
}

