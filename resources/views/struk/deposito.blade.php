<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Deposito - {{ $deposito->nomor_deposito }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier', monospace;
            font-size: 10px;
            line-height: 1.3;
            color: #000;
            padding: 8px;
        }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .dashed { border-top: 1px dashed #000; margin: 6px 0; }
        .table-row {
            width: 100%;
            margin-bottom: 3px;
        }
        .table-row td {
            vertical-align: top;
        }
        .label { font-weight: bold; width: 45%; }
        .value { text-align: left; }
        .header { margin-bottom: 8px; }
        .footer { margin-top: 8px; font-size: 8px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 1px 0; }
        .nominal-table td {
            padding: 2px 0;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <div class="center bold underline" style="font-size: 12px; margin-bottom: 2px;">
            {{ $settings->nama_koperasi }}
        </div>
        <div class="center" style="font-size: 8px;">
            {{ $settings->alamat_koperasi }}<br>
            Telp: {{ $settings->no_telp }}
        </div>
    </div>
    
    <div class="center bold" style="margin-bottom: 6px; font-size: 11px;">
        STRUK BUKTI DEPOSITO
    </div>
    
    <table style="font-size: 8px; margin-bottom: 4px; width: 100%;">
        <tr>
            <td>No. Struk: {{ $no_struk }}</td>
            <td style="text-align: right;">Tanggal: {{ $tanggal_cetak }}</td>
        </tr>
    </table>
    
    <div class="dashed"></div>
    
    <!-- INFO NASABAH -->
    <table class="table-row">
        <tr>
            <td class="label">Nama Anggota</td>
            <td>: {{ $deposito->nasabah->user->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">No. Anggota</td>
            <td>: {{ $deposito->nasabah->id ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nomor Deposito</td>
            <td>: <span class="bold">{{ $deposito->nomor_deposito }}</span></td>
        </tr>
        <tr>
            <td class="label">Tenor</td>
            <td>: {{ $deposito->tenor->tenor_bulan ?? '-' }} Bulan</td>
        </tr>
        <tr>
            <td class="label">Suku Bunga</td>
            <td>: {{ number_format($deposito->bunga * 100, 2) }}% p.a.</td>
        </tr>
        <tr>
            <td class="label">Tanggal Mulai</td>
            <td>: {{ \Carbon\Carbon::parse($deposito->tgl_mulai)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="label">Jatuh Tempo</td>
            <td>: {{ \Carbon\Carbon::parse($deposito->tgl_jatuh_tempo)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td>: <span class="bold">{{ strtoupper($deposito->status) }}</span></td>
        </tr>
    </table>
    
    <div class="dashed"></div>
    
    <!-- NOMINAL -->
    <div class="center bold" style="margin-bottom: 2px;">
        NOMINAL
    </div>
    
    <table class="nominal-table">
        <tr>
            <td class="label">Nominal Deposito</td>
            <td class="text-right">: Rp {{ number_format($deposito->nominal_awal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Estimasi Bunga</td>
            <td class="text-right">: Rp {{ number_format($estimasi_bunga, 0, ',', '.') }}</td>
        </tr>
        <div class="dashed"></div>
        <tr>
            <td class="bold">TOTAL PENERIMAAN JT</td>
            <td class="bold text-right">: Rp {{ number_format($nominal_akhir, 0, ',', '.') }}</td>
        </tr>
    </table>
    
    <div class="dashed"></div>
    
    <!-- FOOTER -->
    <div class="footer center">
        @if($settings->email || $settings->website || $settings->nama_pt)
        <div style="margin-bottom: 2px;">
            @if($settings->email)Email: {{ $settings->email }}@endif
            @if($settings->website)<br>Website: {{ $settings->website }}@endif
            @if($settings->nama_pt)<br>{{ $settings->nama_pt }}@endif
        </div>
        @endif
        <div class="bold" style="margin-top: 4px;">Terima Kasih</div>
        <div>Simpan struk ini sebagai bukti kepemilikan Deposito resmi.</div>
    </div>
</body>
</html>
