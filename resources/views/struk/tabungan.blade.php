<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Transaksi Tabungan - Koperasi Majakara</title>
        <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier', monospace; font-size: 12px; line-height: 1.5; color: #000; padding: 4px; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .dashed { border-top: 1px dashed #000; margin: 9px 0; }
        .table-row { width: 100%; margin-bottom: 3px; }
        .table-row td { vertical-align: top; font-size:8px }
        .label { font-weight: normal; width: 45%; font-size:11px }
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
        .id-kecil{font-size:8px}
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
        STRUK TRANSAKSI TABUNGAN
    </div>
    
    <div class="center bold" style="margin-bottom: 4px; font-size: 9px; text-transform: uppercase;">
        {{ $transaksi->jenis === 'setoran' ? 'SETORAN' : 'PENARIKAN' }}
    </div>

    <div class="dashed"></div>
    
    <table class="table-row">
        <tr>
            <td class="label">ID Transaksi</td>
            <td class="id-kecil">: {{ $transaksi->id_transaksi ?? str_pad($transaksi->id ?? '', 5, '0', STR_PAD_LEFT) }}</td>
        </tr>
        <tr>
            <td class="label">Tanggal & Waktu</td>
            <td class="id-kecil">: {{ $transaksi->tgl_transaksi ? $transaksi->tgl_transaksi->format('d-m-Y H:i') : '-' }}</td>
        </tr>
        <tr>
            <td class="label">Nama Anggota</td>
            <td class="id-kecil">: {{ $transaksi->nasabah->user->nama ?? 'N/A' }}</td>
        </tr>
        <tr>
            <td class="label">NIK</td>
            <td class="id-kecil">: {{ $transaksi->nasabah->dataKtp->nik ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Jenis Transaksi</td>
            <td class="id-kecil">: {{ ucfirst($transaksi->jenis) }}</td>
        </tr>
        <tr>
            <td class="label">Via</td>
            <td class="id-kecil">: {{ ucfirst($transaksi->via ?? '-') }}</td>
        </tr>
    </table>
    
    <div class="dashed"></div>
    
    <!-- DETAIL KEUANGAN -->
    <table class="table-row">
        @if($transaksi->jenis === 'penarikan' && $transaksi->pengajuanTarik && (float)($transaksi->pengajuanTarik->biaya_transfer ?? 0) > 0)
        <tr>
            <td class="label">Nominal Penarikan</td>
            <td class="text-right">: Rp {{ number_format($transaksi->pengajuanTarik->nominal ?? 0, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Biaya Transfer</td>
            <td class="text-right">: Rp {{ number_format($transaksi->pengajuanTarik->biaya_transfer ?? 0, 0, ',', '.') }}</td>
        </tr>
        <div class="dashed"></div>
        <tr>
            <td class="bold">TOTAL DIDEBET</td>
            <td class="bold text-right">: Rp {{ number_format($transaksi->nominal ?? 0, 0, ',', '.') }}</td>
        </tr>
        @else
        <tr>
            <td class="bold">NOMINAL</td>
            <td class="bold text-right">: Rp {{ number_format($transaksi->nominal ?? 0, 0, ',', '.') }}</td>
        </tr>
        @endif
    </table>

    @if(!empty($transaksi->keterangan))
    <div class="dashed"></div>
    <table class="table-row">
        <tr>
            <td class="label">Keterangan</td>
            <td class="text-right">: {{ $transaksi->keterangan }}</td>
        </tr>
    </table>
    @endif

    @php
        $pengajuanSetor = $transaksi->pengajuanSetor ?? null;
        $approver = $pengajuanSetor && $pengajuanSetor->relationLoaded('approvedBy') ? $pengajuanSetor->approvedBy : null;
        $roleLabel = $approver ? (($approver->role === 'admin_utama' ? 'Admin Utama' : ($approver->role === 'admin_operasional' ? 'Admin Operasional' : 'Admin'))) : null;
    @endphp
    @if($approver && $roleLabel)
    <div class="dashed"></div>
    <div class="approver center">
        <span class="bold">Disetujui oleh:</span><br />
        <span>{{ $roleLabel }} – {{ $approver->nama ?? 'N/A' }}</span>
    </div>
    @endif

    <div class="dashed"></div>
    
    <!-- FOOTER -->
    <div class="footer center">
        <div>Dicetak : {{ now()->format('d-m-Y H:i') }}</div>
        <div class="bold" style="margin-top: 4px;">Dicetak dari {{ $strukSettings->nama_pt }}</div>
    </div>
</body>
</html>
