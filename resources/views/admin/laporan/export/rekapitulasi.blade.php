<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Rekapitulasi</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 12px; }
        h1 { font-size: 16px; color: #674c1d; }
        table { width: 100%; border-collapse: collapse; margin-top: 16px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background: #f5f0e8; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
    </style>
</head>
<body>
    <h1>Laporan Rekapitulasi {{ $tipe === 'harian' ? 'Harian' : 'Bulanan' }}</h1>
    <p>Periode: {{ $dari }} s/d {{ $sampai }}</p>
    <table>
        <tr><th colspan="2">Tabungan</th></tr>
        <tr><td>Total Setoran</td><td class="text-right">Rp {{ number_format($setoran_tabungan ?? 0, 0, ',', '.') }}</td></tr>
        <tr><td>Total Penarikan</td><td class="text-right">Rp {{ number_format($penarikan_tabungan ?? 0, 0, ',', '.') }}</td></tr>
        <tr><td class="font-bold">Net Tabungan</td><td class="text-right font-bold">Rp {{ number_format($net_tabungan ?? 0, 0, ',', '.') }}</td></tr>
    </table>
    <table>
        <tr><th colspan="2">Pinjaman</th></tr>
        <tr><td>Pencairan (periode)</td><td class="text-right">Rp {{ number_format($pencairan_pinjaman ?? 0, 0, ',', '.') }}</td></tr>
        <tr><td>Angsuran Masuk (periode)</td><td class="text-right">Rp {{ number_format($angsuran_masuk ?? 0, 0, ',', '.') }}</td></tr>
        <tr><td class="font-bold">Outstanding</td><td class="text-right font-bold">Rp {{ number_format($outstanding ?? 0, 0, ',', '.') }}</td></tr>
    </table>
    <p style="margin-top: 20px; font-size: 10px; color: #666;">Dicetak dari Koperasi Majakara - {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>
