<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Surat Bukti Pinjaman - {{ $data['no_pinjaman'] }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        @page {
            size: 9.5in 7.5in;
        }

        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.25;
            color: #000;
            background-color: #fff;
        }


        .header-table {
            width: 100%;
            border-collapse: collapse;
        }

        .header-table td {
            vertical-align: middle;
        }

        .logo-img {
            max-height: 70px;
            width:90px;
            display: inline-block;
            vertical-align: middle;
        }

        .koperasi-info {
            font-size: 12px;
        }

        .koperasi-info td {
            font-size: 12px;
        }

        .content_pad{
            padding: 0 20px;
        }

        .nasabah-info {
            font-size: 12px;
            text-align: right;
        }

        .banner {
            background-color: #935a16;
            color: #fff;
            text-align: center;
            font-weight: bold;
            font-size: 13.5px;
            padding: 3px 10px;
            letter-spacing: 1px;
            margin-bottom: 6px;
            margin-left: -30px;
            margin-right: -30px;
            text-transform: uppercase;
        }

        .params-title {
            font-weight: bold;
            font-size: 11px;
            color: #111;
            margin-bottom: 1px;
        }

        .params-value {
            font-size: 11px;
            color: #333;
        }

        .angsuran-subtable {
            margin: 0;
            width: 100%;
            margin-bottom: 8px;
            border-collapse: collapse;
        }

        .angsuran-subtable th,
        .angsuran-subtable td {
            border: 3px solid #ccc;
            padding: 2px 3px;
            font-size: 11px;
            /* perbesar table jadwal angsuran */
            text-align: center;
        }

        .perjanjian-section {
            margin-bottom: 6px;
        }

        .perjanjian-title{
            font-size:10px;
            text-align:left;
            margin-bottom:1px;
            padding-left:15px;
        }

        .perjanjian-text {
            font-size: 10px;
            text-align: left;
            margin-bottom: 2px;
        }

        .perjanjian-list {
            padding-left:5px;
            list-style-type: decimal;
            margin-left: 12px;
            font-size: 10px;
        }

        .perjanjian-list li {
            margin-bottom: 1px;
            text-align: left;
        }

        .footer-section {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }

        .footer-section td {
            vertical-align: top;
            text-align: center;
            font-size: 11px;
        }

        .info-box {
            border: 1px solid #935a16;
            padding: 3px 5px;
            text-align: left;
            font-size: 8px;
            color: #935a16;
            border-radius: 4px;
            line-height: 1.2;
        }

        .signature-title {
            font-weight: bold;
            padding-bottom: 65px;
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
         <!-- NO. STRUK (tengah) -->
                <div style="padding-left: 10px;">
                    <div style="font-size: 8px; font-weight: bold; letter-spacing: 1px;">{{ $data['no_struk'] ?? '' }}</div>
                </div>
        <table class="header-table">
            <tr>
                <!-- Logo -->
                <td style="width: 10%; text-align: right; padding-right: 10px;">
                    @if(!empty($logoBase64))
                    <img src="{{ $logoBase64 }}" class="logo-img" alt="Logo">
                    @else
                    <div style="font-weight: bold; font-size: 18px; color: #935a16;">MAJAKARA</div>
                    @endif
                </td>
                <!-- Koperasi Info -->
                <td class="koperasi-info" style="width: 30%; font-size: 10px; line-height: 1.35; padding-left: 15px;">
                    <strong>Kantor Perwakilan</strong> : {{ $settings->alamat_koperasi }} |<br>
                     <strong>Nomor Tlp Kantor</strong> : {{ $settings->no_telp }} | <strong>Email</strong>: {{ $settings->email }}
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
        Surat Bukti Pinjaman
    </div>

    <div class="content-pad">
        <!-- CONTENT DATA & CALCULATIONS (UNIFIED GRID TABLE) -->
        <table style="width: 100%; border-collapse: collapse; border: none; margin-bottom: 6px;">
            <!-- ROW 1 (Labels for Params 1-3 & Cicilan Per Bulan Row) -->
            <tr>
                <td style="width: 20%; border: none; padding: 2px 4px; text-align: left;">Tujuan
                    Pinjaman</td>
                <td style="width: 20%; border: none; padding: 2px 4px; text-align: left;">: {{ 
                    $data['tujuan_pinjaman'] ?? 'Modal Usaha' }}
                </td>
                <td style="width: 20%; border: none; padding: 2px 4px; text-align: left;">Tenor
                    Pinjaman
                </td>
                <td style="width: 20%; padding: 2px 4px; vertical-align: middle; text-align:left;">
                    : {{ $data['lama_pinjam'] }} Bulan</td>
            </tr>
            <!-- ROW 2 (Values for Params 1-3 & Nominal Harus Dibayarkan Row) -->
            <tr>
                <td
                    style="border: none; padding: 2px 4px; text-align: left; vertical-align: middle;">
                    Tanggal Pencairan</td>
                <td
                    style="border: none; padding: 2px 4px; text-align: left; vertical-align: middle;">
                    : {{ $data['tanggal'] }}</td>
                <td
                    style="border: none; padding: 2px 4px; text-align: left; vertical-align: middle;">
                    Biaya Keterlambatan</td>
                <td style="padding: 2px 4px; vertical-align: middle; text-align: left;">
                    : {{ number_format($data['denda_rate'] ?? 0, 2) }}% Per Hari</td>
            </tr>

            <!-- ROW 3 (Values for Params 4-6 & Nominal Diterima Row) -->
            <tr>
                <td
                    style="border: none; padding: 2px 4px; text-align: left; vertical-align: middle;">
                    Suku Bunga</td>
                <td
                    style="border: none; padding: 2px 4px; text-align: left; vertical-align: middle; font-size: 11px;">
                    : {{ number_format($data['bunga_rate'] ?? 0, 2) }}% Flat per bulan</td>
                <td
                    style="border: none; padding: 2px 4px; text-align: left; vertical-align: middle;">
                    Jangka Waktu Pelunasan</td>
                <td style=" padding: 2px 4px; vertical-align: middle; text-align: left;">
                    : {{ $data['tanggal'] }} s/d {{
                    $data['tanggal_jatuh_tempo'] ?? '-' }}</td>
            </tr>
            <!-- ROW 4 Pinjaman-->
            <tr>
                <td
                    style="border: none; padding: 2px 4px; text-align: left; vertical-align: middle;">
                    Nominal Pokok Pinjaman</td>
                <td
                    style="border: none; padding: 2px 4px; text-align: left; vertical-align: middle;">
                    : Rp {{ number_format($data['jumlah_pinjam'], 0, ',', '.') }},-</td>
                <td
                style="border: none; padding: 2px 4px; text-align: left; vertical-align: middle;">
                Nominal wajib dibayarkan</td>
                <td
                    style="border: none; padding: 2px 4px; text-align: left; vertical-align: middle;">
                    : Rp {{ number_format($data['nominal_total_bayar'], 0, ',', '.') }},-</td>
            </tr>
        </table>
        @if(isset($data['jadwal_angsuran']) && count($data['jadwal_angsuran']) > 0)
                    <table class="angsuran-subtable">
                        <tbody>
                            <tr>
                                <td style="font-size: 11px; padding: 2px; text-align: center;">Cicilan Perbulan</td>
                                @foreach($data['jadwal_angsuran'] as $ags)
                                <td style="font-size: 11px; padding: 2px; text-align: center;">{{
                                    $ags['jatuh_tempo'] }}</td>   
                                @endforeach
                            </tr>
                            <tr>
                                <td> </td>
                                @foreach($data['jadwal_angsuran'] as $ags)
                                <td style="font-size: 11px; padding: 2px; text-align: center;">
                                    Rp {{ number_format($ags['tagihan'], 0, ',', '.') }}</td>
                                @endforeach
                            </tr>
                        </tbody>
                    </table>
                    @else
                    <div style="font-weight: bold; text-align: right; padding-right: 4px; font-size: 12px;">Rp {{
                        number_format($data['angsuran_pertama'], 0, ',', '.') }},-</div>
                    @endif

        <!-- PERJANJIAN SYARAT KETENTUAN -->
        <div class="perjanjian-section">
            @if(!empty($settings->syarat_ketentuan_pinjaman))
            <div class="perjanjian-text" style="white-space: pre-line;">{!! str_replace(
                ['{tanggal}', 'MAJAKARA'],
                [
                    $data['tanggal'] ?? now()->format('d/m/Y'),
                    '<strong>MAJAKARA</strong>'
                ],
                e($settings->syarat_ketentuan_pinjaman)
            ) !!}</div>
            @else
            <div class="perjanjian-title">
                Yang bertanda tangan dibawah ini:
            </div>
            <div class="perjanjian-text">
                Bekasi, @php
                $now = new DateTime(); 
                echo $now->format('Y-m-d');
                @endphp
                Petugas bagian kredit bertindak untuk dan atas nama <strong>MAJAKARA</strong> dengan nasabah membuat
                perjanjian sebagai berikut:
            </div>
            <ol class="perjanjian-list">
                <li>Saya bersedia memberikan informasi data pribadi dan kontak darurat kepada PIHAK
                    <strong>MAJAKARA</strong>.
                </li>
                <li>Nasabah wajib menyimpan Surat Bukti Pinjam <strong>MAJAKARA</strong>.</li>
                <li style="width:50%;">Saya bersedia dan tidak ada TUNTUTAN DALAM BENTUK APAPUN, baik secara PIDANA/PERDATA kepada Pihak
                    <strong>MAJAKARA</strong>, jika saya LALAI/tidak melakukan pembayaran sampai Tanggal Jatuh Tempo
                    Saya Bersedia disita barang saya senilai pinjaman dan bunga oleh pihak <strong>MAJAKARA</strong>.
                </li>
                <li>Bunga dan biaya administrasi mengikuti ketentuan yang berlaku.</li>
                <li>Pihak Majakara berhak menolak barang yang tidak memenuhi syarat.</li>
                <li>Segala bentuk wanprestasi akan diselesaikan sesuai hukum yang berlaku.</li>
            </ol>
            @endif
        </div>

        <div style="text-align: right; font-size: 11px; margin-bottom: 4px; padding-right: 0;">
            Bekasi, .....................................
        </div>
        <!-- SIGNATURES AND INFO BOX -->
        <table class="footer-section">
            <tr>
                <!-- Info Box -->
                <td style="width: 25%; text-align: left; vertical-align: middle;">
                    <div class="info-box" style="white-space: pre-line;">@if(!empty($settings->info_box_pinjaman)){!! str_replace('MAJAKARA', '<strong>MAJAKARA</strong>', e($settings->info_box_pinjaman)) !!}@else<strong>PINJAMAN BISA DIANGSUR</strong>
HARI BESAR DAN HARI MINGGU TETAP BUKA
Jam Pengambilan Barang: 08.00 - 18.00
Buka Jam: 08.00 - 20.00 @endif</div>
                </td>
                <!-- Signature: Nasabah -->
                <td style="width: 25%; vertical-align: top; text-align: center;">
                    <div class="signature-title">Nasabah/yang dikuasakan,</div>
                    <div style=" padding-top: 10px;">(...................................)</div>
                </td>
                <!-- Conditional Emergency Contact -->
                @if((float)$data['jumlah_pinjam'] >= 1000000)
                <td style="width: 20%; vertical-align: top; text-align: center;">
                    <div class="signature-title">Kontak Darurat,</div>
                    <div style=" padding-top: 10px;">(...................................)</div>
                </td>
                @endif
                <!-- Signature: Petugas -->
                <td style="width: 25%; vertical-align: top; text-align: center;">
                    <div class="signature-title">Petugas,</div>
                    <div style=" padding-top: 10px;">(...................................)</div>
                </td>
            </tr>
        </table>
    </div>

</body>

</html>