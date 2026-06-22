<!-- HEADER -->
<div class="text-center" style="margin-bottom: 12px;">
    <div class="font-bold underline" style="font-size: 14px; margin-bottom: 2px;">{{ $settings->nama_koperasi }}</div>
    <div style="font-size: 10px;">
        {{ $settings->alamat_koperasi }}<br>
        Telp: {{ $settings->no_telp }}
    </div>
</div>

<div class="text-center font-bold" style="margin-bottom: 9px; font-size: 13px;">
    STRUK BUKTI DEPOSITO
</div>

<div style="border-top: 1px dashed #000; margin: 9px 0;"></div>

<!-- INFO NASABAH -->
<table style="width: 100%; border-collapse: collapse;">
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 45%; font-size: 12px; padding: 1px 0;">Nama Anggota</td>
        <td style="font-size: 12px; padding: 1px 0;">: {{ $data['nama_anggota'] }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 45%; font-size: 12px; padding: 1px 0;">No. Anggota</td>
        <td style="font-size: 12px; padding: 1px 0;">: {{ $data['no_anggota'] }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 45%; font-size: 12px; padding: 1px 0;">Nomor Deposito</td>
        <td style="font-size: 12px; padding: 1px 0;">: <span class="bold">{{ $data['no_deposito'] }}</span></td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 45%; font-size: 12px; padding: 1px 0;">Tenor</td>
        <td style="font-size: 12px; padding: 1px 0;">: {{ $data['jangka_waktu'] }} Bulan</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 45%; font-size: 12px; padding: 1px 0;">Suku Bunga</td>
        <td style="font-size: 12px; padding: 1px 0;">: {{ number_format($data['bunga'], 2) }}% p.a.</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 45%; font-size: 12px; padding: 1px 0;">Tanggal Mulai</td>
        <td style="font-size: 12px; padding: 1px 0;">: {{ $data['tanggal'] }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 45%; font-size: 12px; padding: 1px 0;">Jatuh Tempo</td>
        <td style="font-size: 12px; padding: 1px 0;">: {{ $data['jatuh_tempo'] }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 45%; font-size: 12px; padding: 1px 0;">Status</td>
        <td style="font-size: 12px; padding: 1px 0;">: <span class="bold">{{ strtoupper($data['status']) }}</span></td>
    </tr>
</table>

<div style="border-top: 1px dashed #000; margin: 9px 0;"></div>

<!-- NOMINAL -->
<div class="text-center font-bold" style="margin-bottom: 2px;">
    NOMINAL
</div>

<table style="width: 100%; border-collapse: collapse;">
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; font-size: 12px; padding: 1px 0;">Nominal Deposito</td>
        <td style="text-align: right; font-size: 12px; padding: 1px 0;">: Rp {{ number_format($data['nominal_awal'], 0, ',', '.') }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; font-size: 12px; padding: 1px 0;">Estimasi Bunga</td>
        <td style="text-align: right; font-size: 12px; padding: 1px 0;">: Rp {{ number_format($data['bunga_gross'], 0, ',', '.') }}</td>
    </tr>
</table>

<div style="border-top: 1px dashed #000; margin: 9px 0;"></div>

<table style="width: 100%; border-collapse: collapse;">
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; padding: 1px 0;">TOTAL PENERIMAAN JT</td>
        <td style="font-weight: bold; text-align: right; padding: 1px 0;">: Rp {{ number_format($data['nominal_akhir'], 0, ',', '.') }}</td>
    </tr>
</table>

<div style="border-top: 1px dashed #000; margin: 9px 0;"></div>

<!-- FOOTER -->
<div class="text-center" style="margin-top: 12px; font-size: 10px;">
    @if($settings->email || $settings->website || $settings->nama_pt)
    <div style="margin-bottom: 2px;">
        @if($settings->email)Email: {{ $settings->email }}@endif
        @if($settings->website)<br>Website: {{ $settings->website }}@endif
        @if($settings->nama_pt)<br>{{ $settings->nama_pt }}@endif
    </div>
    @endif
    <div class="bold" style="margin-top: 4px;">Terima Kasih</div>
    <div>Simpan struk ini sebagai bukti kepemilikan Deposito resmi.</div>
</div>
