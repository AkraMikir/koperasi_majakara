<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pengajuan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { font-size: 14px; color: #674c1d; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; max-width: 400px; }
        th, td { border: 1px solid #ddd; padding: 8px; }
        th { background: #f5f0e8; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan Pengajuan</h1>
    <p>Periode: {{ $tgl_dari }} s/d {{ $tgl_sampai }}</p>
    <table>
        <tr><th>Jenis</th><th>Jumlah</th><th class="text-right">Total Nominal</th></tr>
        <tr><td>Setor Tabungan</td><td>{{ $summary['setor']['count'] ?? 0 }}</td><td class="text-right">Rp {{ number_format($summary['setor']['nominal'] ?? 0, 0, ',', '.') }}</td></tr>
        <tr><td>Tarik Tabungan</td><td>{{ $summary['tarik']['count'] ?? 0 }}</td><td class="text-right">Rp {{ number_format($summary['tarik']['nominal'] ?? 0, 0, ',', '.') }}</td></tr>
        <tr><td>Pinjaman</td><td>{{ $summary['pinjaman']['count'] ?? 0 }}</td><td class="text-right">Rp {{ number_format($summary['pinjaman']['nominal'] ?? 0, 0, ',', '.') }}</td></tr>
        <tr><td>Pembayaran Pinjaman</td><td>{{ $summary['pembayaran']['count'] ?? 0 }}</td><td class="text-right">Rp {{ number_format($summary['pembayaran']['nominal'] ?? 0, 0, ',', '.') }}</td></tr>
    </table>
    <p style="margin-top: 20px; font-size: 9px; color: #666;">Koperasi Majakara - {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>
