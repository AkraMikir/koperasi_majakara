<?php

namespace App\Services;

use App\Models\ActivityLog;

class ActivityLogService
{
    /**
     * Catat satu aktivitas ke dalam log.
     *
     * @param string      $action       Kode aksi (e.g. 'approve_setoran', 'submit_pengajuan_pinjaman')
     * @param string      $module       Modul terkait: tabungan | pinjaman | nasabah | master_data | akun
     * @param string      $description  Kalimat deskriptif yang akan ditampilkan di UI
     * @param array       $properties   Data tambahan dalam format array (nominal, nama nasabah, dll.)
     * @param string|null $subjectType  Nama model (e.g. 'PengajuanTabungan')
     * @param string|int|null $subjectId ID objek yang terdampak
     */
    public function log(
        string $action,
        string $module,
        string $description,
        array $properties = [],
        ?string $subjectType = null,
        string|int|null $subjectId = null
    ): void {
        try {
            $user = auth()->user();
            if (!$user) {
                return;
            }

            ActivityLog::create([
                'user_id'      => $user->id,
                'user_name'    => $user->nama,
                'user_role'    => $user->role,
                'action'       => $action,
                'module'       => $module,
                'description'  => $description,
                'subject_type' => $subjectType,
                'subject_id'   => (string) $subjectId,
                'properties'   => !empty($properties) ? $properties : null,
                'ip_address'   => request()->ip(),
            ]);
        } catch (\Throwable $e) {
            // Logging tidak boleh menghentikan proses utama
            // Log ke error log tanpa throw
            logger()->error('ActivityLog error: ' . $e->getMessage());
        }
    }

    // ====================== HELPER METHODS ======================
    // Helper untuk tiap modul agar tidak perlu mengingat action/module string

    // --- TABUNGAN ---

    public function logSubmitSetoran(string $pengajuanId, float $nominal, string $metode): void
    {
        $this->log(
            'submit_setoran',
            'tabungan',
            "Mengajukan setoran tabungan sebesar Rp " . number_format($nominal, 0, ',', '.') . " via {$metode}",
            ['nominal' => $nominal, 'metode' => $metode],
            'PengajuanTabungan',
            $pengajuanId
        );
    }

    public function logSubmitJanjiTemuTabungan(string $janjiTemuId, float $nominal, string $jenis, string $tanggal): void
    {
        $this->log(
            'submit_janji_temu_tabungan',
            'tabungan',
            "Membuat janji temu {$jenis} tabungan sebesar Rp " . number_format($nominal, 0, ',', '.') . " pada {$tanggal}",
            ['nominal' => $nominal, 'jenis' => $jenis, 'tanggal' => $tanggal],
            'JanjiTemuTabungan',
            $janjiTemuId
        );
    }

    public function logSubmitPenarikan(string $pengajuanId, float $nominal, string $metode): void
    {
        $this->log(
            'submit_penarikan',
            'tabungan',
            "Mengajukan penarikan tabungan sebesar Rp " . number_format($nominal, 0, ',', '.') . " via {$metode}",
            ['nominal' => $nominal, 'metode' => $metode],
            'PengajuanPenarikanTabungan',
            $pengajuanId
        );
    }

    public function logApproveSetoran(string $pengajuanId, float $nominal, string $nasabahNama): void
    {
        $this->log(
            'approve_setoran',
            'tabungan',
            "Menyetujui pengajuan setoran tabungan Rp " . number_format($nominal, 0, ',', '.') . " dari nasabah {$nasabahNama}",
            ['nominal' => $nominal, 'nasabah' => $nasabahNama],
            'PengajuanTabungan',
            $pengajuanId
        );
    }

    public function logRejectSetoran(string $pengajuanId, float $nominal, string $nasabahNama, string $alasan = ''): void
    {
        $this->log(
            'reject_setoran',
            'tabungan',
            "Menolak pengajuan setoran tabungan Rp " . number_format($nominal, 0, ',', '.') . " dari nasabah {$nasabahNama}" . ($alasan ? ". Alasan: {$alasan}" : ''),
            ['nominal' => $nominal, 'nasabah' => $nasabahNama, 'alasan' => $alasan],
            'PengajuanTabungan',
            $pengajuanId
        );
    }

    public function logApproveTarik(string $pengajuanId, float $nominal, string $nasabahNama, float $biayaTransfer = 0): void
    {
        $properties = ['nominal' => $nominal, 'nasabah' => $nasabahNama];
        if ($biayaTransfer > 0) {
            $properties['biaya_transfer'] = $biayaTransfer;
            $totalDebet = $nominal + $biayaTransfer;
            $properties['total_didebet'] = $totalDebet;
        }
        $desc = "Menyetujui penarikan tabungan Rp " . number_format($nominal, 0, ',', '.') . " untuk nasabah {$nasabahNama}";
        if ($biayaTransfer > 0) {
            $desc .= " (biaya transfer Rp " . number_format($biayaTransfer, 0, ',', '.') . " ditanggung nasabah)";
        }
        $this->log(
            'approve_tarik',
            'tabungan',
            $desc,
            $properties,
            'PengajuanPenarikanTabungan',
            $pengajuanId
        );
    }

    public function logRejectTarik(string $pengajuanId, float $nominal, string $nasabahNama, string $alasan = ''): void
    {
        $this->log(
            'reject_tarik',
            'tabungan',
            "Menolak penarikan tabungan Rp " . number_format($nominal, 0, ',', '.') . " untuk nasabah {$nasabahNama}" . ($alasan ? ". Alasan: {$alasan}" : ''),
            ['nominal' => $nominal, 'nasabah' => $nasabahNama, 'alasan' => $alasan],
            'PengajuanPenarikanTabungan',
            $pengajuanId
        );
    }

    public function logProsesJanjiTemuTabungan(string $transaksiId, float $nominal, string $nasabahNama, string $jenis): void
    {
        $this->log(
            'proses_janji_temu_tabungan',
            'tabungan',
            "Memproses janji temu {$jenis} tabungan Rp " . number_format($nominal, 0, ',', '.') . " untuk nasabah {$nasabahNama}",
            ['nominal' => $nominal, 'nasabah' => $nasabahNama, 'jenis' => $jenis],
            'TransTabungan',
            $transaksiId
        );
    }

    public function logCreateTransaksiManual(string $transaksiId, float $nominal, string $nasabahNama, string $jenis): void
    {
        $this->log(
            'create_transaksi_manual',
            'tabungan',
            "Membuat transaksi manual {$jenis} tabungan Rp " . number_format($nominal, 0, ',', '.') . " untuk nasabah {$nasabahNama}",
            ['nominal' => $nominal, 'nasabah' => $nasabahNama, 'jenis' => $jenis],
            'TransTabungan',
            $transaksiId
        );
    }

    public function logEditTransaksiManual(string $transaksiId, string $nasabahNama): void
    {
        $this->log(
            'edit_transaksi_manual',
            'tabungan',
            "Mengedit transaksi manual tabungan untuk nasabah {$nasabahNama}",
            ['nasabah' => $nasabahNama],
            'TransTabungan',
            $transaksiId
        );
    }

    public function logDeleteTransaksiManual(string $transaksiId, string $nasabahNama): void
    {
        $this->log(
            'delete_transaksi_manual',
            'tabungan',
            "Menghapus transaksi manual tabungan milik nasabah {$nasabahNama}",
            ['nasabah' => $nasabahNama],
            'TransTabungan',
            $transaksiId
        );
    }

    // --- PINJAMAN ---

    public function logSubmitPengajuanPinjaman(string $pengajuanId, float $nominal, string $jenisPengajuan): void
    {
        $this->log(
            'submit_pengajuan_pinjaman',
            'pinjaman',
            "Mengajukan pinjaman sebesar Rp " . number_format($nominal, 0, ',', '.') . " via {$jenisPengajuan}",
            ['nominal' => $nominal, 'jenis' => $jenisPengajuan],
            'PengajuanPinjaman',
            $pengajuanId
        );
    }

    public function logSubmitPembayaranPinjaman(string $pengajuanId, float $nominal, string $metode): void
    {
        $this->log(
            'submit_pembayaran_pinjaman',
            'pinjaman',
            "Mengajukan pembayaran angsuran sebesar Rp " . number_format($nominal, 0, ',', '.') . " via {$metode}",
            ['nominal' => $nominal, 'metode' => $metode],
            'PengajuanPembayaranPinjaman',
            $pengajuanId
        );
    }

    public function logSubmitJanjiTemuPembayaran(string $janjiTemuId, float $nominal, string $tanggal): void
    {
        $this->log(
            'submit_janji_temu_pembayaran',
            'pinjaman',
            "Membuat janji temu pembayaran angsuran sebesar Rp " . number_format($nominal, 0, ',', '.') . " pada {$tanggal}",
            ['nominal' => $nominal, 'tanggal' => $tanggal],
            'JanjiTemuPembayaranPinjaman',
            $janjiTemuId
        );
    }

    public function logApprovePengajuanPinjaman(string $pengajuanId, float $nominal, string $nasabahNama): void
    {
        $this->log(
            'approve_pengajuan_pinjaman',
            'pinjaman',
            "Menyetujui pengajuan pinjaman Rp " . number_format($nominal, 0, ',', '.') . " atas nama nasabah {$nasabahNama}",
            ['nominal' => $nominal, 'nasabah' => $nasabahNama],
            'PengajuanPinjaman',
            $pengajuanId
        );
    }

    public function logRejectPengajuanPinjaman(string $pengajuanId, float $nominal, string $nasabahNama, string $alasan = ''): void
    {
        $this->log(
            'reject_pengajuan_pinjaman',
            'pinjaman',
            "Menolak pengajuan pinjaman Rp " . number_format($nominal, 0, ',', '.') . " atas nama nasabah {$nasabahNama}" . ($alasan ? ". Alasan: {$alasan}" : ''),
            ['nominal' => $nominal, 'nasabah' => $nasabahNama, 'alasan' => $alasan],
            'PengajuanPinjaman',
            $pengajuanId
        );
    }

    public function logCairkanPinjaman(string $pinjamanId, float $nominal, string $nasabahNama): void
    {
        $this->log(
            'cairkan_pinjaman',
            'pinjaman',
            "Mencairkan pinjaman Rp " . number_format($nominal, 0, ',', '.') . " untuk nasabah {$nasabahNama}",
            ['nominal' => $nominal, 'nasabah' => $nasabahNama],
            'PinjamanH',
            $pinjamanId
        );
    }

    public function logApprovePembayaranPinjaman(string $pembayaranId, float $nominal, string $nasabahNama): void
    {
        $this->log(
            'approve_pembayaran_pinjaman',
            'pinjaman',
            "Menyetujui pembayaran angsuran Rp " . number_format($nominal, 0, ',', '.') . " dari nasabah {$nasabahNama}",
            ['nominal' => $nominal, 'nasabah' => $nasabahNama],
            'PengajuanPembayaranPinjaman',
            $pembayaranId
        );
    }

    public function logRejectPembayaranPinjaman(string $pembayaranId, float $nominal, string $nasabahNama, string $alasan = ''): void
    {
        $this->log(
            'reject_pembayaran_pinjaman',
            'pinjaman',
            "Menolak pembayaran angsuran Rp " . number_format($nominal, 0, ',', '.') . " dari nasabah {$nasabahNama}" . ($alasan ? ". Alasan: {$alasan}" : ''),
            ['nominal' => $nominal, 'nasabah' => $nasabahNama, 'alasan' => $alasan],
            'PengajuanPembayaranPinjaman',
            $pembayaranId
        );
    }

    public function logKonfirmasiPembayaranPinjaman(string $pembayaranId, float $nominal, string $nasabahNama): void
    {
        $this->log(
            'konfirmasi_pembayaran_pinjaman',
            'pinjaman',
            "Mengkonfirmasi pembayaran angsuran Rp " . number_format($nominal, 0, ',', '.') . " dari nasabah {$nasabahNama}",
            ['nominal' => $nominal, 'nasabah' => $nasabahNama],
            'PengajuanPembayaranPinjaman',
            $pembayaranId
        );
    }

    public function logProsesJanjiTemuPembayaranPinjaman(string $pengajuanId, float $nominal, string $nasabahNama): void
    {
        $this->log(
            'proses_janji_temu_pembayaran_pinjaman',
            'pinjaman',
            "Memproses janji temu pembayaran pinjaman Rp " . number_format($nominal, 0, ',', '.') . " dari nasabah {$nasabahNama}",
            ['nominal' => $nominal, 'nasabah' => $nasabahNama],
            'PengajuanPembayaranPinjaman',
            $pengajuanId
        );
    }

    public function logPelunasanDipercepat(string $pinjamanId, float $sisaTagihan, string $nasabahNama): void
    {
        $this->log(
            'pelunasan_dipercepat',
            'pinjaman',
            "Melakukan pelunasan dipercepat pinjaman Rp " . number_format($sisaTagihan, 0, ',', '.') . " atas nama nasabah {$nasabahNama}",
            ['sisa_tagihan' => $sisaTagihan, 'nasabah' => $nasabahNama],
            'PinjamanH',
            $pinjamanId
        );
    }

    public function logCreatePinjamanManual(string $pinjamanId, float $nominal, string $nasabahNama): void
    {
        $this->log(
            'create_pinjaman_manual',
            'pinjaman',
            "Membuat pinjaman manual Rp " . number_format($nominal, 0, ',', '.') . " untuk nasabah {$nasabahNama}",
            ['nominal' => $nominal, 'nasabah' => $nasabahNama],
            'PinjamanH',
            $pinjamanId
        );
    }

    public function logDeletePinjamanManual(string $pinjamanId, string $nasabahNama): void
    {
        $this->log(
            'delete_pinjaman_manual',
            'pinjaman',
            "Menghapus pinjaman manual milik nasabah {$nasabahNama}",
            ['nasabah' => $nasabahNama],
            'PinjamanH',
            $pinjamanId
        );
    }

    // --- NASABAH ---

    public function logSubmitPerubahanData(int $pengajuanId): void
    {
        $this->log(
            'submit_perubahan_data',
            'nasabah',
            "Mengajukan perubahan data profil",
            [],
            'PengajuanPerubahanData',
            $pengajuanId
        );
    }

    public function logBatalPerubahanData(int $pengajuanId): void
    {
        $this->log(
            'batal_perubahan_data',
            'nasabah',
            "Membatalkan pengajuan perubahan data profil",
            [],
            'PengajuanPerubahanData',
            $pengajuanId
        );
    }

    public function logApprovePerubahanData(int $pengajuanId, string $nasabahNama): void
    {
        $this->log(
            'approve_perubahan_data',
            'nasabah',
            "Menyetujui pengajuan perubahan data nasabah {$nasabahNama}",
            ['nasabah' => $nasabahNama],
            'PengajuanPerubahanData',
            $pengajuanId
        );
    }

    public function logRejectPerubahanData(int $pengajuanId, string $nasabahNama, string $alasan = ''): void
    {
        $this->log(
            'reject_perubahan_data',
            'nasabah',
            "Menolak pengajuan perubahan data nasabah {$nasabahNama}" . ($alasan ? ". Alasan: {$alasan}" : ''),
            ['nasabah' => $nasabahNama, 'alasan' => $alasan],
            'PengajuanPerubahanData',
            $pengajuanId
        );
    }

    public function logResetPin(int $nasabahId, string $nasabahNama): void
    {
        $this->log(
            'reset_pin_nasabah',
            'nasabah',
            "Mereset PIN nasabah {$nasabahNama}",
            ['nasabah' => $nasabahNama],
            'Nasabah',
            $nasabahId
        );
    }

    // --- AKUN ---

    public function logUbahPassword(): void
    {
        $this->log(
            'ubah_password',
            'akun',
            "Mengubah password akun",
            []
        );
    }

    public function logUbahPin(): void
    {
        $this->log(
            'ubah_pin',
            'akun',
            "Mengubah PIN transaksi",
            []
        );
    }

    // --- MASTER DATA ---

    public function logMasterDataAction(string $action, string $namaData, string $kategori, string|int|null $dataId = null): void
    {
        $aksiLabel = match($action) {
            'create' => 'Menambahkan',
            'update' => 'Memperbarui',
            'delete' => 'Menghapus',
            'toggle_aktif' => 'Mengaktifkan',
            'toggle_nonaktif' => 'Menonaktifkan',
            default => $action,
        };

        $this->log(
            "{$action}_master_data",
            'master_data',
            "{$aksiLabel} data {$kategori}: {$namaData}",
            ['nama' => $namaData, 'kategori' => $kategori],
            $kategori,
            $dataId
        );
    }

    // --- ADMIN OPERASIONAL MANAGEMENT ---

    public function logAdminOperasionalAction(string $action, string $namaAdmin, string|int|null $adminId = null): void
    {
        $aksiLabel = match($action) {
            'create' => 'Menambahkan akun Admin Operasional',
            'update' => 'Memperbarui data Admin Operasional',
            'delete' => 'Menghapus akun Admin Operasional',
            'toggle_aktif' => 'Mengaktifkan akun Admin Operasional',
            'toggle_nonaktif' => 'Menonaktifkan akun Admin Operasional',
            default => $action,
        };

        $this->log(
            "{$action}_admin_operasional",
            'master_data',
            "{$aksiLabel}: {$namaAdmin}",
            ['nama' => $namaAdmin],
            'AdminOperasional',
            $adminId
        );
    }
}
