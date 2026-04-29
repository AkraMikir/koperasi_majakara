<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Transaksi Tabungan - Koperasi Majakara</title>
    <style>
        /* CSS reset & base settings */
        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }
        body {
            font-family: Arial, Helvetica, sans-serif; /* Lebih terbaca di printer kasir */
            font-size: 11px; /* Ukuran font dikecilkan agar muat di 58mm */
            color: #000;
            background: #fff;
            line-height: 1.2; /* Jarak antar baris dirapatkan */
            padding: 0;
        }
        
        /* Thermal Paper Width Settings (typically 58mm or 80mm) */
        .receipt-container {
            width: 100%;
            max-width: 280px; /* Adjust for 58mm/80mm layout */
            margin: 0 auto;
            padding: 0 2px;
        }

        /* Typography & Alignment */
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .text-left { text-align: left; }
        .font-bold { font-weight: bold; }
        .text-sm { font-size: 9px; }
        .text-lg { font-size: 12px; }
        .mb-1 { margin-bottom: 1px; }
        .mb-2 { margin-bottom: 2px; }
        .mb-4 { margin-bottom: 4px; }
        .mt-1 { margin-top: 1px; }
        .mt-2 { margin-top: 2px; }
        .mt-4 { margin-top: 4px; }

        /* Borders & Layout */
        .solid-line {
            border-top: 1px solid #000;
            margin: 1px 0;
        }
        .flex { display: flex; }
        .justify-between { justify-content: space-between; }
        
        .logo {
            max-width: 120px;
            margin: 0 auto 4px;
            display: block;
            filter: grayscale(100%) contrast(1000%); /* Bantu hitam-putih untuk printer */
        }

        .row {
            display: flex;
            align-items: flex-start;
            margin-bottom: 1px;
        }
        .label {
            flex: 0 0 75px; /* Lebar fixed untuk label agar tidak pecah */
            text-align: left;
        }
        .colon {
            flex: 0 0 10px;
            text-align: center;
        }
        .value {
            flex: 1 1 auto;
            text-align: right;
            word-break: break-word; /* Hanya break kata kalau memang kepanjangan */
        }

        .signatures {
            display: flex;
            justify-content: space-between;
            margin-top: 12px;
        }
        .sign-box {
            text-align: center;
            width: 45%;
        }
        .sign-area {
            height: 35px; /* Kurangi gap untuk tanda tangan */
        }
        .sign-name {
            border-top: 1px solid #000;
            padding-top: 2px;
            font-size: 9px;
        }

        /* Print Specific CSS */
        @media print {
            body {
                padding: 0;
            }
            .receipt-container {
                width: 100%;
                max-width: none;
                margin: 0;
                padding: 0 2px;
            }
            /* Hide print dialog header/footer if possible */
            @page {
                margin: 0;
            }
        }
    </style>
</head>
<body>
    <div class="receipt-container">
        <!-- Header -->
        <div class="text-center mb-4">
            <img src="{{ asset('images/logo/674c1d MAJAKARA.png') }}" alt="Logo Koperasi Majakara" style="max-width: 100px;" />
            <p class="font-bold">STRUK TRANSAKSI TABUNGAN</p>
            <p class="font-bold uppercase">{{ $transaksi->jenis }}</p>
        </div>

        <div class="solid-line"></div>

        <!-- Transaction Details -->
        <div class="mb-2">
            <div class="row">
                <span class="label">Tanggal</span>
                <span class="colon">:</span>
                <span class="value">{{ $transaksi->tgl_transaksi ? $transaksi->tgl_transaksi->format('d/m/Y H:i') : '-' }}</span>
            </div>
            <div class="row">
                <span class="label">No. Transaksi</span>
                <span class="colon">:</span>
                <span class="value text-sm">{{ $transaksi->id_transaksi ?? str_pad($transaksi->id ?? '', 5, '0', STR_PAD_LEFT) }}</span>
            </div>
            @if($transaksi->pengajuanSetor)
            <div class="row">
                <span class="label">ID Pengajuan</span>
                <span class="colon">:</span>
                <span class="value text-sm">{{ $transaksi->pengajuanSetor->id }}</span>
            </div>
            @endif
            <div class="row">
                <span class="label">Nama Nasabah</span>
                <span class="colon">:</span>
                <span class="value">{{ $transaksi->nasabah->user->nama ?? 'N/A' }}</span>
            </div>
        </div>

        <div class="solid-line"></div>

        <!-- Nominal Details -->
        <div class="mb-2">
            @if($transaksi->jenis === 'penarikan' && $transaksi->pengajuanTarik && (float)($transaksi->pengajuanTarik->biaya_transfer ?? 0) > 0)
            <div class="row">
                <sp an class="label">Nilai Tarik</sp>
                <span class="colon">:</span>
                <span class="value">Rp {{ number_format($transaksi->pengajuanTarik->nominal ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="row">
                <span class="label">Biaya Transfer</span>
                <span class="colon">:</span>
                <span class="value">Rp {{ number_format($transaksi->pengajuanTarik->biaya_transfer ?? 0, 0, ',', '.') }}</span>
            </div>
            <div class="solid-line"></div>
            @endif
            
            <div class="row font-bold">
                <span class="label">Total {{ $transaksi->jenis === 'setoran' ? 'Setoran' : 'Didebet' }}</span>
                <span class="colon">:</span>
                <span class="value text-lg">Rp {{ number_format($transaksi->nominal ?? 0, 0, ',', '.') }}</span>
            </div>
            
            <div class="row">
                <span class="label">Metode</span>
                <span class="colon">:</span>
                <span class="value">{{ ucfirst($transaksi->via ?? '-') }}</span>
            </div>
            @if(!empty($transaksi->keterangan))
            <div class="row mt-2">
                <span class="label">Keterangan</span>
                <span class="colon">:</span>
                <span class="value" style="font-weight: normal; text-align: left;">{{ $transaksi->keterangan }}</span>
            </div>
            @endif
        </div>

        <div class="solid-line"></div>

        <!-- Approval & Note -->
        <div class="text-center mb-4 text-sm mt-4">
            <p>Terima kasih atas transaksi Anda.</p>
            <p class="font-bold mt-2 uppercase">SIMPAN TANDA TERIMA INI SEBAGAI BUKTI YANG SAH</p>
        </div>

        <!-- Signatures -->
        <div class="signatures">
            <div class="sign-box">
                <p>Petugas</p>
                <div class="sign-area"></div>
                @php
                    $pengajuanSetor = $transaksi->pengajuanSetor ?? null;
                    $approver = $pengajuanSetor && $pengajuanSetor->relationLoaded('approvedBy') ? $pengajuanSetor->approvedBy : null;
                    $petugasName = $approver ? $approver->nama : ($transaksi->adminPengelola ? $transaksi->adminPengelola->nama : 'Admin');
                @endphp
                <p class="sign-name">{{ $petugasName }}</p>
            </div>
            <div class="sign-box">
                <p>Nasabah</p>
                <div class="sign-area"></div>
                <p class="sign-name">{{ $transaksi->nasabah->user->nama ?? 'Nasabah' }}</p>
            </div>
        </div>

    </div>

    <script>
        window.onload = function() {
            // Memberikan sedikit delay agar aset (seperti logo) sempat dimuat
            setTimeout(function() {
                window.print();
            }, 500);
        };

        // Otomatis menutup tab setelah dialog print tertutup (baik cancel maupun print)
        window.onafterprint = function() {
            window.close();
        };
    </script>
</body>
</html>
