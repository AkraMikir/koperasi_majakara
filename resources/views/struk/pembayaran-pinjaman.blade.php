<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran Angsuran - {{ $pengajuan->id ?? '-' }}</title>
        <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier', monospace; font-size: 12px; line-height: 1.5; color: #000; padding: 4px; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .dashed { border-top: 1px dashed #000; margin: 9px 0; }
        .table-row { width: 100%; margin-bottom: 3px; }
        .table-row td { vertical-align: top; }
        .label { font-weight: normal; width: 45%; font-size:12px}
        .header { margin-bottom: 12px; }
        .footer { margin-top: 12px; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 1px 0; }
        .text-right { text-align: right; }
        .signature-box { width: 100%; margin-top: 20px; }
        .signature-box td { text-align: center; font-size: 10px; height: 55px; vertical-align: bottom; }
        .syarat { font-size: 10px; margin-top: 6px; text-align: justify; line-height: 1.2; white-space: pre-line; }
        .nominal-table td { padding: 2px 0; }
        .slot-title { font-size: 32px; font-weight: bold; margin: 10px 0; text-align: center; }
        .approver { margin-top: 10px; font-size: 10px; }
        .id-kecil{font-size:12px}
    </style>
</head>
@php
    $strukSettings = \App\Models\SettingsStruk::getSettings();
@endphp
<body>
    <!-- HEADER -->
    <div class="header">
        <div class="center bold underline" style="font-size: 14px; margin-bottom: 2px;">
            {{ $strukSettings->nama_koperasi }}
        </div>
        <div class="center" style="font-size: 10px;">
            {{ $strukSettings->alamat_koperasi }}<br>
            Telp: {{ $strukSettings->no_telp }}
        </div>
    </div>
    
    <div class="center bold" style="margin-bottom: 9px; font-size: 13px;">
        STRUK PEMBAYARAN ANGSURAN
    </div>
    
    <div class="dashed"></div>
    
    <table class="table-row">
        <tr>
            <td class="label">ID Pinjaman</td>
            <td class="id-kecil">: {{ $pengajuan->pinjaman->id ?? '-' }}</td>
        </tr>
        @if($angsuran)
        <tr>
            <td class="label">Angsuran Ke</td>
            <td class="id-kecil">: {{ $angsuran->no_urut ?? '-' }}</td>
        </tr>
        @endif
        <tr>
            <td class="label">Nama</td>
            <td class="id-kecil">: {{ $pengajuan->nasabah->user->nama ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal</td>
            <td class="id-kecil">: {{ $pengajuan->tgl_pembayaran ? $pengajuan->tgl_pembayaran->format('d-m-Y H:i') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Metode</td>
            <td class="id-kecil">: {{ $pengajuan->metode_pembayaran ? ucfirst(str_replace('_', ' ', $pengajuan->metode_pembayaran)) : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td class="id-kecil">: Lunas angsuran{{ $angsuran ? ' ke-' . $angsuran->no_urut : '' }}</td>
        </tr>
    </table>
    
    <div class="dashed"></div>
    
    <table class="table-row">
        <tr>
            <td class="bold">NOMINAL</td>
            <td class="bold text-right">: Rp {{ number_format($pengajuan->nominal ?? 0, 0, ',', '.') }}</td>
        </tr>
    </table>
    
    <div class="dashed"></div>
    
    <!-- FOOTER -->
    <div class="footer center">
        <div>Dicetak : {{ now()->format('d-m-Y H:i') }}</div>
        <div class="bold" style="margin-top: 4px;">Dicetak dari {{ $strukSettings->nama_pt }}</div>
    </div>
</body>
</html>
