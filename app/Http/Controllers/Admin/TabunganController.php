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
use Illuminate\Support\Facades\Storage;

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
        $query = PengajuanTabungan::with(['nasabah.user', 'buktiFoto', 'janjiTemu'])
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
        $pengajuan = PengajuanTabungan::with(['nasabah.user', 'nasabah.dataKtp', 'nasabah.dataRek', 'buktiFoto', 'janjiTemu.lokasi'])
            ->findOrFail($id);

        return view('admin.tabungan.detail-pengajuan-setor', compact('pengajuan'));
    }

    /**
     * Approve pengajuan setoran.
     */
    public function approveSetor(Request $request, $id)
    {
        $pengajuan = PengajuanTabungan::with(['buktiFoto', 'janjiTemu', 'transTabungan'])->findOrFail($id);
        
        // Update status to approved (status '2')
        $pengajuan->update(['status' => '2']);

        // Get nominal from pengajuan (or janji temu for tunai)
        $nominal = $pengajuan->nominal ?? 0;
        if ($pengajuan->janjiTemu && $nominal == 0) {
            $nominal = $pengajuan->janjiTemu->nominal ?? 0;
        }

        // Validate nominal
        if ($nominal == 0 || $nominal < 10000) {
            return redirect()->back()
                ->with('error', 'Nominal tidak valid. Minimal Rp 10.000');
        }

        // Create transaksi tabungan jika belum ada
        // Pastikan tidak ada duplikasi transaksi
        if ($pengajuan->transTabungan->count() == 0) {
            // Get jns_akun for Tabungan
            $jnsAkun = \App\Models\JnsAkun::where('kode_akun', 'TAB')->first();
            
            // Generate ID transaksi
            $idTransaksi = TransTabungan::generateIdTransaksi($jnsAkun->prefix_id ?? 'TAB');

            TransTabungan::create([
                'id_transaksi' => $idTransaksi,
                'id_pengajuan_setor' => $pengajuan->id,
                'id_anggota' => $pengajuan->id_anggota,
                'id_jns_akun' => $jnsAkun->id ?? null,
                'nominal' => $nominal,
                'keterangan' => $pengajuan->keterangan ?? 'Setoran tabungan disetujui',
                'jenis' => 'setoran',
                'via' => $pengajuan->janjiTemu ? 'cash' : ($request->via ?? 'transfer'),
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
        
        // Validate for transfer
        if ($pengajuan->metode_transfer == 'transfer') {
            $request->validate([
                'foto_bukti_tf_admin' => 'required|image|max:5120',
                'bank_pengirim' => 'required|string|max:50',
            ]);
        }
        
        // Check saldo
        $saldo = $this->getSaldoNasabah($pengajuan->id_anggota);
        
        if ($saldo < $pengajuan->nominal) {
            return redirect()->back()
                ->with('error', 'Saldo nasabah tidak mencukupi');
        }

        // Upload foto bukti TF admin (jika transfer)
        $fotoBuktiPath = null;
        if ($pengajuan->metode_transfer == 'transfer' && $request->hasFile('foto_bukti_tf_admin')) {
            $fotoBuktiPath = $request->file('foto_bukti_tf_admin')->store('bukti_tf_admin', 'public');
        }

        // Update pengajuan with foto
        $pengajuan->update([
            'status' => '2',
            'foto_bukti_tf_admin' => $fotoBuktiPath,
        ]);

        // Get jns_akun for Tabungan
        $jnsAkun = \App\Models\JnsAkun::where('kode_akun', 'TAB')->first();
        
        // Generate ID transaksi kompleks
        $idTransaksi = TransTabungan::generateIdTransaksi($jnsAkun->prefix_id ?? 'TAB');

        // Create transaksi penarikan
        TransTabungan::create([
            'id_transaksi' => $idTransaksi,
            'id_pengajuan_tarik' => $pengajuan->id,
            'id_anggota' => $pengajuan->id_anggota,
            'id_jns_akun' => $jnsAkun->id ?? null,
            'nominal' => $pengajuan->nominal,
            'keterangan' => $pengajuan->keterangan,
            'jenis' => 'penarikan',
            'via' => $pengajuan->metode_transfer == 'transfer' ? 'transfer' : 'cash',
            'tgl_transaksi' => now(),
        ]);

        return redirect()->route('admin.tabungan.pengajuan-tarik')
            ->with('success', 'Pengajuan penarikan berhasil disetujui dan transfer telah dilakukan');
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
        $janjiTemu = JanjiTemuTabungan::with(['pengajuan.nasabah.user', 'pengajuan.nasabah.dataKtp', 'pengajuan.nasabah.dataRek', 'lokasi', 'pengajuan.transTabungan'])
            ->findOrFail($id);

        return view('admin.tabungan.detail-janji-temu', compact('janjiTemu'));
    }

    /**
     * Create transaksi tabungan langsung dari janji temu.
     */
    public function createTransFromJanjiTemu(Request $request, $id)
    {
        $request->validate([
            'nominal' => 'required|string',
            'keterangan' => 'nullable|string|max:500',
            'foto_penerimaan' => 'nullable|image|max:5120',
            'tgl_transaksi' => 'required|date',
        ]);

        // Parse nominal from formatted currency string
        $nominal = (float) str_replace(['.', ','], '', $request->nominal);
        
        if ($nominal < 10000) {
            return redirect()->back()
                ->with('error', 'Nominal minimal Rp 10.000')
                ->withInput();
        }

        $janjiTemu = JanjiTemuTabungan::with('pengajuan')->findOrFail($id);
        $pengajuan = $janjiTemu->pengajuan;

        // Check if transaksi already exists
        if ($pengajuan->transTabungan->count() > 0) {
            return redirect()->back()
                ->with('error', 'Transaksi untuk janji temu ini sudah pernah dibuat');
        }

        // Handle foto penerimaan jika ada
        $fotoPenerimaan = null;
        if ($request->hasFile('foto_penerimaan')) {
            $fotoPenerimaan = $request->file('foto_penerimaan')->store('bukti_tabungan', 'public');
            
            // Simpan juga ke bukti foto tabungan
            BuktiFotoTabungan::create([
                'id_pengajuan' => $pengajuan->id,
                'file_photo' => $fotoPenerimaan,
                'jenis' => 'tabungan',
                'nominal' => $nominal,
                'keterangan' => $request->keterangan ?? 'Bukti penerimaan dari janji temu',
            ]);
        }

        // Update status pengajuan menjadi approved jika belum
        if ($pengajuan->status == '1') {
            $pengajuan->update(['status' => '2']);
        }

        // Create transaksi tabungan
        $transaksi = TransTabungan::create([
            'id_pengajuan_setor' => $pengajuan->id,
            'id_anggota' => $pengajuan->id_anggota,
            'nominal' => $nominal,
            'keterangan' => $request->keterangan ?? 'Setoran tabungan dari janji temu',
            'jenis' => 'setoran',
            'via' => 'cash',
            'tgl_transaksi' => $request->tgl_transaksi,
        ]);

        return redirect()->route('admin.tabungan.detail-janji-temu', $id)
            ->with('success', 'Transaksi tabungan berhasil dibuat dari janji temu');
    }

    /**
     * Edit pengajuan setoran.
     */
    public function editPengajuanSetor(Request $request, $id)
    {
        $request->validate([
            'nominal' => 'nullable|numeric|min:10000',
            'keterangan' => 'nullable|string|max:500',
            'status' => 'required|in:1,2,3',
        ]);

        $pengajuan = PengajuanTabungan::findOrFail($id);
        
        $updateData = [
            'keterangan' => $request->keterangan,
            'status' => $request->status,
        ];

        // Update nominal jika diisi
        if ($request->has('nominal') && $request->nominal) {
            $updateData['nominal'] = $request->nominal;
        }

        $pengajuan->update($updateData);

        return redirect()->route('admin.tabungan.detail-pengajuan-setor', $id)
            ->with('success', 'Pengajuan setoran berhasil diupdate');
    }

    /**
     * Delete pengajuan setoran.
     */
    public function deletePengajuanSetor($id)
    {
        $pengajuan = PengajuanTabungan::findOrFail($id);
        
        // Hanya bisa delete jika status masih pending dan belum ada transaksi
        if ($pengajuan->status != '1') {
            return redirect()->back()
                ->with('error', 'Hanya pengajuan dengan status pending yang bisa dihapus');
        }

        if ($pengajuan->transTabungan->count() > 0) {
            return redirect()->back()
                ->with('error', 'Pengajuan yang sudah memiliki transaksi tidak bisa dihapus');
        }

        // Delete bukti foto files
        foreach ($pengajuan->buktiFoto as $bukti) {
            if (Storage::disk('public')->exists($bukti->file_photo)) {
                Storage::disk('public')->delete($bukti->file_photo);
            }
        }

        $pengajuan->delete();

        return redirect()->route('admin.tabungan.pengajuan-setor')
            ->with('success', 'Pengajuan setoran berhasil dihapus');
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
        // Hitung dari trans_tabungan yang sudah ada
        $totalSetoran = TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'setoran')
            ->sum('nominal') ?? 0;

        $totalPenarikan = TransTabungan::where('id_anggota', $idAnggota)
            ->where('jenis', 'penarikan')
            ->sum('nominal') ?? 0;

        // Tambahkan setoran dari pengajuan yang sudah approved tapi belum ada transaksi
        $pengajuanApproved = PengajuanTabungan::where('id_anggota', $idAnggota)
            ->where('status', '2') // Approved
            ->whereDoesntHave('transTabungan')
            ->with('janjiTemu')
            ->get();

        foreach ($pengajuanApproved as $pengajuan) {
            // Gunakan nominal dari pengajuan (bukan dari bukti foto)
            $nominal = $pengajuan->nominal ?? 0;
            
            // Jika nominal masih 0, coba ambil dari janji temu (untuk backward compatibility)
            if ($nominal == 0 && $pengajuan->janjiTemu) {
                $nominal = $pengajuan->janjiTemu->nominal ?? 0;
            }
            
            $totalSetoran += $nominal;
        }

        return max(0, $totalSetoran - $totalPenarikan);
    }

    /**
     * Create manual transaction form.
     */
    public function createTransaksi()
    {
        $nasabah = Nasabah::with('user')->get();
        $jnsAkun = \App\Models\JnsAkun::where('is_active', true)->get();

        return view('admin.tabungan.create-transaksi', compact('nasabah', 'jnsAkun'));
    }

    /**
     * Store manual transaction.
     */
    public function storeTransaksi(Request $request)
    {
        $request->validate([
            'id_anggota' => 'required|exists:tbl_nasabah,id',
            'id_jns_akun' => 'required|exists:jns_akun,id',
            'jenis' => 'required|in:setoran,penarikan',
            'nominal' => 'required|numeric|min:10000',
            'via' => 'required|in:transfer,cash',
            'keterangan' => 'nullable|string|max:500',
            'tgl_transaksi' => 'required|date',
            'foto_bukti' => 'nullable|image|max:5120',
        ]);

        // If penarikan, check saldo
        if ($request->jenis == 'penarikan') {
            $saldo = $this->getSaldoNasabah($request->id_anggota);
            if ($saldo < $request->nominal) {
                return redirect()->back()
                    ->with('error', 'Saldo nasabah tidak mencukupi')
                    ->withInput();
            }
        }

        // Get jns_akun prefix
        $jnsAkun = \App\Models\JnsAkun::find($request->id_jns_akun);
        
        // Generate ID transaksi
        $idTransaksi = TransTabungan::generateIdTransaksi($jnsAkun->prefix_id ?? 'TAB');

        // Upload foto bukti if exists
        $fotoBukti = null;
        if ($request->hasFile('foto_bukti')) {
            $fotoBukti = $request->file('foto_bukti')->store('bukti_transaksi', 'public');
        }

        // Create transaksi
        $transaksi = TransTabungan::create([
            'id_transaksi' => $idTransaksi,
            'id_anggota' => $request->id_anggota,
            'id_jns_akun' => $request->id_jns_akun,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan . ($fotoBukti ? ' | Foto: ' . $fotoBukti : ''),
            'jenis' => $request->jenis,
            'via' => $request->via,
            'tgl_transaksi' => $request->tgl_transaksi,
        ]);

        return redirect()->route('admin.tabungan.transaksi')
            ->with('success', "Transaksi {$request->jenis} berhasil dibuat dengan ID: {$idTransaksi}");
    }

    /**
     * Edit manual transaction form.
     */
    public function editTransaksi($id)
    {
        $transaksi = TransTabungan::with(['nasabah.user', 'jnsAkun'])->findOrFail($id);

        // Only allow edit if created manually (no pengajuan)
        if ($transaksi->id_pengajuan_setor || $transaksi->id_pengajuan_tarik) {
            return redirect()->back()
                ->with('error', 'Transaksi dari pengajuan tidak dapat diedit');
        }

        $nasabah = Nasabah::with('user')->get();
        $jnsAkun = \App\Models\JnsAkun::where('is_active', true)->get();

        return view('admin.tabungan.edit-transaksi', compact('transaksi', 'nasabah', 'jnsAkun'));
    }

    /**
     * Update manual transaction.
     */
    public function updateTransaksi(Request $request, $id)
    {
        $transaksi = TransTabungan::findOrFail($id);

        // Only allow update if created manually
        if ($transaksi->id_pengajuan_setor || $transaksi->id_pengajuan_tarik) {
            return redirect()->back()
                ->with('error', 'Transaksi dari pengajuan tidak dapat diupdate');
        }

        $request->validate([
            'nominal' => 'required|numeric|min:10000',
            'keterangan' => 'nullable|string|max:500',
            'tgl_transaksi' => 'required|date',
        ]);

        // If changing to penarikan or increasing penarikan, check saldo
        if ($request->jenis == 'penarikan') {
            $saldo = $this->getSaldoNasabah($transaksi->id_anggota);
            $saldoWithoutThis = $saldo + ($transaksi->jenis == 'penarikan' ? $transaksi->nominal : 0);
            
            if ($saldoWithoutThis < $request->nominal) {
                return redirect()->back()
                    ->with('error', 'Saldo nasabah tidak mencukupi untuk nominal ini')
                    ->withInput();
            }
        }

        $transaksi->update([
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan,
            'tgl_transaksi' => $request->tgl_transaksi,
        ]);

        return redirect()->route('admin.tabungan.detail-transaksi', $id)
            ->with('success', 'Transaksi berhasil diupdate');
    }

    /**
     * Delete manual transaction.
     */
    public function destroyTransaksi($id)
    {
        $transaksi = TransTabungan::findOrFail($id);

        // Only allow delete if created manually
        if ($transaksi->id_pengajuan_setor || $transaksi->id_pengajuan_tarik) {
            return redirect()->back()
                ->with('error', 'Transaksi dari pengajuan tidak dapat dihapus');
        }

        $transaksi->delete();

        return redirect()->route('admin.tabungan.transaksi')
            ->with('success', 'Transaksi berhasil dihapus');
    }
}

