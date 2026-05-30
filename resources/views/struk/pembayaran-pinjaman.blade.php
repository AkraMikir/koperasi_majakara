<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran Angsuran</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; margin: 8px; line-height: 1.3; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .mt { margin-top: 6px; }
        .mb { margin-bottom: 4px; }
        hr { border: none; border-top: 1px solid #333; margin: 6px 0; }
    </style>
</head>
@php
    $strukSettings = \App\Models\SettingsStruk::getSettings();
@endphp
<body>
    <div class="center bold mb">{{ $strukSettings->nama_koperasi }}</div>
    <div class="center text-[8px] leading-tight" style="font-size: 8px; color: #555;">{{ $strukSettings->alamat_koperasi }}</div>
    <div class="center text-[8px]" style="font-size: 8px; color: #555; margin-bottom: 4px;">Telp: {{ $strukSettings->no_telp }}</div>
    <div class="center mb bold">STRUK PEMBAYARAN ANGSURAN</div>
    <hr>
    <div class="mt">ID Pengajuan : {{ $pengajuan->id }}</div>
    <div>Tanggal Pembayaran : {{ $pengajuan->tgl_pembayaran ? $pengajuan->tgl_pembayaran->format('d-m-Y H:i') : '-' }}</div>
    <div class="mt">Nama : {{ $pengajuan->nasabah->user->nama ?? 'N/A' }}</div>
    <div>ID Pinjaman : {{ $pengajuan->pinjaman->id ?? '-' }}</div>
    @if($angsuran)
    <div>Angsuran ke : {{ $angsuran->no_urut ?? '-' }}</div>
    @endif
    <div class="mt bold">Nominal : Rp {{ number_format($pengajuan->nominal ?? 0, 0, ',', '.') }}</div>
    <div>Metode : {{ $pengajuan->metode_pembayaran ? ucfirst(str_replace('_', ' ', $pengajuan->metode_pembayaran)) : '-' }}</div>
    <div class="mt">Status : Lunas angsuran{{ $angsuran ? ' ke-' . $angsuran->no_urut : '' }}</div>
    <hr>
    <div class="center mt">Dicetak : {{ now()->format('d-m-Y H:i') }}</div>
    <div class="center">Dicetak dari {{ $strukSettings->nama_pt }}</div>
</body>
</html>
