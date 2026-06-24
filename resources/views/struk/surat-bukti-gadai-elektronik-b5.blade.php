<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Surat Bukti Gadai Elektronik - {{ $data['slot_kode'] }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        @page {
            size: 9.5in 5.5in;
        }
        body {
            font-family: 'Arial', sans-serif;
            font-size: 12px;
            line-height: 1.25;
            color: #000;
            background-color: #fff;
        }
        .page {
            page-break-after: always;
            position: relative;
            height: 100%;
        }
        .page:last-child {
            page-break-after: avoid;
        }
        .header-table {
            width: 100%;
            margin-bottom: 5px;
        }
        .header-table td {
            vertical-align: middle;
        }
        .logo-img {
            max-height: 90px;
            width: auto;
            display: inline-block;
            vertical-align: middle;
        }
        .koperasi-info {
            font-size: 12px;
            line-height: 1.35;
            padding-left: 10px !important;
        }
        .nasabah-info {
            font-size: 12px;
            text-align: right;
            line-height: 1.35;
        }
        .banner {
            background-color: #2c4a24;
            color: #fff;
            text-align: center;
            font-weight: bold;
            font-size: 13.5px;
            padding: 4px 10px;
            letter-spacing: 0.5px;
            margin-bottom: 8px;
            text-transform: uppercase;
        }
        .detail-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 10px;
        }
        .detail-table td {
            padding: 8px 6px;
            vertical-align: middle;
            font-size: 12px;
            border-bottom: 1px solid #f3f4f6;
        }
        .detail-table td.label {
            width: 18%;
            color: #333;
        }
        .detail-table td.value {
            width: 32%;
            font-weight: bold;
        }
        .perjanjian-title {
            text-align: center;
            font-weight: bold;
            font-size: 10px;
            margin: 5px 0;
            letter-spacing: 0.5px;
            border-top: 1px dashed #000;
            border-bottom: 1px dashed #000;
            padding: 3px 0;
        }
        .perjanjian-text {
            font-size: 10px;
            line-height: 1.3;
            text-align: justify;
            margin-bottom: 6px;
        }
        .perjanjian-list {
            margin-left: 12px;
            font-size: 10px;
            line-height: 1.3;
            margin-bottom: 8px;
            list-style-type: decimal;
        }
        .perjanjian-list li {
            margin-bottom: 2px;
        }
        .footer-table {
            width: 100%;
            margin-top: 5px;
        }
        .footer-table td {
            vertical-align: top;
        }
        .info-box {
            border: 1.5px solid #2c4a24;
            padding: 5px;
            border-radius: 4px;
            font-size: 10px;
            line-height: 1.35;
            color: #2c4a24;
            font-weight: bold;
            width: 250px;
        }
        .signature-area {
            text-align: center;
            font-size: 11px;
        }
        .signature-space {
            height: 38px;
        }
    </style>
</head>
<body>

    <!-- HALAMAN 1: SURAT BUKTI GADAI ELEKTRONIK -->
    <div class="page">
        <table class="header-table">
            <tr>
                <td style="width: 10%; text-align: right; padding-right: 10px;">
                    @if(file_exists(public_path('images/logo/674c1d MAJAKARA.png')))
                    <img src="{{ public_path('images/logo/674c1d MAJAKARA.png') }}" class="logo-img" alt="Logo">
                    @else
                    <div style="font-weight: bold; font-size: 18px; color: #935a16;">MAJAKARA</div>
                    @endif
                </td>
                <td class="koperasi-info" style="width: 30%; font-size: 10px; line-height: 1.35; padding-left: 15px;">
                    <strong>Kantor Perwakilan</strong> : {{ $settings->alamat_koperasi }} |<br>
                    <strong>Nomor Tlp Kantor</strong> : {{ $settings->no_telp }} | <strong>Email</strong>: {{ $settings->email }}
                </td>
                <td class="nasabah-info" style="width: 30%;">
                    Nama: {{ $data['nama_anggota'] }}<br>
                    Alamat: {{ $data['alamat_nasabah'] ?? '-' }}<br>
                    Negara: ID<br>
                    Kode Pos: {{ $data['kode_pos_nasabah'] ?? '-' }}
                </td>
            </tr>
        </table>

        <div class="banner">
            SURAT BUKTI GADAI BARANG ELEKTRONIK
        </div>

        <table style="width: 100%; border-collapse: collapse; border: none; margin-bottom: 10px; table-layout: fixed;">
            <tr>
                <!-- Column 1 (Left) -->
                <td style="width: 31%; vertical-align: top; padding-left: 20px; border: none;">
                    <table class="detail-table" style="width: 100%;">
                        <tr>
                            <td class="label" style="width: 45%;">Nama Peminjam</td>
                            <td class="value" style="width: 55%;">: {{ $data['nama_anggota'] }}</td>
                        </tr>
                        <tr>
                            <td class="label">No HP</td>
                            <td class="value">: {{ $data['nomor_hp'] }}</td>
                        </tr>
                        <tr>
                            <td class="label">Alamat</td>
                            <td class="value">: {{ $data['alamat_nasabah'] }}</td>
                        </tr>
                        <tr>
                            <td class="label">No KTP</td>
                            <td class="value">: {{ $data['nik'] }}</td>
                        </tr>
                        <tr>
                            <td class="label">Tanggal Kredit</td>
                            <td class="value">: {{ $data['tgl_mulai'] }}</td>
                        </tr>
                        <tr>
                            <td class="label">Nilai Pinjaman</td>
                            <td class="value">: Rp {{ number_format($data['nominal_deal'], 0, ',', '.') }}</td>
                        </tr>
                    </table>
                </td>
                <!-- Gap 1 -->
                <td style="width: 3.5%; border: none;"></td>
                <!-- Column 2 (Middle) -->
                <td style="width: 31%; vertical-align: top; border: none;">
                    <table class="detail-table" style="width: 100%;">
                        <tr>
                            <td class="label" style="width: 45%;">Biaya Inap</td>
                            <td class="value" style="width: 55%;">: Rp {{ number_format($data['biaya_inap'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Petugas</td>
                            <td class="value">: {{ $data['admin_nama'] }}</td>
                        </tr>
                        <tr>
                            <td class="label">Catatan</td>
                            <td class="value">: {{ $data['catatan'] }}</td>
                        </tr>
                        <tr>
                            <td class="label">Merk/Type</td>
                            <td class="value">: {{ $data['merk_type'] }}</td>
                        </tr>
                        <tr>
                            <td class="label">No IMEI/SN</td>
                            <td class="value">: {{ $data['no_imei_sn'] }}</td>
                        </tr>
                        <tr>
                            <td class="label">Kelengkapan</td>
                            <td class="value">: {{ $data['kelengkapan'] }}</td>
                        </tr>
                    </table>
                </td>
                <!-- Gap 2 -->
                <td style="width: 3.5%; border: none;"></td>
                <!-- Column 3 (Right) -->
                <td style="width: 31%; vertical-align: top; padding-right: 20px; border: none;">
                    <table class="detail-table" style="width: 100%;">
                        <tr>
                            <td class="label" style="width: 45%;">Status</td>
                            <td class="value" style="width: 55%;">: {{ $data['status'] }}</td>
                        </tr>
                        <tr>
                            <td class="label">Jatuh Tempo</td>
                            <td class="value">: {{ $data['jatuh_tempo'] }}</td>
                        </tr>
                        <tr>
                            <td class="label">Bunga</td>
                            <td class="value">: Rp {{ number_format($data['biaya_jasa'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Total Tebus</td>
                            <td class="value">: Rp {{ number_format($data['total_tagihan'], 0, ',', '.') }}</td>
                        </tr>
                        <tr>
                            <td class="label">Kode Transaksi</td>
                            <td class="value">: {{ $data['slot_kode'] }}</td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>

        <!-- Signatures at the bottom of Page 1 -->
        <table style="width: 100%; margin-top: 15px; table-layout: fixed; border-collapse: collapse; border: none;">
            <tr>
                <!-- Signature: Nasabah -->
                <td style="width: 31%; text-align: left; font-weight: bold; font-size: 9.5px; padding-left: 20px; border: none; vertical-align: top;">
                    Tanda Tangan Nasabah
                    <div style="height: 100px;"></div>
                    <span>( .................................................. )</span>
                </td>
                <!-- Gap 1 -->
                <td style="width: 3.5%; border: none;"></td>
                <!-- Middle Column -->
                <td style="width: 31%; border: none;"></td>
                <!-- Gap 2 -->
                <td style="width: 3.5%; border: none;"></td>
                <!-- Signature: Petugas -->
                <td style="width: 31%; text-align: left; font-weight: bold; font-size: 9.5px; border: none; vertical-align: top;">
                    Tanda Tangan Petugas
                    <div style="height: 100px;"></div>
                    <span>( .................................................. )</span>
                </td>
            </tr>
        </table>
    </div>

    <!-- HALAMAN 2: PERJANJIAN PINJAMAN DENGAN JAMINAN BARANG BERGERAK (ELEKTRONIK) -->
    <div class="page">
        <table class="header-table">
            <tr>
                <td style="width: 10%; text-align: right; padding-right: 10px;">
                    @if(file_exists(public_path('images/logo/674c1d MAJAKARA.png')))
                    <img src="{{ public_path('images/logo/674c1d MAJAKARA.png') }}" class="logo-img" alt="Logo">
                    @else
                    <div style="font-weight: bold; font-size: 18px; color: #935a16;">MAJAKARA</div>
                    @endif
                </td>
                <td class="koperasi-info" style="width: 60%; font-size: 10px; line-height: 1.35; padding-left: 15px;">
                    <strong>Kantor Perwakilan</strong> : {{ $settings->alamat_koperasi }} | <strong>Nomor Tlp Kantor</strong> : {{ $settings->no_telp }} | <strong>Email</strong>: {{ $settings->email }}
                </td>
                <td class="nasabah-info" style="width: 30%;">
                    Nama: {{ $data['nama_anggota'] }}<br>
                    Alamat: {{ $data['alamat_nasabah'] ?? '-' }}<br>
                    Negara: ID<br>
                    Kode Pos: {{ $data['kode_pos_nasabah'] ?? '-' }}
                </td>
            </tr>
        </table>

        <div class="perjanjian-title">
            PERJANJIAN PINJAMAN DENGAN JAMINAN BARANG BERGERAK
        </div>

        <div class="perjanjian-text">
            Yang bertanda tangan dibawah ini:<br>
            Bekasi, ......................... Petugas bagian kredit bertindak untuk dan atas nama <strong>MAJAKARA</strong> dengan nasabah membuat perjanjian sebagai berikut :
        </div>

        <ol class="perjanjian-list">
            <li>Barang yang dijaminkan adalah <strong>milik pribadi dan bukan hasil tindak kejahatan</strong>, jika dikemudian hari saya terbukti bersalah maka saya akan mempertanggung jawabkan sendiri.</li>
            <li>Untuk menebus barang gadai, nasabah harus datang sendiri atau dengan mengalihkan hak kepada orang lain dengan melampirkan Surat Kuasa Asli dan KTP peminjam dan KTP penerima kuasa.</li>
            <li>Nasabah wajib menyimpan <strong>Surat Bukti Gadai MAJAKARA</strong> sebagai syarat penebusan.</li>
            <li>Saya bersedia dan tidak ada tuntutan dalam bentuk apapun, baik secara PIDANA/PERDATA kepada Pihak MAJAKARA, jika saya <strong>LALAI/tidak melakukan pembayaran bunga</strong> sampai tanggal jatuh tempo dan <strong>BARANG ELEKTRONIK YANG SAYA GADAI AKAN HANGUS/DIJUAL</strong> oleh pihak <strong>MAJAKARA</strong>.</li>
            <li>Bunga dan biaya administrasi mengikuti ketentuan yang berlaku.</li>
            <li>Saya bersedia membayar denda keterlambatan 1 s/d 15 hari sebesar 5%/hari dan juga bunga berjalan sebesar 5% dari jumlah pinjaman. Dan <strong>hari ke 16 barang elektronik yang saya gadaikan dianggap HANGUS</strong>.</li>
            <li>Pihak Majakara Gadai berhak menolak barang yang tidak memenuhi syarat.</li>
            <li>Segala bentuk wanprestasi akan diselesaikan sesuai hukum yang berlaku.</li>
        </ol>

        <div style="font-size: 8px; font-style: italic; margin-bottom: 12px; color: #444;">
            Dasar hukum: Pasal 1150 KUHPerdata tentang gadai, Pasal 1155 KUHPerdata tentang hak menjual barang gadai, dan Pasal 1238 KUHPerdata tentang wanprestasi.
        </div>

        <!-- Footer containing Jam Operasional & Signatures -->
        <table class="footer-table">
            <tr>
                <td style="width: 45%;">
                    <div class="info-box">
                        PINJAMAN BISA DIANGSUR HARI BESAR DAN HARI MINGGU TETAP BUKA.<br>
                        Jam Operasional : Senin - Minggu<br>
                        Buka Jam: 08.00 - 20.00<br>
                        Jam Pengambilan Barang: 08.00 - 18.00
                    </div>
                </td>
                <td style="width: 55%;">
                    <div style="text-align: right; font-size: 9px; margin-bottom: 5px; font-weight: bold; padding-right: 20px;">
                        Bekasi, ....................................................
                    </div>
                    <table style="width: 100%; font-size: 8.5px; font-weight: bold;">
                        <tr>
                            <td class="signature-area" style="width: 33%;">
                                Nasabah/yang dikuasakan
                                <div class="signature-space"></div>
                                ( ................................. )
                            </td>
                            <td class="signature-area" style="width: 33%;">
                                Yang Memberi Kuasa
                                <div class="signature-space"></div>
                                ( ................................. )
                            </td>
                            <td class="signature-area" style="width: 34%;">
                                Petugas
                                <div class="signature-space"></div>
                                ( ................................. )
                            </td>
                        </tr>
                    </table>
                </td>
            </tr>
        </table>
    </div>

</body>
</html>
