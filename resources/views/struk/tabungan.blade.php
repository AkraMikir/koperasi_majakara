<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Transaksi Tabungan - Koperasi Majakara</title>
        <style>
        @page { margin: 0px; }
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Courier', monospace; font-size: 12px; line-height: 1.5; color: #000; padding: 4px; }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .dashed { border-top: 1px dashed #000; margin: 9px 0; }
        .table-row td { vertical-align: top; }
        .label { font-weight: normal; width: 45%; font-size: 12px; }
        .id-kecil { font-size: 12px; }
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
    $strukSettings = \App\Models\SettingsStruk::getSettings();
    
    $pengajuanSetor = $transaksi->pengajuanSetor ?? null;
    $approver = $pengajuanSetor && $pengajuanSetor->relationLoaded('approvedBy') ? $pengajuanSetor->approvedBy : null;
    $roleLabel = $approver ? (($approver->role === 'admin_utama' ? 'Admin Utama' : ($approver->role === 'admin_operasional' ? 'Admin Operasional' : 'Admin'))) : null;

    $mappedData = [
        'jenis_trans' => $transaksi->jenis,
        'no_struk' => $transaksi->id_transaksi ?? str_pad($transaksi->id ?? '', 5, '0', STR_PAD_LEFT),
        'tanggal' => ($transaksi->tgl_transaksi ?? $transaksi->created_at ?? now())->format('d-m-Y H:i'),
        'nama_anggota' => $transaksi->nasabah->user->nama ?? 'N/A',
        'via' => ucfirst($transaksi->via ?? '-'),
        'nominal' => $transaksi->nominal ?? 0,
        'nominal_murni' => $transaksi->jenis === 'penarikan' && $transaksi->pengajuanTarik ? ($transaksi->pengajuanTarik->nominal ?? 0) : ($transaksi->nominal ?? 0),
        'biaya_transfer' => $transaksi->jenis === 'penarikan' && $transaksi->pengajuanTarik ? ($transaksi->pengajuanTarik->biaya_transfer ?? 0) : 0,
        'keterangan' => $transaksi->keterangan ?? '',
        'approver_name' => $approver ? ($approver->nama ?? 'N/A') : '',
        'approver_role' => $roleLabel ?? '',
        'tanggal_cetak' => now()->format('d-m-Y H:i')
    ];
@endphp
<body>
    @include('admin.settings.partials.components.tabungan-body', ['settings' => $strukSettings, 'data' => $mappedData, 'isPdf' => true])
</body>
</html>
