<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPinjaman;
use App\Models\PinjamanH;
use App\Models\TempoPinjamanB;
use App\Models\TempoPinjamanM;
use App\Models\Nasabah;
use Illuminate\Http\Request;

class PinjamanController extends Controller
{
    /**
     * Display dashboard pinjaman admin.
     */
    public function index()
    {
        // Statistik pinjaman
        $stats = [
            'total_pengajuan_pending' => PengajuanPinjaman::whereDoesntHave('pinjaman')->count(),
            'total_pinjaman_aktif' => PinjamanH::where('lunas', 'belum')->whereIn('status', ['pencairan', 'telaksana'])->count(),
            'total_pinjaman_lunas' => PinjamanH::where('lunas', 'lunas')->count(),
            'total_pinjaman_hari_ini' => PinjamanH::whereDate('created_at', today())->count(),
            'total_nominal_pinjaman_aktif' => PinjamanH::where('lunas', 'belum')->whereIn('status', ['pencairan', 'telaksana'])->sum('jumlah_pinjam') ?? 0,
            'total_angsuran_telat' => $this->getTotalAngsuranTelat(),
        ];

        // Pengajuan terbaru (pending)
        $pengajuan_terbaru = PengajuanPinjaman::whereDoesntHave('pinjaman')
            ->with('nasabah.user')
            ->latest()
            ->take(5)
            ->get();

        // Pinjaman aktif terbaru
        $pinjaman_aktif_terbaru = PinjamanH::where('lunas', 'belum')
            ->whereIn('status', ['pencairan', 'telaksana'])
            ->with('nasabah.user')
            ->latest()
            ->take(5)
            ->get();

        // Angsuran jatuh tempo hari ini
        $angsuran_jatuh_tempo = $this->getAngsuranJatuhTempo();

        return view('admin.pinjaman.index', compact(
            'stats',
            'pengajuan_terbaru',
            'pinjaman_aktif_terbaru',
            'angsuran_jatuh_tempo'
        ));
    }

    /**
     * Display list of pengajuan pinjaman.
     */
    public function pengajuan(Request $request)
    {
        $query = PengajuanPinjaman::with('nasabah.user')
            ->latest();

        // Filter by status (pengajuan yang belum punya pinjaman = pending)
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'pending') {
                $query->whereDoesntHave('pinjaman');
            } else {
                $query->whereHas('pinjaman');
            }
        } else {
            // Default show pending
            $query->whereDoesntHave('pinjaman');
        }

        // Filter by jenis
        if ($request->has('jenis') && $request->jenis !== '') {
            $query->where('jenis', $request->jenis);
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

        return view('admin.pinjaman.pengajuan', compact('pengajuan'));
    }

    /**
     * Display detail pengajuan pinjaman.
     */
    public function detailPengajuan($id)
    {
        $pengajuan = PengajuanPinjaman::with(['nasabah.user', 'nasabah.dataKtp', 'nasabah.pekerjaan', 'pinjaman'])
            ->findOrFail($id);

        return view('admin.pinjaman.detail-pengajuan', compact('pengajuan'));
    }

    /**
     * Approve pengajuan pinjaman.
     */
    public function approvePengajuan(Request $request, $id)
    {
        $request->validate([
            'bunga' => 'required|numeric|min:0|max:100',
            'bunga_rp' => 'required|numeric|min:0',
            'denda_persen' => 'required|numeric|min:0|max:100',
        ]);

        $pengajuan = PengajuanPinjaman::findOrFail($id);

        // Create pinjaman
        $pinjaman = PinjamanH::create([
            'id_anggota' => $pengajuan->id_anggota,
            'id_pengajuan' => $pengajuan->id,
            'jumlah_pinjam' => $pengajuan->nominal,
            'lama_pinjam' => (int)$pengajuan->durasi,
            'jenis' => $pengajuan->jenis,
            'bunga' => $request->bunga / 100, // Convert to decimal
            'bunga_rp' => $request->bunga_rp,
            'denda_persen' => $request->denda_persen,
            'tgl_pinjam' => now(),
            'status' => 'pencairan',
            'lunas' => 'belum',
        ]);

        // Generate jadwal angsuran
        $this->generateJadwalAngsuran($pinjaman);

        return redirect()->route('admin.pinjaman.pengajuan')
            ->with('success', 'Pengajuan pinjaman berhasil disetujui');
    }

    /**
     * Reject pengajuan pinjaman.
     */
    public function rejectPengajuan(Request $request, $id)
    {
        $request->validate([
            'keterangan' => 'required|string'
        ]);

        $pengajuan = PengajuanPinjaman::findOrFail($id);
        // Update status pengajuan jika ada field status
        // Note: Sesuai struktur, pengajuan tidak punya status field, jadi kita skip atau update keterangan
        $pengajuan->update([
            'keterangan' => $request->keterangan
        ]);

        return redirect()->route('admin.pinjaman.pengajuan')
            ->with('success', 'Pengajuan pinjaman ditolak');
    }

    /**
     * Display list of active pinjaman.
     */
    public function pinjamanAktif(Request $request)
    {
        $query = PinjamanH::with('nasabah.user')
            ->where('lunas', 'belum')
            ->whereIn('status', ['pencairan', 'telaksana'])
            ->latest();

        // Filter by jenis
        if ($request->has('jenis') && $request->jenis !== '') {
            $query->where('jenis', $request->jenis);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('nasabah.user', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $pinjaman = $query->paginate(15);

        return view('admin.pinjaman.pinjaman-aktif', compact('pinjaman'));
    }

    /**
     * Display detail pinjaman.
     */
    public function detailPinjaman($id)
    {
        $pinjaman = PinjamanH::with([
            'nasabah.user',
            'nasabah.dataKtp',
            'nasabah.pekerjaan',
            'pengajuan',
            'tempoBulanan',
            'tempoMingguan'
        ])->findOrFail($id);

        // Get angsuran berdasarkan jenis
        $angsuran = $pinjaman->jenis === 'bulanan' 
            ? $pinjaman->tempoBulanan()->orderBy('no_urut')->get()
            : $pinjaman->tempoMingguan()->orderBy('no_urut')->get();

        return view('admin.pinjaman.detail-pinjaman', compact('pinjaman', 'angsuran'));
    }

    /**
     * Display list of angsuran/tempo.
     */
    public function angsuran(Request $request)
    {
        $query = null;
        $jenis = $request->get('jenis', 'bulanan');

        if ($jenis === 'bulanan') {
            $query = TempoPinjamanB::with(['pinjaman.nasabah.user', 'nasabah.user'])
                ->latest('tgl_jatuh_tempo');
        } else {
            $query = TempoPinjamanM::with(['pinjaman.nasabah.user', 'nasabah.user'])
                ->latest('tgl_jatuh_tempo');
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status_bayar', $request->status);
        }

        // Filter by date
        if ($request->has('tanggal_dari') && $request->tanggal_dari !== '') {
            $query->whereDate('tgl_jatuh_tempo', '>=', $request->tanggal_dari);
        }

        if ($request->has('tanggal_sampai') && $request->tanggal_sampai !== '') {
            $query->whereDate('tgl_jatuh_tempo', '<=', $request->tanggal_sampai);
        }

        // Search
        if ($request->has('search') && $request->search !== '') {
            $search = $request->search;
            $query->whereHas('nasabah.user', function($q) use ($search) {
                $q->where('nama', 'like', "%{$search}%")
                  ->orWhere('email', 'like', "%{$search}%");
            });
        }

        $angsuran = $query->paginate(20);

        return view('admin.pinjaman.angsuran', compact('angsuran', 'jenis'));
    }

    /**
     * Generate jadwal angsuran untuk pinjaman.
     */
    private function generateJadwalAngsuran(PinjamanH $pinjaman)
    {
        $jumlahAngsuran = $pinjaman->lama_pinjam;
        $jumlahPinjam = $pinjaman->jumlah_pinjam;
        $bungaRp = $pinjaman->bunga_rp;
        $totalTagihan = $jumlahPinjam + $bungaRp;
        $jumlahPerAngsuran = $totalTagihan / $jumlahAngsuran;

        $tanggalMulai = $pinjaman->tgl_pinjam;

        for ($i = 1; $i <= $jumlahAngsuran; $i++) {
            $tanggalJatuhTempo = clone $tanggalMulai;
            
            if ($pinjaman->jenis === 'bulanan') {
                $tanggalJatuhTempo->addMonths($i);
            } else {
                $tanggalJatuhTempo->addWeeks($i);
            }

            $data = [
                'pinjaman_id' => $pinjaman->id,
                'anggota_id' => $pinjaman->id_anggota,
                'no_urut' => $i,
                'tgl_jatuh_tempo' => $tanggalJatuhTempo,
                'jumlah_tagihan' => $jumlahPerAngsuran,
                'jumlah_terbayar' => 0,
                'status_bayar' => 'belum',
            ];

            if ($pinjaman->jenis === 'bulanan') {
                TempoPinjamanB::create($data);
            } else {
                TempoPinjamanM::create($data);
            }
        }
    }

    /**
     * Get total angsuran telat.
     */
    private function getTotalAngsuranTelat()
    {
        $bulanan = TempoPinjamanB::where('status_bayar', 'telat')
            ->whereDate('tgl_jatuh_tempo', '<', now())
            ->count();

        $mingguan = TempoPinjamanM::where('status_bayar', 'telat')
            ->whereDate('tgl_jatuh_tempo', '<', now())
            ->count();

        return $bulanan + $mingguan;
    }

    /**
     * Get angsuran jatuh tempo hari ini.
     */
    private function getAngsuranJatuhTempo()
    {
        $bulanan = TempoPinjamanB::whereDate('tgl_jatuh_tempo', today())
            ->where('status_bayar', 'belum')
            ->with(['pinjaman.nasabah.user', 'nasabah.user'])
            ->get()
            ->map(function($item) {
                $item->jenis = 'bulanan';
                return $item;
            });

        $mingguan = TempoPinjamanM::whereDate('tgl_jatuh_tempo', today())
            ->where('status_bayar', 'belum')
            ->with(['pinjaman.nasabah.user', 'nasabah.user'])
            ->get()
            ->map(function($item) {
                $item->jenis = 'mingguan';
                return $item;
            });

        return $bulanan->merge($mingguan)->take(5);
    }

    /**
     * Display detail angsuran.
     */
    public function detailAngsuran(Request $request, $id)
    {
        $jenis = $request->get('jenis', 'bulanan');
        
        if ($jenis === 'bulanan') {
            $angsuran = TempoPinjamanB::with(['pinjaman.nasabah.user', 'nasabah.user', 'pinjaman'])
                ->findOrFail($id);
        } else {
            $angsuran = TempoPinjamanM::with(['pinjaman.nasabah.user', 'nasabah.user', 'pinjaman'])
                ->findOrFail($id);
        }

        return view('admin.pinjaman.detail-angsuran', compact('angsuran', 'jenis'));
    }

    /**
     * Update pembayaran angsuran.
     */
    public function updatePembayaranAngsuran(Request $request, $id)
    {
        $request->validate([
            'jumlah_bayar' => 'required|numeric|min:0',
            'jenis' => 'required|in:bulanan,mingguan',
        ]);

        if ($request->jenis === 'bulanan') {
            $angsuran = TempoPinjamanB::findOrFail($id);
        } else {
            $angsuran = TempoPinjamanM::findOrFail($id);
        }

        $jumlahTerbayar = ($angsuran->jumlah_terbayar ?? 0) + $request->jumlah_bayar;
        $statusBayar = 'belum';
        
        if ($jumlahTerbayar >= $angsuran->jumlah_tagihan) {
            $statusBayar = 'lunas';
            $jumlahTerbayar = $angsuran->jumlah_tagihan; // Jangan lebih dari tagihan
        } elseif ($angsuran->tgl_jatuh_tempo < now() && $jumlahTerbayar < $angsuran->jumlah_tagihan) {
            $statusBayar = 'telat';
        }

        $angsuran->update([
            'jumlah_terbayar' => $jumlahTerbayar,
            'status_bayar' => $statusBayar,
        ]);

        // Check if all angsuran sudah lunas, update pinjaman
        $pinjaman = $angsuran->pinjaman;
        if ($pinjaman) {
            $allAngsuran = $pinjaman->jenis === 'bulanan' 
                ? $pinjaman->tempoBulanan 
                : $pinjaman->tempoMingguan;
            
            $allLunas = $allAngsuran->every(function($item) {
                return $item->status_bayar === 'lunas';
            });

            if ($allLunas) {
                $pinjaman->update(['lunas' => 'lunas']);
            }
        }

        return redirect()->back()
            ->with('success', 'Pembayaran angsuran berhasil diperbarui');
    }
}
