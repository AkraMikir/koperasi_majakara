<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Angsuran</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; margin: 8px; line-height: 1.3; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .mt { margin-top: 6px; }
        .mb { margin-bottom: 4px; }
        hr { border: none; border-top: 1px solid #333; margin: 6px 0; }
    </style>
</head>
<body>
    <div class="center bold mb">{{ config('app.name', 'Koperasi Majakara') }}</div>
    <div class="center mb">STRUK BUKTI BAYAR ANGSURAN</div>
    <hr>
    @php
        $pinjaman = $angsuran->pinjaman ?? null;
        $nasabah = $pinjaman->nasabah ?? null;
    @endphp
    <div class="mt">ID Pinjaman : {{ $pinjaman->id ?? '-' }}</div>
    <div>Angsuran ke : {{ $angsuran->no_urut ?? '-' }}</div>
    <div class="mt">Nama : {{ $nasabah->user->nama ?? 'N/A' }}</div>
    <div class="mt bold">Nominal : Rp {{ number_format($angsuran->jumlah_terbayar ?? $angsuran->jumlah_tagihan ?? 0, 0, ',', '.') }}</div>
    <div>Tanggal Bayar : {{ isset($angsuran->tgl_bayar) ? $angsuran->tgl_bayar->format('d-m-Y H:i') : '-' }}</div>
    <div class="mt">Status : Lunas angsuran ke-{{ $angsuran->no_urut ?? '-' }}</div>
    <hr>
    <div class="center mt">Dicetak : {{ now()->format('d-m-Y H:i') }}</div>
    <div class="center">Dicetak dari {{ config('app.name', 'Koperasi Majakara') }}</div>
</body>
</html>
