<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Angsuran Pinjaman</title>
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
    <h1>Laporan Angsuran Pinjaman</h1>
    <p>Periode: {{ $tgl_dari }} s/d {{ $tgl_sampai }}</p>
    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>Tgl Bayar</th>
                <th>Pinjaman</th>
                <th>Nasabah</th>
                <th class="text-right">Pokok</th>
                <th class="text-right">Denda</th>
                <th class="text-right">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($rows ?? [] as $i => $r)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $r->tgl_bayar ? $r->tgl_bayar->format('d/m/Y') : '-' }}</td>
                <td>{{ $r->pinjaman ? $r->pinjaman->id : '-' }}</td>
                <td>{{ $r->pinjaman && $r->pinjaman->nasabah && $r->pinjaman->nasabah->user ? $r->pinjaman->nasabah->user->nama : '-' }}</td>
                <td class="text-right">Rp {{ number_format($r->pokok ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($r->denda ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($r->total ?? 0, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
        @if(isset($rows) && $rows->isNotEmpty())
        <tfoot>
            <tr>
                <td colspan="4"><strong>TOTAL</strong></td>
                <td class="text-right">Rp {{ number_format($total_pokok ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($total_denda ?? 0, 0, ',', '.') }}</td>
                <td class="text-right">Rp {{ number_format($total_jumlah ?? 0, 0, ',', '.') }}</td>
            </tr>
        </tfoot>
        @endif
    </table>
    <p style="margin-top: 16px; font-size: 9px; color: #666;">Koperasi Majakara - {{ now()->format('d/m/Y H:i') }}</p>
</body>
</html>
