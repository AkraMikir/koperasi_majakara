<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Pinjaman Aktif</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; }
        h1 { font-size: 14px; color: #674c1d; }
        table { width: 100%; border-collapse: collapse; margin-top: 12px; }
        th, td { border: 1px solid #ddd; padding: 5px; }
        th { background: #f5f0e8; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h1>Laporan Pinjaman Aktif</h1>
    <p>Sisa Piutang: Rp {{ number_format($total_outstanding ?? 0, 0, ',', '.') }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>ID</th>
                <th>Nasabah</th>
                <th>Tgl</th>
                <th class="text-right">Nominal</th>
                <th class="text-right">Terbayar</th>
                <th class="text-right">Sisa</th>
                <th>Sisa Angsur</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows ?? [] as $i => $r)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $r->pinjaman->id }}</td>
                <td>{{ $r->pinjaman->nasabah && $r->pinjaman->nasabah->user ? $r->pinjaman->nasabah->user->nama : '-' }}</td>
                <td>{{ $r->pinjaman->tgl_pinjam ? $r->pinjaman->tgl_pinjam->format('d/m/Y') : '-' }}</td>
                <td class="text-right">Rp {{ number_format($r->pinjaman->jumlah_pinjam, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($r->total_terbayar, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($r->sisa_pokok, 0, ',', '.') }}</td>
                <td>{{ $r->sisa_angsuran }}x</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    <p style="margin-top: 16px; font-size: 9px;">Koperasi Majakara - {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>
