<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Deposito - {{ $deposito->nomor_deposito }}</title>
        <style>
        @page { margin: 0px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier', monospace; font-size: 12px; line-height: 1.5; color: #000; padding: 4px; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .dashed { border-top: 1px dashed #000; margin: 9px 0; }
        .table-row { width: 100%; margin-bottom: 3px; }
        .table-row td { vertical-align: top; }
        .label { font-weight: bold; width: 45%; }
        .header { margin-bottom: 12px; }
        .footer { margin-top: 12px; font-size: 10px; }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 1px 0; }
        .text-right { text-align: right; }
        .signature-box { width: 100%; margin-top: 20px; }
        .signature-box td { text-align: center; font-size: 10px; height: 55px; vertical-align: bottom; }
        .syarat { font-size: 10px; margin-top: 6px; text-align: justify; line-height: 1.2; white-space: pre-line; }
        .nominal-table td { padding: 2px 0; }
        .slot-title { font-size: 32px; font-weight: bold; margin: 10px 0; text-align: center; }
        .approver { margin-top: 10px; font-size: 10px; }
    </style>
</head>
@php
    $mappedData = [
        'nama_anggota' => $deposito->nasabah->user->nama ?? '-',
        'no_anggota' => $deposito->nasabah->id ?? '-',
        'no_deposito' => $deposito->nomor_deposito,
        'jangka_waktu' => $deposito->tenor->tenor_bulan ?? '-',
        'bunga' => $deposito->bunga * 100,
        'tanggal' => \Carbon\Carbon::parse($deposito->tgl_mulai)->format('d/m/Y'),
        'jatuh_tempo' => \Carbon\Carbon::parse($deposito->tgl_jatuh_tempo)->format('d/m/Y'),
        'status' => strtoupper($deposito->status),
        'nominal_awal' => $deposito->nominal_awal,
        'bunga_gross' => $estimasi_bunga,
        'nominal_akhir' => $nominal_akhir,
        'tanggal_cetak' => $tanggal_cetak
    ];
@endphp
<body>
    @include('admin.settings.partials.components.deposito-body', ['settings' => $settings, 'data' => $mappedData, 'isPdf' => true])
</body>
</html>
