<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pencairan Pinjaman</title>
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
    <div class="center mb">STRUK PENCAIRAN PINJAMAN</div>
    <hr>
    <div class="mt">ID Pinjaman : {{ $pinjaman->id }}</div>
    @php
        $tglCair = $pinjaman->pengajuan->tgl_cair ?? $pinjaman->tgl_pinjam ?? null;
    @endphp
    <div>Tanggal Pencairan : {{ $tglCair ? $tglCair->format('d-m-Y H:i') : '-' }}</div>
    <div class="mt">Nama : {{ $pinjaman->nasabah->user->nama ?? 'N/A' }}</div>
    <div class="mt bold">Nominal Pinjaman : Rp {{ number_format($pinjaman->jumlah_pinjam ?? 0, 0, ',', '.') }}</div>
    <div>Tenor : {{ $pinjaman->lama_pinjam ?? $pinjaman->pengajuan->durasi ?? '-' }} bulan</div>
    <div>Angsuran per bulan : Rp {{ number_format($pinjaman->ags_bulan ?? 0, 0, ',', '.') }}</div>
    <div class="mt">Metode : {{ $pinjaman->pengajuan->jenis_pencairan ? ucfirst(str_replace('_', ' ', $pinjaman->pengajuan->jenis_pencairan)) : '-' }}</div>
    <hr>
    <div class="center mt">Dicetak : {{ now()->format('d-m-Y H:i') }}</div>
    <div class="center">Dicetak dari {{ config('app.name', 'Koperasi Majakara') }}</div>
</body>
</html>
