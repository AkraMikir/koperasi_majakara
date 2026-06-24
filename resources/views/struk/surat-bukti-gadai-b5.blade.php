<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Bukti Gadai - {{ $data['slot_kode'] }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: 9.5in 5.5in;
            margin: 0.17in 0.51in;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 9px;
            line-height: 1.25;
            color: #000;
            background-color: #fff;
        }

        .content-pad {
            padding: 0 10px;
        }

        .header-table {
            width: 100%;
            margin-bottom: 8px;
        }

        .header-table td {
            vertical-align: middle;
        }

        .logo-img {
            max-height: 60px;
            width: auto;
            display: inline-block;
            vertical-align: middle;
        }

        .koperasi-info {
            font-size: 8.5px;
            padding-left: 10px !important;
        }

        .nasabah-info {
            font-size: 8.5px;
            text-align: right;
        }

        .banner {
            background-color: #935a16;
            color: #fff;
            text-align: center;
            font-weight: bold;
            font-size: 10.5px;
            padding: 4px 10px;
            letter-spacing: 1px;
            margin-bottom: 8px;
            margin-left: -10px;
            margin-right: -10px;
            text-transform: uppercase;
        }

        .params-title {
            font-weight: bold;
            font-size: 8.5px;
            color: #111;
            margin-bottom: 1px;
        }

        .params-value {
            font-size: 8.5px;
            color: #333;
        }

        .perjanjian-section {
            margin-bottom: 8px;
        }

        .perjanjian-text {
            font-size: 7.5px;
            text-align: left;
            margin-bottom: 2px;
        }

        .footer-section {
            width: 100%;
            border-collapse: collapse;
            margin-top: 15px;
        }

        .footer-section td {
            vertical-align: top;
            text-align: center;
            font-size: 8px;
        }

        .info-box {
            border: 1px solid #935a16;
            padding: 4px 6px;
            text-align: left;
            font-size: 7.5px;
            color: #935a16;
            border-radius: 4px;
            width: 190px;
            line-height: 1.2;
        }

        .signature-title {
            font-weight: bold;
            margin-bottom: 45px;
        }

        .signature-line {
            border-top: 1px solid #000;
            width: 100%;
            margin-top: 2px;
        }
    </style>
</head>

<body>
    <div class="content-pad">
    <!-- HEADER KOP -->
    <table class="header-table">
        <tr>
            <!-- Logo -->
            <td style="width: 18%; text-align: right; padding-right: 10px;">
                @if(file_exists(public_path('images/logo/674c1d MAJAKARA.png')))
                <img src="{{ public_path('images/logo/674c1d MAJAKARA.png') }}" class="logo-img" alt="Logo">
                @else
                <div style="font-weight: bold; font-size: 14px; color: #935a16;">MAJAKARA</div>
                @endif
            </td>
            <!-- Koperasi Info -->
            <td class="koperasi-info" style="width: 52%;">
                <table style="width: 100%; border-collapse: collapse; font-size: 8.5px; line-height: 1.2;">
                    <tr>
                        <td style="width: 90px; font-weight: bold; vertical-align: top;">Kantor Perwakilan</td>
                        <td style="width: 5px; vertical-align: top;">:</td>
                        <td style="vertical-align: top;">{{ $settings->alamat_koperasi }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; vertical-align: top;">Nomor Tlp Kantor</td>
                        <td style="vertical-align: top;">:</td>
                        <td style="vertical-align: top;">{{ $settings->no_telp }}</td>
                    </tr>
                    <tr>
                        <td style="font-weight: bold; vertical-align: top;">Kritik dan Saran</td>
                        <td style="vertical-align: top;">:</td>
                        <td style="vertical-align: top;">{{ $settings->no_telp }}</td>
                    </tr>
                </table>
            </td>
            <!-- Nasabah Info -->
            <td class="nasabah-info" style="width: 30%;">
                Nama: {{ $data['nama_anggota'] }}<br>
                Alamat: {{ $data['alamat_nasabah'] ?? '-' }}<br>
                Negara: ID<br>
                Kode Pos: {{ $data['kode_pos_nasabah'] ?? '-' }}
            </td>
        </tr>
    </table>
    </div>

    <!-- BANNER BROWN -->
    <div class="banner">
        Surat Bukti Gadai
    </div>

    <div class="content-pad">
    <!-- CONTENT DATA & CALCULATIONS (UNIFIED GRID TABLE) -->
    <table style="width: 100%; border-collapse: collapse; border: none; margin-bottom: 8px;">
        <!-- ROW 1 -->
        <tr>
            <td style="width: 20%; border: none; padding: 4px; font-weight: bold; text-align: center;">Kategori Barang</td>
            <td style="width: 20%; border: none; padding: 4px; font-weight: bold; text-align: center;">Deskripsi Barang</td>
            <td style="width: 20%; border: none; padding: 4px; font-weight: bold; text-align: center;">Slot Kode</td>
            <td style="width: 20%; border: 1px solid #000; padding: 4px 6px; font-weight: bold; vertical-align: middle;">Nominal Deal:</td>
            <td style="width: 20%; border: 1px solid #000; padding: 4px 6px; text-align: right; font-weight: bold; font-size: 8.5px; vertical-align: middle;">Rp {{ number_format($data['nominal_deal'], 0, ',', '.') }},-</td>
        </tr>
        <!-- ROW 2 -->
        <tr>
            <td style="border: none; padding: 6px 4px; text-align: center; vertical-align: middle; font-weight: bold;">{{ $data['kategori'] }}</td>
            <td style="border: none; padding: 6px 4px; text-align: center; vertical-align: middle; font-weight: bold;">{{ $data['barang'] }}</td>
            <td style="border: none; padding: 6px 4px; text-align: center; vertical-align: middle; font-weight: bold;">{{ $data['slot_kode'] }}</td>
            <td style="border: 1px solid #000; padding: 4px 6px; font-weight: bold; vertical-align: middle;">Biaya Jasa:</td>
            <td style="border: 1px solid #000; padding: 4px 6px; text-align: right; font-weight: bold; font-size: 8.5px; vertical-align: middle;">Rp {{ number_format($data['biaya_jasa'], 0, ',', '.') }},-</td>
        </tr>
        <!-- ROW 3 -->
        <tr>
            <td style="border: none; padding: 4px; font-weight: bold; text-align: center;">Tanggal Mulai</td>
            <td style="border: none; padding: 4px; font-weight: bold; text-align: center;">Jatuh Tempo</td>
            <td style="border: none; padding: 4px; font-weight: bold; text-align: center;">Status Transaksi</td>
            <td style="border: 1px solid #000; padding: 4px 6px; font-weight: bold; vertical-align: middle;">Biaya Inap:</td>
            <td style="border: 1px solid #000; padding: 4px 6px; text-align: right; font-weight: bold; font-size: 8.5px; vertical-align: middle;">Rp {{ number_format($data['biaya_inap'], 0, ',', '.') }},-</td>
        </tr>
        <!-- ROW 4 -->
        <tr>
            <td style="border: none; padding: 6px 4px; text-align: center; vertical-align: middle; font-weight: bold;">{{ $data['tgl_mulai'] }}</td>
            <td style="border: none; padding: 6px 4px; text-align: center; vertical-align: middle; font-weight: bold;">{{ $data['jatuh_tempo'] }}</td>
            <td style="border: none; padding: 6px 4px; text-align: center; vertical-align: middle; font-weight: bold;">{{ $data['jenis_trans'] }}</td>
            <td style="border: 1px solid #000; padding: 4px 6px; font-weight: bold; vertical-align: middle;">Denda Aktif:</td>
            <td style="border: 1px solid #000; padding: 4px 6px; text-align: right; font-weight: bold; font-size: 8.5px; vertical-align: middle;">Rp {{ number_format($data['denda_aktif'] ?? 0, 0, ',', '.') }},-</td>
        </tr>
        <!-- ROW 5 -->
        <tr>
            <td colspan="3" style="border: none; padding: 4px;"></td>
            <td style="border: 1px solid #000; padding: 4px 6px; font-weight: bold; vertical-align: middle;">Extra Pinjaman:</td>
            <td style="border: 1px solid #000; padding: 4px 6px; text-align: right; font-weight: bold; font-size: 8.5px; vertical-align: middle;">Rp {{ number_format($data['extra_pinjaman_nominal'] ?? 0, 0, ',', '.') }},-</td>
        </tr>
        <!-- ROW 6 -->
        <tr>
            <td colspan="3" style="border: none; padding: 4px;"></td>
            <td style="border: 1px solid #000; padding: 4px 6px; font-weight: bold; vertical-align: middle;">Total Tagihan:</td>
            <td style="border: 1px solid #000; padding: 4px 6px; text-align: right; font-weight: bold; font-size: 8.5px; color: #935a16; vertical-align: middle;">Rp {{ number_format($data['total_tagihan'], 0, ',', '.') }},-</td>
        </tr>
    </table>

    <!-- PERJANJIAN SYARAT KETENTUAN -->
    <div class="perjanjian-section">
        <table style="width:100%; border-collapse:collapse; margin-bottom: 6px;">
            <tr>
                <td style="width: 33%; vertical-align: middle;">
                    <div style="border-bottom: 1px dashed #935a16;"></div>
                </td>
                <td style="text-align: center; white-space: nowrap; padding: 0 8px; font-weight: bold; font-size: 8.5px; color: #935a16; text-transform: uppercase; vertical-align: middle;">
                    SYARAT &amp; KETENTUAN GADAI
                </td>
                <td style="width: 33%; vertical-align: middle;">
                    <div style="border-bottom: 1px dashed #935a16;"></div>
                </td>
            </tr>
        </table>
        @if(!empty($settings->syarat_ketentuan_gadai))
        <div class="perjanjian-text" style="white-space: pre-line;">{{ $settings->syarat_ketentuan_gadai }}</div>
        @else
        <div class="perjanjian-text">
            1. Nasabah wajib membawa Surat Bukti Gadai saat melakukan penebusan/perpanjangan.<br>
            2. Apabila sampai dengan tanggal jatuh tempo tidak ada pelunasan atau perpanjangan, barang jaminan menjadi hak milik Majakara.<br>
            3. Bunga dan biaya inap mengikuti ketentuan yang berlaku di koperasi.
        </div>
        @endif
    </div>

    <!-- BEKASI DATE - section tersendiri di atas tanda tangan -->
    <div style="text-align: right; font-size: 8px; margin-bottom: 4px; padding-right: 0;">
        Bekasi, .....................................
    </div>

    <!-- SIGNATURES AND INFO BOX -->
    <table class="footer-section">
        <tr>
            <!-- Info Box -->
            <td style="width: 35%; text-align: left; vertical-align: middle;">
                <div class="info-box">
                    <strong>GADAI NYAMAN &amp; AMAN</strong><br>
                    HARI BESAR DAN HARI MINGGU TETAP BUKA<br>
                    Jam Pengambilan Barang: 08.00 - 18.00<br>
                    Buka Jam: 08.00 - 20.00
                </div>
            </td>
            <!-- Signature: Nasabah -->
            <td style="width: 20%; vertical-align: top; text-align: center;">
                <div class="signature-title" style="margin-bottom: 48px;">Nasabah/yang dikuasakan,</div>
                <div style="border-top: 1px solid #000; padding-top: 2px;">(...................................)</div>
            </td>

            <!-- Conditional Emergency Contact -->
            @if((float)$data['nominal_deal'] >= 1000000)
            <td style="width: 20%; vertical-align: top; text-align: center;">
                <div class="signature-title" style="margin-bottom: 48px;">Kontak Darurat,</div>
                <div style="border-top: 1px solid #000; padding-top: 2px;">(...................................)</div>
            </td>
            @endif

            <!-- Signature: Petugas -->
            <td style="width: 25%; vertical-align: top; text-align: center;">
                <div class="signature-title" style="margin-bottom: 48px;">Petugas,</div>
                <div style="border-top: 1px solid #000; padding-top: 2px;">(...................................)</div>
            </td>
        </tr>
    </table>
    </div><!-- end content-pad -->
</body>

</html>
