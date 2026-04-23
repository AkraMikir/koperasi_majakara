<!DOCTYPE html>
<html>
<head>
    <title>Laporan Deposito</title>
    <style>
        body { font-family: sans-serif; font-size: 12px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f4f4f4; }
        h2 { text-align: center; margin-bottom: 5px; }
        .text-right { text-align: right; }
    </style>
</head>
<body>
    <h2>LAPORAN DAFTAR DEPOSITO</h2>
    <p style="text-align: center;">Koperasi Majakara</p>

    <table>
        <thead>
            <tr>
                <th>No</th>
                <th>No. Deposito</th>
                <th>Nasabah</th>
                <th>Nominal Awal</th>
                <th>Tenor</th>
                <th>Suku Bunga</th>
                <th>Jatuh Tempo</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @foreach($depositos as $index => $d)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $d->nomor_deposito }}</td>
                <td>{{ $d->nasabah->user->nama ?? '-' }}</td>
                <td class="text-right">Rp {{ number_format($d->nominal_awal, 0, ',', '.') }}</td>
                <td>{{ $d->tenor->tenor_bulan ?? '-' }} bln</td>
                <td>{{ number_format($d->bunga * 100, 2) }}%</td>
                <td>{{ $d->tgl_jatuh_tempo ? $d->tgl_jatuh_tempo->format('d M Y') : '-' }}</td>
                <td>{{ ucfirst($d->status) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
</body>
</html>
