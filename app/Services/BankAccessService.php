<?php

namespace App\Services;

use App\Models\BiayaTransfer;
use App\Models\DataRek;
use App\Models\Nasabah;
use App\Models\TransTabungan;
use App\Helpers\IdGenerator;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;

/**
 * BankAccessService
 *
 * Central service untuk:
 *  1. Mengecek apakah nasabah non-BCA memenuhi syarat saldo minimum untuk mengakses fitur premium
 *  2. Menghitung & memotong biaya transfer antarbank dari saldo tabungan nasabah
 *
 * Digunakan oleh:
 *  - Nasabah\PinjamanController, DepositoController (guard akses pengajuan)
 *  - Admin\PinjamanController::cairkanPinjaman (potong biaya)
 *  - Admin\DepositoController::selesaikanPencairanTf (potong biaya)
 */
class BankAccessService
{
    /** Kode bank koperasi (yang dipakai admin sebagai bank_pengirim) */
    const BANK_KOPERASI = 'BCA';

    // ──────────────────────────────────────────────────────────────
    //  BAGIAN 1: AKSES FITUR
    // ──────────────────────────────────────────────────────────────

    /**
     * Ambil nama bank nasabah dari tbl_data_rek.
     * Return null jika data tidak ditemukan.
     */
    public function getNamaBank(int $nasabahId): ?string
    {
        return DataRek::where('nasabah_id', $nasabahId)
            ->value('nama_bank');
    }

    /**
     * Apakah nasabah adalah pengguna BCA (atau setara bank koperasi)?
     */
    public function isBcaUser(int $nasabahId): bool
    {
        $namaBank = $this->getNamaBank($nasabahId);
        if (!$namaBank) {
            return false; // Tidak ada data rekening → dianggap non-BCA (lebih aman)
        }
        return strtoupper(trim($namaBank)) === strtoupper(self::BANK_KOPERASI);
    }

    /**
     * Ambil saldo tabungan nasabah (STR - PNR dari trans_tabungan).
     */
    public function getSaldoTabungan(int $nasabahId): float
    {
        $totalSetoran = TransTabungan::where('id_anggota', $nasabahId)
            ->whereHas('jnsTransaksi', fn($q) => $q->where('kode', 'STR'))
            ->sum('nominal');

        $totalPenarikan = TransTabungan::where('id_anggota', $nasabahId)
            ->whereHas('jnsTransaksi', fn($q) => $q->where('kode', 'PNR'))
            ->sum('nominal');

        return max(0, (float)$totalSetoran - (float)$totalPenarikan);
    }

    /**
     * Ambil minimum saldo non-BCA dari tabel biaya_transfer.
     * Mengambil nilai max dari semua entri aktif (paling ketat).
     */
    public function getMinSaldoNonBca(): float
    {
        $max = BiayaTransfer::where('is_active', true)
            ->max('min_saldo_non_bca');

        return (float) ($max ?? 0);
    }

    /**
     * Apakah nasabah boleh mengakses fitur premium (pinjaman, deposito, gadai)?
     *
     * Aturan:
     *  - Nasabah BCA  → selalu boleh.
     *  - Nasabah non-BCA → boleh HANYA jika saldo tabungan >= min_saldo_non_bca.
     *
     * @return array{allowed: bool, reason: string, bank: string|null, saldo: float, min_saldo: float}
     */
    public function checkPremiumAccess(int $nasabahId): array
    {
        $namaBank  = $this->getNamaBank($nasabahId);
        $isBca     = strtoupper(trim($namaBank ?? '')) === strtoupper(self::BANK_KOPERASI);

        if ($isBca || !$namaBank) {
            // BCA selalu akses. Tidak ada rekening → beri akses (admin yang handle validasi lain)
            return [
                'allowed'   => true,
                'reason'    => '',
                'bank'      => $namaBank,
                'saldo'     => 0.0,
                'min_saldo' => 0.0,
            ];
        }

        // Nasabah non-BCA: cek saldo
        $saldo     = $this->getSaldoTabungan($nasabahId);
        $minSaldo  = $this->getMinSaldoNonBca();

        $allowed = $saldo >= $minSaldo;

        $reason = '';
        if (!$allowed) {
            $kekurangan = $minSaldo - $saldo;
            $reason = sprintf(
                'Rekening %s Anda memerlukan saldo tabungan minimal Rp %s untuk mengakses fitur ini. ' .
                'Saldo Anda saat ini: Rp %s (kurang Rp %s). ' .
                'Silakan lakukan setoran tabungan terlebih dahulu.',
                strtoupper($namaBank),
                number_format($minSaldo, 0, ',', '.'),
                number_format($saldo, 0, ',', '.'),
                number_format($kekurangan, 0, ',', '.')
            );
        }

        return [
            'allowed'   => $allowed,
            'reason'    => $reason,
            'bank'      => $namaBank,
            'saldo'     => $saldo,
            'min_saldo' => $minSaldo,
        ];
    }

    // ──────────────────────────────────────────────────────────────
    //  BAGIAN 2: BIAYA TRANSFER
    // ──────────────────────────────────────────────────────────────

    /**
     * Ambil biaya transfer antarbank.
     * bank_pengirim = bank koperasi (BCA), bank_penerima = bank nasabah.
     * Return 0 jika tidak ditemukan atau sama bank.
     */
    public function getBiayaTransfer(string $bankPengirim, string $bankPenerima): float
    {
        if (strtoupper(trim($bankPengirim)) === strtoupper(trim($bankPenerima))) {
            return 0.0; // Satu bank → tidak ada biaya
        }

        $bt = BiayaTransfer::where('is_active', true)
            ->whereRaw('UPPER(bank_pengirim) = ?', [strtoupper(trim($bankPengirim))])
            ->whereRaw('UPPER(bank_penerima) = ?', [strtoupper(trim($bankPenerima))])
            ->first();

        return $bt ? (float) $bt->biaya_admin : 0.0;
    }

    /**
     * Potong biaya transfer antarbank dari saldo tabungan nasabah.
     *
     * Dibuat sebagai TransTabungan PNR (penarikan) agar audit trail konsisten.
     * Hanya dilakukan jika biaya > 0 (artinya bank berbeda & entri ada di master).
     *
     * @param int    $nasabahId        ID nasabah
     * @param string $bankNasabah      Nama bank nasabah (penerima)
     * @param string $keteranganRef    Keterangan untuk keperluan apa (mis: "Pencairan Pinjaman #ID")
     * @param int    $adminId          ID admin yang memproses
     *
     * @return array{success: bool, message: string, biaya: float}
     */
    public function potongBiayaTransfer(
        int $nasabahId,
        string $bankNasabah,
        string $keteranganRef,
        int $adminId
    ): array {
        $biaya = $this->getBiayaTransfer(self::BANK_KOPERASI, $bankNasabah);

        if ($biaya <= 0) {
            return ['success' => true, 'message' => 'Tidak ada biaya transfer.', 'biaya' => 0.0];
        }

        // Cek kecukupan saldo
        $saldo = $this->getSaldoTabungan($nasabahId);
        if ($saldo < $biaya) {
            return [
                'success' => false,
                'message' => sprintf(
                    'Saldo tabungan nasabah tidak mencukupi untuk biaya admin transfer (Rp %s). ' .
                    'Saldo tersedia: Rp %s.',
                    number_format($biaya, 0, ',', '.'),
                    number_format($saldo, 0, ',', '.')
                ),
                'biaya' => $biaya,
            ];
        }

        try {
            // Buat TransTabungan PNR
            $idVia   = DB::table('jns_via')->where('kode', 'TF')->value('id');
            $idTrans = DB::table('jns_transaksi')->where('kode', 'PNR')->value('id');
            $idTransaksi = IdGenerator::generate('trans_tabungan', 'T', 'TF', 'PNR');

            TransTabungan::create([
                'id'                => $idTransaksi,
                'id_anggota'        => $nasabahId,
                'id_jns_via'        => $idVia,
                'id_jns_transaksi'  => $idTrans,
                'nominal'           => $biaya,
                'keterangan'        => 'Biaya Admin Transfer Antarbank - ' . $keteranganRef,
                'tgl_transaksi'     => now(),
                'admin_pengelola_id'=> $adminId,
            ]);

            Log::info('BankAccessService: Biaya transfer dipotong', [
                'nasabah_id'   => $nasabahId,
                'bank_nasabah' => $bankNasabah,
                'biaya'        => $biaya,
                'ref'          => $keteranganRef,
                'admin_id'     => $adminId,
            ]);

            return [
                'success' => true,
                'message' => sprintf(
                    'Biaya admin transfer Rp %s telah dipotong dari saldo tabungan.',
                    number_format($biaya, 0, ',', '.')
                ),
                'biaya' => $biaya,
            ];
        } catch (\Exception $e) {
            Log::error('BankAccessService: Gagal memotong biaya transfer', [
                'nasabah_id' => $nasabahId,
                'error'      => $e->getMessage(),
            ]);

            return [
                'success' => false,
                'message' => 'Gagal memotong biaya transfer: ' . $e->getMessage(),
                'biaya'   => $biaya,
            ];
        }
    }
}
