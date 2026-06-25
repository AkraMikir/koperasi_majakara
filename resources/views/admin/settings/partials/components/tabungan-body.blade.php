<!-- HEADER -->
<div class="text-center" style="margin-bottom: 12px; text-align: center;">
    <div style="margin-bottom: 4px;">
        <img src="{{ isset($isPdf) && $isPdf ? public_path('images/logo/674c1d MAJAKARA.png') : asset('images/logo/674c1d MAJAKARA.png') }}" alt="Logo" style="max-width: 130px; max-height: 65px; margin: 0 auto; display: block;" />
    </div>
    <div class="font-bold underline" style="font-size: 14px; margin-bottom: 2px;">{{ $settings->nama_koperasi }}</div>
    <div style="font-size: 10px;">
        {{ $settings->alamat_koperasi }}<br>
        Telp: {{ $settings->no_telp }}
    </div>
</div>

<div class="text-center font-bold" style="margin-bottom: 9px; font-size: 13px; text-align: center;">
    STRUK TRANSAKSI TABUNGAN
</div>

<div class="text-center font-bold" style="margin-bottom: 4px; font-size: 9px; text-transform: uppercase; text-align: center;">
    {{ $data['jenis_trans'] === 'NABUNG' || $data['jenis_trans'] === 'setoran' ? 'SETORAN' : 'PENARIKAN' }}
</div>

<div style="border-top: 1px dashed #000; margin: 9px 0;"></div>

<table style="width: 100%; border-collapse: collapse;">
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 35%; font-size: 11px; padding: 1px 0; white-space: nowrap;">ID</td>
        <td style="font-size: 11px; padding: 1px 0;">: {{ $data['no_struk'] }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 35%; font-size: 11px; padding: 1px 0; white-space: nowrap;">Tanggal</td>
        <td style="font-size: 11px; padding: 1px 0;">: {{ $data['tanggal'] }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 35%; font-size: 11px; padding: 1px 0; white-space: nowrap;">Nama</td>
        <td style="font-size: 11px; padding: 1px 0;">: {{ $data['nama_anggota'] }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 35%; font-size: 11px; padding: 1px 0; white-space: nowrap;">Jenis</td>
        <td style="font-size: 11px; padding: 1px 0;">: {{ $data['jenis_trans'] === 'NABUNG' || $data['jenis_trans'] === 'setoran' ? 'Setoran' : 'Penarikan' }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 35%; font-size: 11px; padding: 1px 0; white-space: nowrap;">Via</td>
        <td style="font-size: 11px; padding: 1px 0;">: {{ $data['via'] }}</td>
    </tr>
</table>

<div style="border-top: 1px dashed #000; margin: 9px 0;"></div>

<!-- DETAIL KEUANGAN -->
<table style="width: 100%; border-collapse: collapse;">
    @if(isset($data['biaya_transfer']) && $data['biaya_transfer'] > 0)
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 45%; font-size: 11px; padding: 1px 0; white-space: nowrap;">Penarikan</td>
        <td style="text-align: right; font-size: 11px; padding: 1px 0;">: Rp {{ number_format($data['nominal_murni'], 0, ',', '.') }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 45%; font-size: 11px; padding: 1px 0; white-space: nowrap;">Transfer</td>
        <td style="text-align: right; font-size: 11px; padding: 1px 0;">: Rp {{ number_format($data['biaya_transfer'], 0, ',', '.') }}</td>
    </tr>
    <div style="border-top: 1px dashed #000; margin: 9px 0;"></div>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; padding: 1px 0; font-size: 11px;">Total</td>
        <td style="font-weight: bold; text-align: right; padding: 1px 0; font-size: 11px;">: Rp {{ number_format($data['nominal'], 0, ',', '.') }}</td>
    </tr>
    @else
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; padding: 1px 0; font-size: 11px;">NOMINAL</td>
        <td style="font-weight: bold; text-align: right; padding: 1px 0; font-size: 11px;">: Rp {{ number_format($data['nominal'], 0, ',', '.') }}</td>
    </tr>
    @endif
</table>

@if(!empty($data['keterangan']))
<div style="border-top: 1px dashed #000; margin: 9px 0;"></div>
<table style="width: 100%; border-collapse: collapse;">
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 35%; font-size: 11px; padding: 1px 0; white-space: nowrap;">Keterangan</td>
        <td style="font-size: 11px; padding: 1px 0;">: {{ $data['keterangan'] }}</td>
    </tr>
</table>
@endif

@if(!empty($data['approver_name']) && !empty($data['approver_role']))
<div style="border-top: 1px dashed #000; margin: 9px 0;"></div>
<div class="text-center" style="margin-top: 10px; font-size: 10px; text-align: center;">
    <span style="font-weight: bold;">Disetujui oleh:</span><br />
    <span>{{ $data['approver_role'] }} – {{ $data['approver_name'] }}</span>
</div>
@endif

<div style="border-top: 1px dashed #000; margin: 9px 0;"></div>

<!-- FOOTER -->
<div class="text-center" style="margin-top: 12px; font-size: 10px; text-align: center;">
    @if(!empty($settings->email) || !empty($settings->website))
    <div style="margin-bottom: 4px;">
        @if(!empty($settings->email))Email: {{ $settings->email }}@endif
        @if(!empty($settings->website))<br>Website: {{ $settings->website }}@endif
    </div>
    @endif
    <div>Dicetak : {{ $data['tanggal_cetak'] ?? $data['tanggal'] }}</div>
    <div class="font-bold" style="margin-top: 4px;">Dicetak dari {{ $settings->nama_pt }}</div>
</div>
