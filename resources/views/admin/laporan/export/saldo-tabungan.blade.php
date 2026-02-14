<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Saldo Tabungan</title>
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
    <h1>Laporan Saldo Tabungan</h1>
    <p>Tanggal: {{ $tgl_cutoff }} | Total: Rp {{ number_format($total_saldo ?? 0, 0, ',', '.') }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Nasabah</th>
                <th class="text-right">Setor</th>
                <th class="text-right">Tarik</th>
                <th class="text-right">Saldo</th>
            </tr>
        </thead>
        <tbody>
            @foreach($per_nasabah ?? [] as $i => $r)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $r->nasabah && $r->nasabah->user ? $r->nasabah->user->nama : '-' }}</td>
                <td class="text-right">Rp {{ number_format($r->total_setor ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($r->total_tarik ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($r->saldo ?? 0, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p style="margin-top: 16px; font-size: 9px;">Koperasi Majakara - {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>
