<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Tabungan</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 11px; }
        h1 { font-size: 14px; color: #674c1d; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 6px; }
        th { background: #f5f0e8; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan Tabungan</h1>
    <p>Periode: {{ $tgl_dari }} s/d {{ $tgl_sampai }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tanggal</th>
                <th>Nasabah</th>
                <th>Jenis</th>
                <th class="text-right">Nominal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($transaksi ?? [] as $i => $t)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $t->tgl_transaksi ? $t->tgl_transaksi->format('d/m/Y H:i') : '-' }}</td>
                <td>{{ $t->nasabah && $t->nasabah->user ? $t->nasabah->user->nama : '-' }}</td>
                <td>{{ $t->jnsTransaksi ? $t->jnsTransaksi->nama : '-' }}</td>
                <td class="text-right">Rp {{ number_format($t->nominal, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        @if(isset($transaksi) && $transaksi->isNotEmpty())
        <tfoot>
            <tr><td colspan="4">Total Setor</td><td class="text-right">Rp {{ number_format($total_setor ?? 0, 0, ',', '.') }}</td></tr>
            <tr><td colspan="4">Total Tarik</td><td class="text-right">Rp {{ number_format($total_tarik ?? 0, 0, ',', '.') }}</td></tr>
            <tr><td colspan="4"><strong>Net</strong></td><td class="text-right"><strong>Rp {{ number_format($net ?? 0, 0, ',', '.') }}</strong></td></tr>
        </tfoot>
        @endif
    </table>
    <p style="margin-top: 16px; font-size: 9px;">Koperasi Majakara - {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>
