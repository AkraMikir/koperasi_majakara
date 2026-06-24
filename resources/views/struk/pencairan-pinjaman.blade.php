<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Pencairan Pinjaman - {{ $pinjaman->id ?? '-' }}</title>
        <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier', monospace; font-size: 14px; line-height: 1.5; color: #000; padding: 4px; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .dashed { border-top: 1px dashed #000; margin: 9px 0; }
        .table-row { width: 100%; margin-bottom: 3px; }
        .table-row td { vertical-align: top; }
        .label {font-size: 12px; width: 25%; font-weight: 100;}
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
    $tglCair = $pinjaman->pengajuan->tgl_cair ?? $pinjaman->tgl_pinjam ?? null;
    
    $mappedData = [
        'jenis_trans' => 'PENCAIRAN',
        'no_pinjaman' => $pinjaman->id,
        'tanggal' => $tglCair ? $tglCair->format('d-m-Y H:i') : '-',
        'nama_anggota' => $pinjaman->nasabah->user->nama ?? 'N/A',
        'lama_pinjam' => $pinjaman->lama_pinjam ?? $pinjaman->pengajuan->durasi ?? '-',
        'angsuran_pertama' => $pinjaman->ags_bulan ?? 0,
        'metode' => $pinjaman->pengajuan->jenis_pencairan ? ucfirst(str_replace('_', ' ', $pinjaman->pengajuan->jenis_pencairan)) : '-',
        'jumlah_pinjam' => $pinjaman->jumlah_pinjam ?? 0,
        'angsuran_ke' => '',
        'status' => '',
        'nominal' => $pinjaman->jumlah_pinjam ?? 0,
        'tanggal_cetak' => now()->format('d-m-Y H:i')
    ];
@endphp
<body>
    @include('admin.settings.partials.components.pinjaman-body', ['settings' => $strukSettings, 'data' => $mappedData])
</body>
</html>
