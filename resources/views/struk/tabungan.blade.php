<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Struk Transaksi Tabungan - Koperasi Majakara</title>
    <style>
        * { box-sizing: border-box; }
        body { font-family: DejaVu Sans, sans-serif; font-size: 10px; margin: 0; padding: 12px; line-height: 1.4; color: #1a1a1a; }
        .struk { max-width: 72mm; margin: 0 auto; }
        .header { text-align: center; padding-bottom: 8px; border-bottom: 2px solid #1a1a1a; margin-bottom: 10px; }
        .logo { max-height: 36px; max-width: 120px; margin-bottom: 4px; }
        .brand { font-size: 14px; font-weight: bold; letter-spacing: 0.5px; margin: 4px 0 2px; }
        .title { font-size: 11px; font-weight: bold; text-transform: uppercase; letter-spacing: 0.5px; margin: 2px 0; }
        .subtitle { font-size: 10px; color: #333; margin: 2px 0; }
        .section { margin: 8px 0; }
        .row { display: table; width: 100%; margin: 3px 0; }
        .label { display: table-cell; width: 42%; color: #555; }
        .value { display: table-cell; font-weight: 600; }
        .nominal { font-size: 12px; font-weight: bold; margin: 6px 0; }
        .divider { border: none; border-top: 1px dashed #999; margin: 10px 0; }
        .footer { text-align: center; font-size: 9px; color: #666; margin-top: 12px; padding-top: 8px; border-top: 1px solid #ddd; }
        .approver { margin-top: 8px; padding: 6px 8px; background: #f5f5f5; border-radius: 4px; font-size: 9px; }
        .approver-label { color: #555; }
        .approver-value { font-weight: 600; }
    </style>
</head>
@php
    $strukSettings = \App\Models\SettingsStruk::getSettings();
@endphp
<body>
    <div class="struk">
        <div class="header">
            @if(!empty($hasLogo) && !empty($logoPath))
            <img class="logo" src="{{ $logoPath }}" alt="Logo" />
            @endif
            <div class="brand">{{ $strukSettings->nama_koperasi }}</div>
            <div style="font-size: 8px; color: #555; text-align: center; margin-top: 2px;">{{ $strukSettings->alamat_koperasi }}</div>
            <div style="font-size: 8px; color: #555; text-align: center;">Telp: {{ $strukSettings->no_telp }}</div>
            <div class="title" style="margin-top: 6px;">Struk Transaksi Tabungan</div>
            <div class="subtitle">{{ $transaksi->jenis === 'setoran' ? 'SETORAN' : 'PENARIKAN' }}</div>
        </div>

        <div class="section">
            <div class="row"><span class="label">ID Transaksi</span><span class="value">{{ $transaksi->id_transaksi ?? str_pad($transaksi->id ?? '', 5, '0', STR_PAD_LEFT) }}</span></div>
            <div class="row"><span class="label">Tanggal & Waktu</span><span class="value">{{ $transaksi->tgl_transaksi ? $transaksi->tgl_transaksi->format('d-m-Y H:i') : '-' }}</span></div>
            <div class="row"><span class="label">Nama</span><span class="value">{{ $transaksi->nasabah->user->nama ?? 'N/A' }}</span></div>
            <div class="row"><span class="label">NIK</span><span class="value">{{ $transaksi->nasabah->dataKtp->nik ?? '-' }}</span></div>
            <div class="row"><span class="label">Jenis</span><span class="value">{{ ucfirst($transaksi->jenis) }}</span></div>
            <div class="row"><span class="label">Via</span><span class="value">{{ ucfirst($transaksi->via ?? '-') }}</span></div>

            @if($transaksi->jenis === 'penarikan' && $transaksi->pengajuanTarik && (float)($transaksi->pengajuanTarik->biaya_transfer ?? 0) > 0)
            <div class="row"><span class="label">Nominal Penarikan</span><span class="value">Rp {{ number_format($transaksi->pengajuanTarik->nominal ?? 0, 0, ',', '.') }}</span></div>
            <div class="row"><span class="label">Biaya Transfer</span><span class="value">Rp {{ number_format($transaksi->pengajuanTarik->biaya_transfer ?? 0, 0, ',', '.') }}</span></div>
            <div class="nominal">Total Didebet : Rp {{ number_format($transaksi->nominal ?? 0, 0, ',', '.') }}</div>
            @else
            <div class="nominal">Nominal : Rp {{ number_format($transaksi->nominal ?? 0, 0, ',', '.') }}</div>
            @endif

            @if(!empty($transaksi->keterangan))
            <div class="row"><span class="label">Keterangan</span><span class="value">{{ $transaksi->keterangan }}</span></div>
            @endif

            @php
                $pengajuanSetor = $transaksi->pengajuanSetor ?? null;
                $approver = $pengajuanSetor && $pengajuanSetor->relationLoaded('approvedBy') ? $pengajuanSetor->approvedBy : null;
                $roleLabel = $approver ? (($approver->role === 'admin_utama' ? 'Admin Utama' : ($approver->role === 'admin_operasional' ? 'Admin Operasional' : 'Admin'))) : null;
            @endphp
            @if($approver && $roleLabel)
            <div class="approver">
                <span class="approver-label">Disetujui oleh</span><br />
                <span class="approver-value">{{ $roleLabel }} – {{ $approver->nama ?? 'N/A' }}</span>
            </div>
            @endif
        </div>

        <div class="divider"></div>
        <div class="footer">
            Dicetak : {{ now()->format('d-m-Y H:i') }}<br />
            Dicetak dari {{ $strukSettings->nama_pt }}
        </div>
    </div>
</body>
</html>
