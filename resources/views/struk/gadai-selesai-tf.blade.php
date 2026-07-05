<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <title>Struk Pelunasan Transfer - {{ $gadai->slot_kode }}</title>
    <style>
        @page {
            margin: 0px;
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Courier', monospace;
            font-weight: bold;
            font-size: 11px;
            line-height: 1.4;
            color: #000;
            padding: 4px;
        }

        .center {
            text-align: center;
        }

        .bold {
            font-weight: bold;
        }

        .underline {
            text-decoration: underline;
        }

        .dashed {
            border-top: 1px dashed #000;
            margin: 9px 0;
        }

        .table-row {
            width: 100%;
            margin-bottom: 3px;
        }

        .table-row td {
            vertical-align: top;
        }

        .label {
            font-weight: bold;
            width: 35%;
            font-size: 11px;
            white-space: nowrap;
        }

        .header {
            margin-bottom: 12px;
        }

        .footer {
            margin-top: 12px;
            font-size: 10px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        td {
            padding: 1px 0;
        }

        .text-right {
            text-align: right;
        }

        .signature-box {
            width: 100%;
            margin-top: 20px;
        }

        .signature-box td {
            text-align: center;
            font-size: 10px;
            height: 55px;
            vertical-align: bottom;
        }

        .syarat {
            font-size: 10px;
            margin-top: 6px;
            text-align: justify;
            line-height: 1.2;
            white-space: pre-line;
        }

        .nominal-table td {
            padding: 2px 0;
        }

        .slot-title {
            font-size: 32px;
            font-weight: bold;
            margin: 10px 0;
            text-align: center;
        }

        .approver {
            margin-top: 10px;
            font-size: 10px;
        }
    </style>
</head>

<body>
    <div class="header" style="text-align: center;">
        <div style="margin-bottom: 4px;">
            <img src="{{ public_path('images/logo/674c1d MAJAKARA.png') }}" alt="Logo" style="max-width: 130px; max-height: 65px;" />
        </div>
        <div class="center bold underline" style="font-size: 14px; margin-bottom: 2px;">
            {{ $settings->nama_koperasi }}
        </div>
        <div class="center" style="font-size: 10px;">
            {{ $settings->alamat_koperasi }}<br>
            Telp: {{ $settings->no_telp }}
        </div>
    </div>

    <div class="center bold" style="margin-bottom: 9px; font-size: 13px;">
        STRUK PELUNASAN TRANSFER
    </div>

    <table style="font-size: 10px; margin-bottom: 4px; width: 100%;">
        <tr>
            <td>No. Struk: {{ $no_struk }}</td>
            <td style="text-align: right;">Tanggal: {{ $tanggal_cetak }}</td>
        </tr>
    </table>

    <div class="dashed"></div>

    <table class="table-row">
        <tr>
            <td class="label">Nama</td>
            <td>: {{ $gadai->nasabah->user->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Anggota</td>
            <td>: {{ $gadai->nasabah->id ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Slot</td>
            <td>: <span class="bold">{{ $gadai->slot_kode }}</span></td>
        </tr>
        <tr>
            <td class="label">Barang</td>
            <td>: {{ $gadai->item->head_1 ?? '-' }}</td>
        </tr>
    </table>

    <div class="dashed"></div>

    <div class="center bold" style="margin-bottom: 2px;">
        DETAIL PEMBAYARAN TRANSFER
    </div>

    <table>
        <tr>
            <td class="label">Nominal</td>
            <td class="text-right">: Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Metode</td>
            <td class="text-right">: {{ strtoupper($pengajuan->metode) }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal</td>
            <td class="text-right">: {{ \Carbon\Carbon::parse($pengajuan->processed_at)->format('d/m/Y H:i') }}</td>
        </tr>
        <tr>
            <td class="label">Batas</td>
            <td class="text-right">: {{ \Carbon\Carbon::parse($gadai->tgl_ambil_limit)->format('d/m/Y') }}</td>
        </tr>
    </table>

    <div class="dashed"></div>

    <div style="font-size: 9.5px; text-align: justify; margin-top: 5px; line-height: 1.2;">
        * Pembayaran transfer telah diverifikasi dan disetujui. Harap membawa lembar struk pelunasan ini dan kartu identitas untuk pengambilan barang jaminan fisik sebelum batas waktu pengambilan di atas.
    </div>

    <div class="dashed"></div>

    <div class="footer center">
        <div>{{ $settings->nama_pt }}</div>
        <div class="bold" style="margin-top: 4px;">Terima Kasih</div>
    </div>
</body>

</html>