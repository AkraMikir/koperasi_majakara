<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pembayaran Angsuran - {{ $pengajuan->id ?? '-' }}</title>
        <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier', monospace; font-size: 12px; line-height: 1.5; color: #000; padding: 4px; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .dashed { border-top: 1px dashed #000; margin: 9px 0; }
        .table-row { width: 100%; margin-bottom: 3px; }
        .table-row td { vertical-align: top; }
        .label { font-weight: normal; width: 45%; font-size:12px}
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
        .id-kecil{font-size:12px}
    </style>
</head>
@php
    $strukSettings = \App\Models\SettingsStruk::getSettings();
    
    $mappedData = [
        'jenis_trans' => 'BAYAR_ANGSURAN',
        'no_pinjaman' => $pengajuan->pinjaman->id ?? '-',
        'tanggal' => $pengajuan->tgl_pembayaran ? $pengajuan->tgl_pembayaran->format('d-m-Y H:i') : '-',
        'nama_anggota' => $pengajuan->nasabah->user->nama ?? 'N/A',
        'lama_pinjam' => '',
        'angsuran_pertama' => 0,
        'metode' => $pengajuan->metode_pembayaran ? ucfirst(str_replace('_', ' ', $pengajuan->metode_pembayaran)) : '-',
        'jumlah_pinjam' => 0,
        'angsuran_ke' => $angsuran->no_urut ?? '-',
        'status' => 'Lunas angsuran' . ($angsuran ? ' ke-' . $angsuran->no_urut : ''),
        'nominal' => $pengajuan->nominal ?? 0,
        'tanggal_cetak' => now()->format('d-m-Y H:i')
    ];
@endphp
<body>
    @include('admin.settings.partials.components.pinjaman-body', ['settings' => $strukSettings, 'data' => $mappedData])
</body>
</html>
