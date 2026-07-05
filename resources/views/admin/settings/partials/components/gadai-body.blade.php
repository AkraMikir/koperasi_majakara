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
    @if($data['jenis_trans'] === 'AKTIF')
        STRUK GADAI AWAL
    @elseif($data['jenis_trans'] === 'PERPANJANGAN')
        STRUK PERPANJANGAN GADAI
    @else
        STRUK PELUNASAN GADAI
    @endif
</div>

<div style="border-top: 1px dashed #000; margin: 9px 0;"></div>

<table style="width: 100%; border-collapse: collapse;">
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 35%; font-size: 11px; padding: 1px 0; white-space: nowrap;">Nama</td>
        <td style="font-size: 11px; padding: 1px 0;">: {{ $data['nama_anggota'] }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 35%; font-size: 11px; padding: 1px 0; white-space: nowrap;">Anggota</td>
        <td style="font-size: 11px; padding: 1px 0;">: {{ $data['no_anggota'] }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 35%; font-size: 11px; padding: 1px 0; white-space: nowrap;">Kategori</td>
        <td style="font-size: 11px; padding: 1px 0;">: {{ $data['kategori'] }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 35%; font-size: 11px; padding: 1px 0; white-space: nowrap;">Barang</td>
        <td style="font-size: 11px; padding: 1px 0;">: {{ $data['barang'] }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; width: 35%; font-size: 11px; padding: 1px 0; white-space: nowrap;">Slot</td>
        <td style="font-size: 11px; padding: 1px 0;">: <span class="bold">{{ $data['slot_kode'] }}</span></td>
    </tr>
</table>

<div style="border-top: 1px dashed #000; margin: 9px 0;"></div>

<div class="text-center font-bold" style="margin-bottom: 2px; text-align: center;">DETAIL GADAI</div>

<table style="width: 100%; border-collapse: collapse;">
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; font-size: 11px; padding: 1px 0; white-space: nowrap;">Mulai</td>
        <td style="font-size: 11px; padding: 1px 0;">: {{ $data['tgl_mulai'] }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; font-size: 11px; padding: 1px 0; white-space: nowrap;">Tempo</td>
        <td style="font-size: 11px; padding: 1px 0;">: {{ $data['jatuh_tempo'] }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; font-size: 11px; padding: 1px 0; white-space: nowrap;">Nominal</td>
        <td style="text-align: right; font-size: 11px; padding: 1px 0;">: Rp {{ number_format($data['nominal_deal'], 0, ',', '.') }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; font-size: 11px; padding: 1px 0; white-space: nowrap;">Jasa</td>
        <td style="text-align: right; font-size: 11px; padding: 1px 0;">: Rp {{ number_format($data['biaya_jasa'], 0, ',', '.') }}</td>
    </tr>
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; font-size: 11px; padding: 1px 0; white-space: nowrap;">Inap</td>
        <td style="text-align: right; font-size: 11px; padding: 1px 0;">: Rp {{ number_format($data['biaya_inap'], 0, ',', '.') }}</td>
    </tr>
    @if(isset($data['denda_aktif']) && $data['denda_aktif'] > 0)
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; font-size: 11px; padding: 1px 0; white-space: nowrap;">Denda</td>
        <td style="text-align: right; font-size: 11px; padding: 1px 0;">: Rp {{ number_format($data['denda_aktif'], 0, ',', '.') }}</td>
    </tr>
    @endif
    @if(isset($data['extra_pinjaman_nominal']) && $data['extra_pinjaman_nominal'] > 0)
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; font-size: 11px; padding: 1px 0; white-space: nowrap;">Ekstra</td>
        <td style="text-align: right; font-size: 11px; padding: 1px 0;">: Rp {{ number_format($data['extra_pinjaman_nominal'], 0, ',', '.') }}</td>
    </tr>
    @if(!empty($data['extra_pinjaman_reason']))
    <tr style="vertical-align: top;">
        <td></td>
        <td style="text-align: right; font-size: 10px; padding: 1px 0;">({{ $data['extra_pinjaman_reason'] }})</td>
    </tr>
    @endif
    @endif
</table>

<div style="border-top: 1px dashed #000; margin: 9px 0;"></div>

<table style="width: 100%; border-collapse: collapse;">
    <tr style="vertical-align: top;">
        <td style="font-weight: bold; padding: 1px 0; font-size: 11px;">Total</td>
        <td style="font-weight: bold; text-align: right; padding: 1px 0; font-size: 11px;">: Rp {{ number_format($data['total_tagihan'], 0, ',', '.') }}</td>
    </tr>
</table>

<div style="border-top: 1px dashed #000; margin: 9px 0;"></div>

<!-- SIGNATURES -->
<table style="width: 100%; border-collapse: collapse; text-align: center; margin-top: 12px; font-size: 10px;">
    <tr>
        <td style="padding-bottom: 20px;">Nasabah / Anggota</td>
        <td style="padding-bottom: 20px;">Petugas Admin</td>
    </tr>
    <tr class="bold">
        <td>(...................)</td>
        <td>(...................)</td>
    </tr>
</table>

<div style="border-top: 1px dashed #000; margin: 9px 0;"></div>

<!-- SYARAT & KETENTUAN -->
@if(!empty($settings->syarat_ketentuan_gadai))
<div class="text-center font-bold" style="margin-bottom: 9px; font-size: 13px;">
    SYARAT & KETENTUAN
</div>
<div style="font-size: 10px; margin-top: 6px; text-align: justify; line-height: 1.2; white-space: pre-line;">
    {{ $settings->syarat_ketentuan_gadai }}
</div>
<div style="border-top: 1px dashed #000; margin: 9px 0;"></div>
@endif

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
    <div>Visit Again Please!</div>
</div>
