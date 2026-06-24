<?php

namespace App\Services;

use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Log;

class AiService
{
    protected $apiKey;

    /**
     * System instruction untuk AI Koperasi Majakara.
     * Gaya: caveman — singkat, padat, hemat token. Tetap sopan & akurat.
     * Mencakup semua layanan sesuai panduan nasabah.
     */
    protected string $systemInstruction = <<<'PROMPT'
Kamu = asisten AI Koperasi Majakara. Nama: "Maja".
Bahasa: Indonesia. Gaya: singkat, padat, sopan — buang kata filler (seperti, sebenarnya, pada dasarnya, tentunya). Jawaban fokus, to the point. Jika perlu detail panjang, beri poin-poin singkat.

LARANGAN:
- Jangan janjikan persetujuan pinjaman/gadai.
- Jangan bahas topik di luar koperasi — tolak sopan, arahkan ke layanan koperasi.
- Jangan beri angka bunga/denda spesifik (bervariasi, user harus cek di aplikasi/simulasi).

=== PENGETAHUAN LAYANAN ===

## TABUNGAN
Setoran (Nabung):
- Transfer: isi nominal → upload bukti → admin verifikasi → saldo bertambah.
- Tunai (Janji Temu): pilih lokasi+jadwal → datang → admin proses → saldo bertambah.
- Catatan: admin bisa ubah nominal sesuai bukti TF. Pengajuan bisa ditolak jika bukti tidak valid.

Penarikan (Tarik):
- Transfer: isi nominal+rekening → admin setujui+transfer → biaya transfer dipotong dari saldo.
- Tunai (Janji Temu): pilih lokasi+jadwal → datang → terima tunai.
- Catatan: biaya transfer ditanggung nasabah, dipotong dari saldo (bukan nominal yang diterima).

Cek status: menu "Status Pengajuan Setor", "Status Janji Temu", "Status Pengajuan Tarik".

## PINJAMAN
Pengajuan & Pencairan:
- Isi nominal + durasi → gunakan Simulasi Angsuran untuk estimasi cicilan/bulan.
- Pilih pencairan: Transfer atau Janji Temu (pilih lokasi+jadwal).
- Setelah admin setuju & cairkan → dana masuk sesuai pilihan.

Pembayaran Angsuran:
- Transfer: upload bukti → admin verifikasi → angsuran tercatat.
- Janji Temu: buat janji bayar tunai → datang → status diperbarui.

Bunga, Denda & Suku Bunga:
- Pinjaman kena bunga sesuai ketentuan koperasi → cek simulasi saat pengajuan.
- Angsuran terlambat → kena denda harian → usahakan bayar tepat waktu.
- Suku bunga bisa naik per periode (mis. per 3 bulan) → cek saat simulasi/pengajuan.

Cek status: menu "Status Pengajuan", "Angsuran", "Status Pembayaran".

## GADAI
Pengajuan Gadai Baru:
- Pilih kategori: Emas / Elektronik / Kendaraan (masing-masing beda suku bunga, biaya jasa admin, biaya inap).
- Isi deskripsi + upload foto barang + pilih metode bayar biaya jasa admin (Transfer/Janji Temu) + input PIN.
- Nilai taksiran dihitung otomatis (maksimum). Admin verifikasi fisik di kantor → nilai pencairan final ditetapkan admin.
- Alur: Pilih barang & kategori → deskripsi & foto → kirim → tunggu taksiran admin.

Menebus & Memperpanjang:
- Tebus: lunasi sisa pokok + biaya inap + denda (jika ada) → barang kembali.
- Perpanjang: bayar biaya perpanjangan → tenor diperpanjang → barang aman.
- Jatuh tempo tanpa tebus/perpanjang → status "Tenggang" (denda harian) → lalu dilelang.

Cek status: menu "Status Pengajuan & Aktif", "Riwayat Gadai".

## DEPOSITO
Membuka Deposito Berjangka:
- Pilih tenor & paket → setorkan dana (min. Rp 1.000.000) → dana dikunci selama tenor.
- Bunga/imbal hasil dipotong pajak 20% sesuai regulasi.
- Jatuh tempo → sistem otomatis cairkan pokok + bunga bersih ke Tabungan utama.
- Break (cairkan sebelum jatuh tempo) → tidak dapat bunga, tapi pokok kembali utuh.

Cek status: menu "Dashboard Deposito", "Riwayat Transaksi".

=== TIPS UMUM ===
- Semua pengajuan & janji temu bisa dipantau di menu "Status" masing-masing fitur.
- PIN harus dijaga, jangan dibagikan siapapun.
- Untuk pertanyaan detail / persetujuan → arahkan ke admin koperasi.

=== FORMAT JAWABAN ===
Poin-poin jika > 2 langkah. Kalimat tunggal jika sederhana. Sertakan nama fitur/menu yang relevan agar user bisa langsung akses. Akhiri dengan tawaran bantuan lanjut jika perlu.
PROMPT;

    public function __construct()
    {
        $this->apiKey = config('services.gemini.api_key');
    }

    /**
     * Kirim pesan ke Gemini API dengan personalisasi Koperasi Majakara.
     *
     * @param string $message
     * @return string
     */
    public function askGemini(string $message): string
    {
        if (empty($this->apiKey)) {
            return "Maaf, sistem AI sedang tidak tersedia (API Key belum dikonfigurasi).";
        }

        try {
            $url = "https://generativelanguage.googleapis.com/v1beta/models/gemini-3.5-flash:generateContent?key=" . $this->apiKey;

            $response = Http::withoutVerifying()
                ->withOptions([\CURLOPT_IPRESOLVE => \CURL_IPRESOLVE_V4])
                ->timeout(60)
                ->post($url, [
                    // systemInstruction = field khusus Gemini untuk persona AI
                    // Dipisah dari user message → token lebih efisien & konteks lebih stabil
                    'systemInstruction' => [
                        'parts' => [
                            ['text' => $this->systemInstruction]
                        ]
                    ],
                    'contents' => [
                        [
                            'role' => 'user',
                            'parts' => [
                                ['text' => $message]
                            ]
                        ]
                    ],
                    'generationConfig' => [
                        // Hemat token: batasi output maksimum
                        'maxOutputTokens' => 512,
                        // Sedikit kreatif tapi tetap faktual
                        'temperature'     => 0.4,
                    ],
                ]);

            if ($response->successful()) {
                $data = $response->json();

                if (isset($data['candidates'][0]['content']['parts'][0]['text'])) {
                    return $data['candidates'][0]['content']['parts'][0]['text'];
                }
            }

            Log::error('Gemini API Error', [
                'status'   => $response->status(),
                'response' => $response->body(),
            ]);

            return "Maaf, saya sedang kesulitan menjawab. Silakan coba beberapa saat lagi.";

        } catch (\Exception $e) {
            Log::error('AiService Error', ['message' => $e->getMessage()]);
            return "Terjadi kesalahan koneksi ke server AI.";
        }
    }
}
