<?php

namespace App\Http\Controllers\Nasabah;

use App\Http\Controllers\Controller;
use App\Models\PengajuanPinjaman;
use App\Models\PinjamanH;
use App\Models\TempoPinjamanB;
use App\Models\TempoPinjamanM;
use Illuminate\Http\Request;
use Carbon\Carbon;

class PinjamanController extends Controller
{
    /**
     * Show the pinjaman dashboard.
     */
    public function index()
    {
        $idAnggota = 1; // TODO: Get from auth

        // Get pinjaman aktif
        $pinjamanAktif = PinjamanH::where('id_anggota', $idAnggota)
            ->whereIn('status', ['pencairan', 'telaksana'])
            ->where('lunas', 'belum')
            ->with(['pengajuan', 'tempoBulanan', 'tempoMingguan'])
            ->latest()
            ->get();

        // Calculate total pinjaman aktif
        $totalPinjamanAktif = $pinjamanAktif->sum('jumlah_pinjam') ?? 0;

        // Calculate sisa pinjaman (total pinjaman - total terbayar)
        $sisaPinjaman = 0;
        foreach ($pinjamanAktif as $pinjaman) {
            $totalTerbayar = 0;
            if ($pinjaman->jenis === 'bulanan') {
                $totalTerbayar = $pinjaman->tempoBulanan->sum('jumlah_terbayar') ?? 0;
            } else {
                $totalTerbayar = $pinjaman->tempoMingguan->sum('jumlah_terbayar') ?? 0;
            }
            $totalTagihan = $pinjaman->jumlah_pinjam + $pinjaman->bunga_rp;
            $sisaPinjaman += max(0, $totalTagihan - $totalTerbayar);
        }

        // Get angsuran terdekat (jatuh tempo dalam 7 hari ke depan)
        $angsuranTerdekat = collect();
        foreach ($pinjamanAktif as $pinjaman) {
            if ($pinjaman->jenis === 'bulanan') {
                $tempo = $pinjaman->tempoBulanan()
                    ->where('status_bayar', 'belum')
                    ->whereBetween('tgl_jatuh_tempo', [now(), now()->addDays(7)])
                    ->orderBy('tgl_jatuh_tempo')
                    ->first();
            } else {
                $tempo = $pinjaman->tempoMingguan()
                    ->where('status_bayar', 'belum')
                    ->whereBetween('tgl_jatuh_tempo', [now(), now()->addDays(7)])
                    ->orderBy('tgl_jatuh_tempo')
                    ->first();
            }
            if ($tempo) {
                $tempo->pinjaman = $pinjaman;
                $angsuranTerdekat->push($tempo);
            }
        }
        $angsuranTerdekat = $angsuranTerdekat->sortBy('tgl_jatuh_tempo')->take(5);

        // Get total angsuran telat
        $totalAngsuranTelat = 0;
        foreach ($pinjamanAktif as $pinjaman) {
            if ($pinjaman->jenis === 'bulanan') {
                $telat = $pinjaman->tempoBulanan()
                    ->where('status_bayar', 'telat')
                    ->orWhere(function($q) {
                        $q->where('status_bayar', 'belum')
                          ->whereDate('tgl_jatuh_tempo', '<', now());
                    })
                    ->count();
            } else {
                $telat = $pinjaman->tempoMingguan()
                    ->where('status_bayar', 'telat')
                    ->orWhere(function($q) {
                        $q->where('status_bayar', 'belum')
                          ->whereDate('tgl_jatuh_tempo', '<', now());
                    })
                    ->count();
            }
            $totalAngsuranTelat += $telat;
        }

        // Get all angsuran untuk tabel
        $semuaAngsuran = collect();
        foreach ($pinjamanAktif as $pinjaman) {
            if ($pinjaman->jenis === 'bulanan') {
                $tempos = $pinjaman->tempoBulanan()->orderBy('no_urut')->get();
            } else {
                $tempos = $pinjaman->tempoMingguan()->orderBy('no_urut')->get();
            }
            foreach ($tempos as $tempo) {
                $tempo->pinjaman = $pinjaman;
                $semuaAngsuran->push($tempo);
            }
        }
        $semuaAngsuran = $semuaAngsuran->sortBy('tgl_jatuh_tempo')->take(10);

        return view('nasabah.pinjaman.index', [
            'pinjamanAktif' => $pinjamanAktif,
            'totalPinjamanAktif' => $totalPinjamanAktif,
            'sisaPinjaman' => $sisaPinjaman,
            'angsuranTerdekat' => $angsuranTerdekat,
            'totalAngsuranTelat' => $totalAngsuranTelat,
            'semuaAngsuran' => $semuaAngsuran,
        ]);
    }

    /**
     * Show the pengajuan pinjaman page.
     */
    public function pengajuanPinjaman()
    {
        $idAnggota = 1; // TODO: Get from auth

        // Get riwayat pengajuan
        $riwayatPengajuan = PengajuanPinjaman::where('id_anggota', $idAnggota)
            ->with('pinjaman')
            ->latest()
            ->take(10)
            ->get();

        return view('nasabah.pinjaman.pengajuan-pinjaman', [
            'riwayatPengajuan' => $riwayatPengajuan,
        ]);
    }

    /**
     * Submit pengajuan pinjaman.
     */
    public function submitPengajuan(Request $request)
    {
        $request->validate([
            'nominal' => 'required|numeric|min:100000',
            'jenis' => 'required|in:bulanan,mingguan',
            'durasi' => 'required|integer|min:1|max:12',
            'keterangan' => 'nullable|string|max:500',
        ]);

        $idAnggota = 1; // TODO: Get from auth

        PengajuanPinjaman::create([
            'id_anggota' => $idAnggota,
            'tgl_pengajuan' => now(),
            'nominal' => $request->nominal,
            'jenis' => $request->jenis,
            'durasi' => (string)$request->durasi,
            'status' => '1', // Pending
            'keterangan' => $request->keterangan,
        ]);

        return redirect()->route('nasabah.pinjaman.status-pengajuan')
            ->with('success', 'Pengajuan pinjaman berhasil dikirim!');
    }

    /**
     * Show status pengajuan pinjaman.
     */
    public function statusPengajuan(Request $request)
    {
        $idAnggota = 1; // TODO: Get from auth

        $query = PengajuanPinjaman::where('id_anggota', $idAnggota)
            ->with('pinjaman')
            ->latest();

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            if ($request->status === 'pending') {
                $query->whereDoesntHave('pinjaman');
            } elseif ($request->status === 'approved') {
                $query->whereHas('pinjaman');
            }
        }

        // Filter by jenis
        if ($request->has('jenis') && $request->jenis !== '') {
            $query->where('jenis', $request->jenis);
        }

        $pengajuan = $query->paginate(15);

        return view('nasabah.pinjaman.status-pengajuan', [
            'pengajuan' => $pengajuan,
        ]);
    }

    /**
     * Show detail pengajuan pinjaman.
     */
    public function detailPengajuan($id)
    {
        $idAnggota = 1; // TODO: Get from auth

        $pengajuan = PengajuanPinjaman::where('id_anggota', $idAnggota)
            ->with(['pinjaman', 'nasabah.user'])
            ->findOrFail($id);

        return view('nasabah.pinjaman.detail-pengajuan', [
            'pengajuan' => $pengajuan,
        ]);
    }

    /**
     * Show pinjaman aktif list.
     */
    public function pinjamanAktif(Request $request)
    {
        $idAnggota = 1; // TODO: Get from auth

        $query = PinjamanH::where('id_anggota', $idAnggota)
            ->whereIn('status', ['pencairan', 'telaksana'])
            ->where('lunas', 'belum')
            ->with(['pengajuan', 'tempoBulanan', 'tempoMingguan'])
            ->latest();

        // Filter by jenis
        if ($request->has('jenis') && $request->jenis !== '') {
            $query->where('jenis', $request->jenis);
        }

        // Filter by status
        if ($request->has('status') && $request->status !== '') {
            $query->where('status', $request->status);
        }

        $pinjaman = $query->paginate(15);

        return view('nasabah.pinjaman.pinjaman-aktif', [
            'pinjaman' => $pinjaman,
        ]);
    }

    /**
     * Show detail pinjaman.
     */
    public function detailPinjaman($id)
    {
        $idAnggota = 1; // TODO: Get from auth

        $pinjaman = PinjamanH::where('id_anggota', $idAnggota)
            ->with([
                'pengajuan',
                'nasabah.user',
                'tempoBulanan',
                'tempoMingguan'
            ])
            ->findOrFail($id);

        // Get angsuran berdasarkan jenis
        $angsuran = $pinjaman->jenis === 'bulanan' 
            ? $pinjaman->tempoBulanan()->orderBy('no_urut')->get()
            : $pinjaman->tempoMingguan()->orderBy('no_urut')->get();

        // Calculate statistics
        $totalTagihan = $pinjaman->jumlah_pinjam + $pinjaman->bunga_rp;
        $totalTerbayar = $angsuran->sum('jumlah_terbayar') ?? 0;
        $sisaPinjaman = max(0, $totalTagihan - $totalTerbayar);
        $progress = $totalTagihan > 0 ? ($totalTerbayar / $totalTagihan) * 100 : 0;
        $angsuranLunas = $angsuran->where('status_bayar', 'lunas')->count();
        $totalAngsuran = $angsuran->count();

        return view('nasabah.pinjaman.detail-pinjaman', [
            'pinjaman' => $pinjaman,
            'angsuran' => $angsuran,
            'totalTagihan' => $totalTagihan,
            'totalTerbayar' => $totalTerbayar,
            'sisaPinjaman' => $sisaPinjaman,
            'progress' => $progress,
            'angsuranLunas' => $angsuranLunas,
            'totalAngsuran' => $totalAngsuran,
        ]);
    }

    /**
     * Show angsuran list.
     */
    public function angsuran(Request $request)
    {
        $idAnggota = 1; // TODO: Get from auth

        $jenis = $request->get('jenis', 'bulanan');
        $query = null;

        if ($jenis === 'bulanan') {
            $query = TempoPinjamanB::where('anggota_id', $idAnggota)
                ->with(['pinjaman.pengajuan'])
                ->latest('tgl_jatuh_tempo');
        } else {
            $query = TempoPinjamanM::where('anggota_id', $idAnggota)
                ->with(['pinjaman.pengajuan'])
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

        $angsuran = $query->paginate(20);

        return view('nasabah.pinjaman.angsuran', [
            'angsuran' => $angsuran,
            'jenis' => $jenis,
        ]);
    }

    /**
     * Show detail angsuran.
     */
    public function detailAngsuran(Request $request, $id)
    {
        $idAnggota = 1; // TODO: Get from auth
        $jenis = $request->get('jenis', 'bulanan');

        if ($jenis === 'bulanan') {
            $angsuran = TempoPinjamanB::where('anggota_id', $idAnggota)
                ->with(['pinjaman.pengajuan', 'nasabah.user'])
                ->findOrFail($id);
        } else {
            $angsuran = TempoPinjamanM::where('anggota_id', $idAnggota)
                ->with(['pinjaman.pengajuan', 'nasabah.user'])
                ->findOrFail($id);
        }

        $sisaTagihan = max(0, $angsuran->jumlah_tagihan - ($angsuran->jumlah_terbayar ?? 0));
        $isTelat = $angsuran->tgl_jatuh_tempo < now() && $angsuran->status_bayar !== 'lunas';

        return view('nasabah.pinjaman.detail-angsuran', [
            'angsuran' => $angsuran,
            'jenis' => $jenis,
            'sisaTagihan' => $sisaTagihan,
            'isTelat' => $isTelat,
        ]);
    }
}
