<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\PengajuanTabungan;
use App\Models\PengajuanPenarikanTabungan;
use App\Models\TransTabungan;
use App\Models\JanjiTemuTabungan;
use App\Models\Nasabah;
use App\Models\BuktiFotoTabungan;
use App\Services\ActivityLogService;
use App\Services\PettyCashConstants;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helpers\IdGenerator;
use App\Models\BuktiFoto;
use App\Models\BiayaTransfer;
use App\Models\NasabahNotification;
use App\Models\PettyCashTransaksiNasabah;
use App\Models\PettyCashSaldo;

class TabunganController extends Controller
{
    /**
     * Display dashboard tabungan admin.
     */
    public function index()
    {
        // Statistik tabungan (pengajuan tarik = hanya transfer; tunai lewat Janji Temu)
        $stats = [
            'total_pengajuan_setor' => PengajuanTabungan::where('status', '1')->count(),
            'total_pengajuan_tarik' => PengajuanPenarikanTabungan::where('status', '1')->where('metode_transfer', 'transfer')->count(),
            'total_transaksi_hari_ini' => TransTabungan::whereDate('created_at', today())->count(),
            'total_setoran_hari_ini' => TransTabungan::whereHas('jnsTransaksi', function($q) {
                    $q->where('kode', 'STR');
                })->whereDate('created_at', today())->sum('nominal') ?? 0,
            'total_penarikan_hari_ini' => TransTabungan::whereHas('jnsTransaksi', function($q) {
                    $q->where('kode', 'PNR');
                })->whereDate('created_at', today())->sum('nominal') ?? 0,
            'total_janji_temu_pending' => JanjiTemuTabungan::where('tanggal_janji_temu', '>=', now())->count(),
        ];

        // Pengajuan setoran terbaru (pending)
        $pengajuan_setor_terbaru = PengajuanTabungan::where('status', '1')
            ->with(['nasabah.user', 'buktiFoto'])
            ->latest()
            ->take(5)
            ->get();

        // Pengajuan penarikan terbaru (pending, hanya transfer)
        $pengajuan_tarik_terbaru = PengajuanPenarikanTabungan::where('status', '1')
            ->where('metode_transfer', 'transfer')
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
        $query = PengajuanTabungan::with(['nasabah.user', 'buktiFoto'])  // Removed janjiTemu
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
        $pengajuan = PengajuanTabungan::with(['nasabah.user', 'nasabah.dataKtp', 'nasabah.dataRek', 'buktiFoto'])  // Removed janjiTemu
            ->findOrFail($id);

        return view('admin.tabungan.detail-pengajuan-setor', compact('pengajuan'));
    }

    /**
     * Approve pengajuan setoran.
     */
    public function approveSetor(Request $request, $id)
    {
        try {
            DB::beginTransaction();
            
            $pengajuan = PengajuanTabungan::with(['buktiFoto', 'transTabungan'])->findOrFail($id);  // Removed janjiTemu
            
            // Get nominal from pengajuan
            $nominal = $pengajuan->nominal ?? 0;

            // Validate nominal
            if ($nominal == 0 || $nominal < 10000) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', 'Nominal tidak valid. Minimal Rp 10.000');
            }

            // Create transaksi tabungan jika belum ada
            // Pastikan tidak ada duplikasi transaksi
            if ($pengajuan->transTabungan->count() == 0) {
                // V2 Logic: Master Data Driven
                $kodeVia = 'TF';  // Pengajuan always Transfer
                $kodeTrans = 'STR';

                // Get IDs from Master Tables
                $idVia = DB::table('jns_via')->where('kode', $kodeVia)->value('id');
                $idTrans = DB::table('jns_transaksi')->where('kode', $kodeTrans)->value('id');

                // Generate Complex String ID using correct method
                // Format: DDMMYYYYSEQFTVTRANS (e.g., 040220260001TTFSTR)
                $idTransaksi = IdGenerator::generate('trans_tabungan', 'T', $kodeVia, $kodeTrans);

                Log::info('Creating transaksi tabungan', [
                    'id' => $idTransaksi,
                    'pengajuan_id' => $pengajuan->id,
                    'nominal' => $nominal,
                    'id_via' => $idVia,
                    'id_trans' => $idTrans,
                ]);

            $pettyId = ($request->metode_bayar === 'transfer_admin' || $request->metode_bayar === 'cash') ? 
                        IdGenerator::generate('petty_cash_transaksi_nasabah', 'P', 'CS', 'STR') : null;

            TransTabungan::create([
                'id'                 => $idTransaksi,
                'id_pengajuan_setor' => $pengajuan->id,
                'id_anggota'         => $pengajuan->id_anggota,
                'id_jns_via'         => $idVia,
                'id_jns_transaksi'   => $idTrans,
                'nominal'            => abs((float) $nominal),
                'keterangan'         => $pengajuan->keterangan ?? 'Setoran tabungan disetujui',
                'tgl_transaksi'      => now(),
                'admin_pengelola_id' => auth()->id(),
                'is_petty_cash'      => ($request->metode_bayar !== 'transfer_koperasi') ? 1 : 0,
                'petty_cash_ref'     => $pettyId,
                'metode_bayar'       => $request->metode_bayar ?? 'transfer_koperasi',
            ]);

            // 🔥 INTEGRASI PETTY CASH: Jika transfer ke Admin atau Cash
            if ($pettyId) {
                PettyCashTransaksiNasabah::create([
                    'id'               => $pettyId,
                    'admin_id'         => auth()->id(),
                    'nasabah_id'       => $pengajuan->id_anggota,
                    'id_jns_transaksi' => $idTrans,
                    'id_jns_via'       => ($request->metode_bayar === 'cash') ? 
                                          PettyCashConstants::VIA_CS : 
                                          PettyCashConstants::VIA_TF,
                    'id_jns_fitur'     => PettyCashConstants::FITUR_TABUNGAN, // Simpanan Umum
                    'nominal'          => $nominal,
                    'status'           => 'approved',
                    'keterangan'       => 'Otomatis dari Pengajuan #' . $pengajuan->id,
                    'ref_table'        => 'trans_tabungan',
                    'ref_id'           => $idTransaksi,
                    'tgl_transaksi'    => now(),
                ]);

                $pettyType = ($request->metode_bayar === 'cash') ? 'cash' : 'transfer';
                PettyCashSaldo::updateOrCreateSaldo(
                    auth()->id(), 
                    'admin', 
                    $nominal, 
                    $pettyId, 
                    'Setoran dari Pengajuan #' . $pengajuan->id,
                    'petty_cash_transaksi_nasabah',
                    $pettyType
                );
            }

            Log::info('Transaksi tabungan created successfully', ['id' => $idTransaksi]);
        }

        // Update status to approved (status '2') + simpan keterangan_admin dan siapa yang approve
        $updateData = [
            'status' => '2',
            'approved_by_user_id' => auth()->id(),
            'metode_bayar' => $request->metode_bayar ?? 'transfer_koperasi',
        ];
        if ($request->filled('keterangan_admin')) {
            $updateData['keterangan_admin'] = $request->keterangan_admin;
        }
        $pengajuan->update($updateData);

            DB::commit();

            app(ActivityLogService::class)->logApproveSetoran($pengajuan->id, $pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A');

            NasabahNotification::notify(
                $pengajuan->id_anggota,
                'tabungan_setor',
                'Pengajuan setoran disetujui',
                'Setoran Anda sebesar Rp ' . number_format($pengajuan->nominal ?? 0, 0, ',', '.') . ' telah disetujui.',
                route('nasabah.tabungan.detail-pengajuan-setor', $pengajuan->id),
                (string) $pengajuan->id,
                'pengajuan_tabungan'
            );

            $transaksi = $pengajuan->transTabungan()->first();
            if ($transaksi) {
                return redirect()->route('admin.tabungan.detail-transaksi', $transaksi->id)
                    ->with('success', 'Pengajuan setoran berhasil disetujui dan transaksi telah dibuat. Silakan cetak struk di bawah.')
                    ->with('download_struk', true);
            }
            return redirect()->route('admin.tabungan.pengajuan-setor')
                ->with('success', 'Pengajuan setoran berhasil disetujui dan transaksi telah dibuat');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error approve setor', [
                'pengajuan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Reject pengajuan setoran.
     */
    public function rejectSetor(Request $request, $id)
    {
        $request->validate([
            'keterangan_admin' => 'required|string'
        ]);

        $pengajuan = PengajuanTabungan::with('nasabah.user')->findOrFail($id);
        $pengajuan->update([
            'status' => '3',
            'keterangan_admin' => $request->keterangan_admin
        ]);

        app(ActivityLogService::class)->logRejectSetoran($pengajuan->id, $pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A', $request->keterangan_admin);

        NasabahNotification::notify(
            $pengajuan->id_anggota,
            'tabungan_setor',
            'Pengajuan setoran ditolak',
            'Pengajuan setoran Anda ditolak. ' . ($request->keterangan_admin ?? ''),
            route('nasabah.tabungan.detail-pengajuan-setor', $pengajuan->id),
            (string) $pengajuan->id,
            'pengajuan_tabungan'
        );

        return redirect()->route('admin.tabungan.pengajuan-setor')
            ->with('success', 'Pengajuan setoran ditolak');
    }

    /**
     * Display list of pengajuan penarikan tabungan.
     */
    public function pengajuanTarik(Request $request)
    {
        // Hanya pengajuan penarikan via TRANSFER. Penarikan tunai diproses via Janji Temu.
        $query = PengajuanPenarikanTabungan::with('nasabah.user')
            ->where('metode_transfer', 'transfer')
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

        // Penarikan tunai diproses via Janji Temu, bukan di halaman ini
        if ($pengajuan->metode_transfer !== 'transfer') {
            return redirect()->route('admin.janji-temu.index')
                ->with('info', 'Penarikan tunai diproses melalui menu Janji Temu. Silakan cek daftar janji temu untuk penarikan tunai.');
        }

        // Get saldo nasabah
        $saldo = $this->getSaldoNasabah($pengajuan->id_anggota);

        // Biaya transfer (ditanggung nasabah): untuk tampilan sisa & form
        $biayaTransferList = BiayaTransfer::where('is_active', true)->get();
        $biayaDefault = $biayaTransferList->where('bank_penerima', $pengajuan->nama_bank)->first()?->biaya_admin
            ?? $biayaTransferList->first()?->biaya_admin
            ?? 0;
        $biayaDefault = (float) $biayaDefault;

        return view('admin.tabungan.detail-pengajuan-tarik', compact('pengajuan', 'saldo', 'biayaTransferList', 'biayaDefault'));
    }

    /**
     * Approve pengajuan penarikan.
     */
    public function approveTarik(Request $request, $id)
    {
        $pengajuan = PengajuanPenarikanTabungan::findOrFail($id);

        // Penarikan tunai diproses via Janji Temu, bukan di sini
        if ($pengajuan->metode_transfer !== 'transfer') {
            return redirect()->route('admin.janji-temu.index')
                ->with('info', 'Penarikan tunai diproses melalui menu Janji Temu.');
        }
        
        // Validate for transfer
        if ($pengajuan->metode_transfer == 'transfer') {
            $request->validate([
                'foto_bukti_tf_admin' => 'required|image|max:5120',
                'bank_pengirim' => 'required|string|max:50',
            ]);
        }
        
        // Biaya transfer (ditanggung nasabah)
        $biayaTransfer = 0;
        if ($pengajuan->metode_transfer == 'transfer') {
            $bt = BiayaTransfer::where('is_active', true)
                ->where('bank_pengirim', $request->bank_pengirim)
                ->where('bank_penerima', $pengajuan->nama_bank)
                ->first();
            $biayaTransfer = $bt ? (float) $bt->biaya_admin : 0;
        }

        // Check saldo: harus mencukupi nominal + biaya transfer
        $saldo = $this->getSaldoNasabah($pengajuan->id_anggota);
        $totalDipotong = $pengajuan->nominal + $biayaTransfer;

        if ($saldo < $totalDipotong) {
            return redirect()->back()
                ->with('error', 'Saldo nasabah tidak mencukupi (nominal + biaya transfer). Total yang dipotong: Rp ' . number_format($totalDipotong, 0, ',', '.'));
        }

        // Upload foto bukti TF admin (jika transfer)
        $fotoBuktiPath = null;
        if ($pengajuan->metode_transfer == 'transfer' && $request->hasFile('foto_bukti_tf_admin')) {
            $fotoBuktiPath = $request->file('foto_bukti_tf_admin')->store('bukti_tf_admin', 'public');
        }

        // Update pengajuan dengan foto dan biaya transfer (untuk ditampilkan di detail nasabah)
        $pengajuan->update([
            'status' => '2',
            'foto_bukti_tf_admin' => $fotoBuktiPath,
            'biaya_transfer' => $biayaTransfer,
        ]);

        // V2 Logic: Master Data Driven
        $kodeVia = ($pengajuan->metode_transfer == 'transfer') ? 'TF' : 'TN';
        $kodeTrans = 'PNR';

        // Get IDs
        $idVia = DB::table('jns_via')->where('kode', $kodeVia)->value('id');
        $idTrans = DB::table('jns_transaksi')->where('kode', $kodeTrans)->value('id');
        
        // Generate ID using correct method
        $idTransaksi = IdGenerator::generate('trans_tabungan', 'T', $kodeVia, $kodeTrans);

        // Create transaksi penarikan: nominal = total yang didebet dari saldo (nominal + biaya transfer)
        TransTabungan::create([
            'id' => $idTransaksi,
            'id_pengajuan_tarik' => $pengajuan->id,
            'id_anggota' => $pengajuan->id_anggota,
            'id_jns_via' => $idVia,
            'id_jns_transaksi' => $idTrans,
            'nominal' => $totalDipotong,
            'keterangan' => $pengajuan->keterangan,
            'tgl_transaksi' => now(),
        ]);

        app(ActivityLogService::class)->logApproveTarik(
            $pengajuan->id,
            (float) $pengajuan->nominal,
            $pengajuan->nasabah->user->nama ?? 'N/A',
            (float) ($pengajuan->biaya_transfer ?? 0)
        );

        NasabahNotification::notify(
            $pengajuan->id_anggota,
            'tabungan_tarik',
            'Pengajuan penarikan disetujui',
            'Penarikan Anda sebesar Rp ' . number_format($pengajuan->nominal ?? 0, 0, ',', '.') . ' telah disetujui. Dana akan ditransfer ke rekening Anda.',
            route('nasabah.tabungan.detail-pengajuan-tarik', $pengajuan->id),
            (string) $pengajuan->id,
            'pengajuan_penarikan_tabungan'
        );

        return redirect()->route('admin.tabungan.pengajuan-tarik')
            ->with('success', 'Pengajuan penarikan berhasil disetujui dan transfer telah dilakukan');
    }

    /**
     * Reject pengajuan penarikan.
     */
    public function rejectTarik(Request $request, $id)
    {
        $request->validate([
            'keterangan_admin' => 'required|string'
        ]);

        $pengajuan = PengajuanPenarikanTabungan::with('nasabah.user')->findOrFail($id);

        // Penarikan tunai diproses via Janji Temu
        if ($pengajuan->metode_transfer !== 'transfer') {
            return redirect()->route('admin.janji-temu.index')
                ->with('info', 'Penarikan tunai diproses melalui menu Janji Temu.');
        }

        $pengajuan->update([
            'status' => '3',
            'keterangan_admin' => $request->keterangan_admin
        ]);

        app(ActivityLogService::class)->logRejectTarik($pengajuan->id, $pengajuan->nominal, $pengajuan->nasabah->user->nama ?? 'N/A', $request->keterangan_admin);

        NasabahNotification::notify(
            $pengajuan->id_anggota,
            'tabungan_tarik',
            'Pengajuan penarikan ditolak',
            'Pengajuan penarikan Anda ditolak. ' . ($request->keterangan_admin ?? ''),
            route('nasabah.tabungan.detail-pengajuan-tarik', $pengajuan->id),
            (string) $pengajuan->id,
            'pengajuan_penarikan_tabungan'
        );

        return redirect()->route('admin.tabungan.pengajuan-tarik')
            ->with('success', 'Pengajuan penarikan ditolak');
    }

    /**
     * Display list of transaksi tabungan.
     */
    public function transaksi(Request $request)
    {
        $query = TransTabungan::with(['nasabah.user', 'adminPengelola'])
            ->latest();

        // Filter Riwayat Saya / Petty Cash
        if ($request->filter == 'saya') {
            $query->where('admin_pengelola_id', auth()->id());
            $title = 'Riwayat Proses Saya';
        } elseif ($request->filter == 'petty') {
            $query->where('is_petty_cash', 1);
            $title = 'Transaksi Petty Cash';
        } else {
            $title = 'Semua Transaksi';
        }

        // Filter by jenis (via relasi jns_transaksi; 'jenis' adalah accessor, bukan kolom DB)
        if ($request->has('jenis') && $request->jenis !== '') {
            $kode = $request->jenis === 'setoran' ? 'STR' : ($request->jenis === 'penarikan' ? 'PNR' : null);
            if ($kode) {
                $query->whereHas('jnsTransaksi', function ($q) use ($kode) {
                    $q->where('kode', $kode);
                });
            }
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

        $myCount = TransTabungan::where('admin_pengelola_id', auth()->id())->count();
        $pettyCount = TransTabungan::where('is_petty_cash', 1)->count();

        return view('admin.tabungan.transaksi', compact('transaksi', 'title', 'myCount', 'pettyCount'));
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
     * Display detail janji temu (dengan atau tanpa pengajuan).
     */
    public function detailJanjiTemu($id)
    {
        $janjiTemu = JanjiTemuTabungan::with([
            'nasabah.user', 'nasabah.dataKtp', 'nasabah.dataRek', 'lokasi', 'buktiFoto', 'transTabungan'
        ])->findOrFail($id);

        $adminSaldo = PettyCashSaldo::getSaldo(auth()->id(), 'admin');

        return view('admin.tabungan.detail-janji-temu', compact('janjiTemu', 'adminSaldo'));
    }

    /**
     * Create transaksi tabungan langsung dari janji temu.
     */
    public function createTransFromJanjiTemu(Request $request, $id)
    {
        $request->validate([
            'nominal' => 'required|string',
            'keterangan_admin' => 'nullable|string|max:500',
            'foto_penerimaan.*' => 'nullable|image|max:5120',  // Multiple files
        ]);

        // Parse nominal from formatted currency string (e.g., "Rp 10.000.000")
        $nominalStr = preg_replace('/[^0-9]/', '', $request->nominal);
        $nominal = (float) $nominalStr;
        
        if ($nominal < 10000) {
            return redirect()->back()
                ->with('error', 'Nominal minimal Rp 10.000')
                ->withInput();
        }

        $janjiTemu = JanjiTemuTabungan::with(['nasabah'])->findOrFail($id);

        // Check if already processed (status = 2)
        if ($janjiTemu->status == '2') {
            return redirect()->back()
                ->with('error', 'Janji temu ini sudah diproses sebelumnya');
        }

        $idAnggota = $janjiTemu->id_nasabah;

        // Handle foto penerimaan menggunakan tbl_bukti_foto universal
        // id wajib diisi: tbl_bukti_foto pakai id string (bukan auto-increment)
        if ($request->hasFile('foto_penerimaan')) {
            foreach ($request->file('foto_penerimaan') as $file) {
                $fotoPenerimaan = $file->store('bukti_tabungan', 'public');
                $idBuktiFoto = IdGenerator::generate('tbl_bukti_foto', 'T', 'CS', 'JNJT');
                BuktiFoto::create([
                    'id' => $idBuktiFoto,
                    'owner_id' => $janjiTemu->id,
                    'owner_fitur' => 'T',  // Tabungan
                    'owner_trans' => 'JNJT',  // Janji Temu
                    'file_path' => $fotoPenerimaan,
                    'keterangan' => 'Bukti penerimaan janji temu',
                ]);
            }
        }

        // Update janji temu: status selesai + nominal disamakan dengan yang dipakai di transaksi
        // (agar semua halaman—admin detail, nasabah detail, list—tampil nominal yang sama)
        $janjiTemu->update([
            'status' => '2',  // Selesai
            'nominal' => $nominal,  // Sinkronkan dengan nominal transaksi (bisa diedit admin)
            'keterangan_admin' => $request->keterangan_admin,
        ]);

        // Create transaksi tabungan
        $kodeVia = 'CS';  // Cash (janji temu)
        
        // Check Janji Temu Type
        $isWithdrawal = isset($janjiTemu->jenis) && $janjiTemu->jenis === 'penarikan';
        $kodeTrans = $isWithdrawal ? 'PNR' : 'STR';
        
        $idVia = DB::table('jns_via')->where('kode', $kodeVia)->value('id');
        $idTrans = DB::table('jns_transaksi')->where('kode', $kodeTrans)->value('id');
        $idTransaksi = IdGenerator::generate('trans_tabungan', 'T', $kodeVia, $kodeTrans);

        // Find related pengajuan tarik if this is a withdrawal
        $idPengajuanTarik = null;
        if ($isWithdrawal) {
            $pengajuanTarik = PengajuanPenarikanTabungan::where('id_anggota', $idAnggota)
                ->where('nominal', $nominal)
                ->where('status', '1') // Pending
                ->latest()
                ->first();
            
            if ($pengajuanTarik) {
                $idPengajuanTarik = $pengajuanTarik->id;
                $pengajuanTarik->update(['status' => '2']); // Approve
            }
        }

        // 🔥 INTEGRASI PETTY CASH: Validasi Saldo jika Penarikan Tunai
        if ($isWithdrawal) {
            try {
                if (!PettyCashSaldo::validatePenarikanCash(auth()->id(), $nominal)) {
                    throw new \Exception("Saldo CASH tidak mencukupi untuk penarikan ini.");
                }
                
                // Pemotongan Saldo Petty Cash Admin (CASH)
                PettyCashSaldo::updateSaldo(
                    auth()->id(), 
                    'cash', 
                    -$nominal, 
                    $janjiTemu->id, 
                    'Penarikan Tunai: ' . ($janjiTemu->nasabah->user->nama ?? 'Nasabah'),
                    'tbl_janji_temu_tabungan'
                );
            } catch (\Exception $e) {
                DB::rollBack();
                return redirect()->back()
                    ->with('error', $e->getMessage())
                    ->withInput();
            }
        }

        $pettyId = !$isWithdrawal ? IdGenerator::generate('petty_cash_transaksi_nasabah', 'P', 'CS', 'STR') : null;

        TransTabungan::create([
            'id'                 => $idTransaksi,
            'id_pengajuan_setor' => null, 
            'id_janji_temu_tabungan' => $janjiTemu->id,
            'id_pengajuan_tarik' => $idPengajuanTarik,
            'id_anggota'         => $idAnggota,
            'id_jns_via'         => $idVia,
            'id_jns_transaksi'   => $idTrans,
            'nominal'            => $isWithdrawal ? -$nominal : $nominal, // Simpan negatif di trans_tabungan jika penarikan
            'keterangan'         => ($isWithdrawal ? '[PENARIKAN TUNAI] ' : '[SETORAN TUNAI] ') . $janjiTemu->keterangan,
            'tgl_transaksi'      => now(),
            'admin_pengelola_id' => auth()->id(),
            'is_petty_cash'      => 1,
            'petty_cash_ref'     => $pettyId ?: $janjiTemu->id, // Jika penarikan ref ke janji temu
            'metode_bayar'       => 'cash',
        ]);

        // 🔥 INTEGRASI PETTY CASH: Untuk Setoran Cash via Janji Temu
        if ($pettyId) {
            PettyCashTransaksiNasabah::create([
                'id'               => $pettyId,
                'admin_id'         => auth()->id(),
                'nasabah_id'       => $idAnggota,
                'id_jns_transaksi' => $idTrans,
                'id_jns_via'       => $idVia,
                'id_jns_fitur'     => PettyCashConstants::FITUR_TABUNGAN,
                'nominal'          => $nominal,
                'status'           => 'approved',
                'keterangan'       => 'Otomatis dari Janji Temu #' . $janjiTemu->id,
                'ref_table'        => 'trans_tabungan',
                'ref_id'           => $idTransaksi,
                'tgl_transaksi'    => now(),
            ]);

            PettyCashSaldo::updateOrCreateSaldo(
                auth()->id(), 
                'admin', 
                $nominal, 
                $pettyId, 
                'Setoran dari Janji Temu #' . $janjiTemu->id,
                'petty_cash_transaksi_nasabah'
            );
        }

        app(ActivityLogService::class)->logProsesJanjiTemuTabungan($idTransaksi, $nominal, $janjiTemu->nasabah->user->nama ?? 'N/A', $isWithdrawal ? 'penarikan' : 'setoran');

        return redirect()->route('admin.janji-temu.index')
            ->with('success', 'Transaksi tabungan berhasil dibuat dari janji temu!');
    }

    /**
     * Edit pengajuan setoran.
     */
    public function editPengajuanSetor(Request $request, $id)
    {
        // Authorization: Only Admin Utama can edit pengajuan
        if (!app(\App\Services\AdminPermissionService::class)->canCrudTabunganTransaksi(auth()->user())) {
            return response()->json([
                'success' => false,
                'message' => 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat mengedit pengajuan.'
            ], 403);
        }

        $request->validate([
            'nominal' => 'nullable|numeric|min:10000',
            'keterangan_admin' => 'nullable|string|max:500',
            'status' => 'required|in:1,2,3',
        ]);

        try {
            DB::beginTransaction();
            
            $pengajuan = PengajuanTabungan::with(['buktiFoto', 'transTabungan'])->findOrFail($id);  // Removed janjiTemu
            
            $updateData = [
                'status' => $request->status,
            ];

            // Update nominal jika diisi
            if ($request->has('nominal') && $request->nominal) {
                $updateData['nominal'] = $request->nominal;
            }

            // Update keterangan_admin jika diisi
            if ($request->has('keterangan_admin') && $request->keterangan_admin) {
                $updateData['keterangan_admin'] = $request->keterangan_admin;
            }

            $pengajuan->update($updateData);

            // Jika status approved (2) dan belum ada transaksi, buat transaksi
            if ($request->status == '2' && $pengajuan->transTabungan->count() == 0) {
                // Get nominal from pengajuan
                $nominal = $pengajuan->nominal ?? 0;

                // Validate nominal
                if ($nominal == 0 || $nominal < 10000) {
                    DB::rollBack();
                    return redirect()->back()
                        ->with('error', 'Nominal tidak valid. Minimal Rp 10.000');
                }

                // V2 Logic: Master Data Driven
                $kodeVia = 'TF';  // Pengajuan always Transfer
                $kodeTrans = 'STR';

                // Get IDs from Master Tables
                $idVia = DB::table('jns_via')->where('kode', $kodeVia)->value('id');
                $idTrans = DB::table('jns_transaksi')->where('kode', $kodeTrans)->value('id');

                // Generate Complex String ID using correct method
                $idTransaksi = IdGenerator::generate('trans_tabungan', 'T', $kodeVia, $kodeTrans);

                Log::info('Creating transaksi tabungan from editPengajuanSetor', [
                    'id' => $idTransaksi,
                    'pengajuan_id' => $pengajuan->id,
                    'nominal' => $nominal,
                    'id_via' => $idVia,
                    'id_trans' => $idTrans,
                ]);

                TransTabungan::create([
                    'id' => $idTransaksi,
                    'id_pengajuan_setor' => $pengajuan->id,
                    'id_anggota' => $pengajuan->id_anggota,
                    'id_jns_via' => $idVia,
                    'id_jns_transaksi' => $idTrans,
                    'nominal' => abs((float) $nominal), // setoran selalu positif
                    'keterangan' => $request->keterangan_admin ?? $pengajuan->keterangan ?? 'Setoran tabungan disetujui',
                    'tgl_transaksi' => now(),
                ]);

                Log::info('Transaksi tabungan created successfully from edit', ['id' => $idTransaksi]);
            }

            DB::commit();

            return redirect()->route('admin.tabungan.pengajuan-setor')
                ->with('success', 'Pengajuan setoran berhasil diupdate dan transaksi telah dibuat');
                
        } catch (\Exception $e) {
            DB::rollBack();
            
            Log::error('Error edit pengajuan setor', [
                'pengajuan_id' => $id,
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString(),
            ]);

            return redirect()->back()
                ->with('error', 'Terjadi kesalahan: ' . $e->getMessage());
        }
    }

    /**
     * Delete pengajuan setoran.
     */
    public function deletePengajuanSetor($id)
    {
        // Authorization: Only Admin Utama can delete pengajuan
        if (!app(\App\Services\AdminPermissionService::class)->canCrudTabunganTransaksi(auth()->user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat menghapus pengajuan.');
        }

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

        // Delete bukti foto files (model BuktiFoto pakai file_path)
        foreach ($pengajuan->buktiFoto as $bukti) {
            if ($bukti->file_path && Storage::disk('public')->exists($bukti->file_path)) {
                Storage::disk('public')->delete($bukti->file_path);
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
                ->whereHas('jnsTransaksi', function($q) { $q->where('kode', 'STR'); })
                ->sum('nominal') ?? 0;
            $item->total_penarikan = TransTabungan::where('id_anggota', $item->id)
                ->whereHas('jnsTransaksi', function($q) { $q->where('kode', 'PNR'); })
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
        $totalSetoranTrans = \App\Models\TransTabungan::where('id_anggota', $idAnggota)
            ->whereHas('jnsTransaksi', function($q) { $q->where('kode', 'STR'); })
            ->sum('nominal') ?? 0;

        $totalPenarikanTrans = \App\Models\TransTabungan::where('id_anggota', $idAnggota)
            ->whereHas('jnsTransaksi', function($q) { $q->where('kode', 'PNR'); })
            ->sum('nominal') ?? 0;

        // Tambahkan setoran dari pengajuan yang sudah approved tapi belum ada transaksi
        $approvedNoTransSum = \App\Models\PengajuanTabungan::where('id_anggota', $idAnggota)
            ->where('status', '2') // Approved
            ->whereDoesntHave('transTabungan')
            ->sum('nominal') ?? 0;

        $saldo = max(0, $totalSetoranTrans + $approvedNoTransSum - $totalPenarikanTrans);

        // Kurangi juga dengan deposito yang diajukan menggunakan metode saldo tabungan namun belum diproses (status == '1' artinya pending/menunggu)
        $pendingDepositoTabungan = \App\Models\PengajuanDeposito::where('id_nasabah', $idAnggota)
            ->where('status', '1') // Pending
            ->where('metode_setor', 'saldo_tabungan')
            ->sum('nominal') ?? 0;

        return max(0, $saldo - $pendingDepositoTabungan);
    }

    /**
     * Create manual transaction form.
     */
    public function createTransaksi()
    {
        // Authorization: Only Admin Utama can create manual transactions
        if (!app(\App\Services\AdminPermissionService::class)->canCrudTabunganTransaksi(auth()->user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat membuat transaksi manual.');
        }

        $nasabah = Nasabah::with('user')->get();

        return view('admin.tabungan.create-transaksi', compact('nasabah'));
    }

    /**
     * Store manual transaction.
     */
    public function storeTransaksi(Request $request)
    {
        // Authorization: Only Admin Utama can create manual transactions
        if (!app(\App\Services\AdminPermissionService::class)->canCrudTabunganTransaksi(auth()->user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat membuat transaksi manual.');
        }

        $request->validate([
            'id_anggota' => 'required|exists:tbl_nasabah,id',
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

        // V2 Logic Mapping
        $kodeVia = ($request->via == 'transfer') ? 'TF' : 'TN';
        $kodeTrans = ($request->jenis == 'setoran') ? 'STR' : 'PNR';
        
        $idVia = DB::table('jns_via')->where('kode', $kodeVia)->value('id');
        $idTrans = DB::table('jns_transaksi')->where('kode', $kodeTrans)->value('id');
        
        // Generate ID using correct method
        $idTransaksi = IdGenerator::generate('trans_tabungan', 'T', $kodeVia, $kodeTrans);

        // Upload foto bukti if exists
        $fotoBukti = null;
        if ($request->hasFile('foto_bukti')) {
            $fotoBukti = $request->file('foto_bukti')->store('bukti_transaksi', 'public');
        }

        // Create transaksi
        $transaksi = TransTabungan::create([
            'id' => $idTransaksi,
            'id_anggota' => $request->id_anggota,
            'id_jns_via' => $idVia,
            'id_jns_transaksi' => $idTrans,
            'nominal' => $request->nominal,
            'keterangan' => $request->keterangan . ($fotoBukti ? ' | Foto: ' . $fotoBukti : ''),
            'tgl_transaksi' => $request->tgl_transaksi,
        ]);

        $nasabahRecord = \App\Models\Nasabah::with('user')->find($request->id_anggota);
        app(ActivityLogService::class)->logCreateTransaksiManual($idTransaksi, $request->nominal, $nasabahRecord->user->nama ?? 'N/A', $request->jenis);

        return redirect()->route('admin.tabungan.transaksi')
            ->with('success', "Transaksi {$request->jenis} berhasil dibuat dengan ID: {$idTransaksi}");
    }

    /**
     * Edit manual transaction form.
     */
    public function editTransaksi($id)
    {
        // Authorization: Only Admin Utama can edit manual transactions
        if (!app(\App\Services\AdminPermissionService::class)->canCrudTabunganTransaksi(auth()->user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat mengedit transaksi manual.');
        }

        $transaksi = TransTabungan::with(['nasabah.user'])->findOrFail($id);

        // Only allow edit if created manually (no pengajuan)
        if ($transaksi->id_pengajuan_setor || $transaksi->id_pengajuan_tarik) {
            return redirect()->back()
                ->with('error', 'Transaksi dari pengajuan tidak dapat diedit');
        }

        $nasabah = Nasabah::with('user')->get();

        return view('admin.tabungan.edit-transaksi', compact('transaksi', 'nasabah'));
    }

    /**
     * Update manual transaction.
     */
    public function updateTransaksi(Request $request, $id)
    {
        // Authorization: Only Admin Utama can update manual transactions
        if (!app(\App\Services\AdminPermissionService::class)->canCrudTabunganTransaksi(auth()->user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat mengupdate transaksi manual.');
        }

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

        // Jika transaksi ini penarikan, validasi saldo (edit nominal tidak boleh melebihi saldo tersedia)
        if ($transaksi->jenis === 'penarikan') {
            $saldo = $this->getSaldoNasabah($transaksi->id_anggota);
            $saldoWithoutThis = $saldo + $transaksi->nominal; // kembalikan nominal lama dulu
            
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

        app(ActivityLogService::class)->logEditTransaksiManual($id, $transaksi->nasabah->user->nama ?? 'N/A');

        return redirect()->route('admin.tabungan.detail-transaksi', $id)
            ->with('success', 'Transaksi berhasil diupdate');
    }

    /**
     * Delete manual transaction.
     */
    public function destroyTransaksi($id)
    {
        // Authorization: Only Admin Utama can delete manual transactions
        if (!app(\App\Services\AdminPermissionService::class)->canCrudTabunganTransaksi(auth()->user())) {
            abort(403, 'Anda tidak memiliki akses untuk fitur ini. Hanya Admin Utama yang dapat menghapus transaksi manual.');
        }

        $transaksi = TransTabungan::with('nasabah.user')->findOrFail($id);

        // Only allow delete if created manually
        if ($transaksi->id_pengajuan_setor || $transaksi->id_pengajuan_tarik) {
            return redirect()->back()
                ->with('error', 'Transaksi dari pengajuan tidak dapat dihapus');
        }

        $nasabahNama = $transaksi->nasabah->user->nama ?? 'N/A';
        $transaksi->delete();

        app(ActivityLogService::class)->logDeleteTransaksiManual($id, $nasabahNama);

        return redirect()->route('admin.tabungan.transaksi')
            ->with('success', 'Transaksi berhasil dihapus');
    }
}

