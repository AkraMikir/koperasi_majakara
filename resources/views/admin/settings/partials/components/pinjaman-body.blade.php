<!-- HEADER -->
<div class="text-center" style="margin-bottom: 12px;">
    <div class="font-bold underline" style="font-size: 14px; margin-bottom: 2px;">{{ $settings->nama_koperasi }}</div>
    <div style="font-size: 10px;">
        {{ $settings->alamat_koperasi }}<br>
        Telp: {{ $settings->no_telp }}
    </div>
</div>

<div class="text-center font-bold" style="margin-bottom: 9px; font-size: 13px;">
    @if($data['jenis_trans'] === 'PENCAIRAN')
        STRUK PENCAIRAN PINJAMAN
    @else
        STRUK PEMBAYARAN ANGSURAN
    @endif
</div>

<div style="border-top: 1px dashed #000; margin: 9px 0;"></div>

@if($data['jenis_trans'] === 'PENCAIRAN')
    <table style="width: 100%; border-collapse: collapse;">
        <tr style="vertical-align: top;">
            <td style="font-weight: normal; width: 45%; font-size: 12px; padding: 1px 0;">ID Pinjaman</td>
            <td style="font-size: 12px; padding: 1px 0;">: {{ $data['no_pinjaman'] }}</td>
        </tr>
        <tr style="vertical-align: top;">
            <td style="font-weight: normal; width: 45%; font-size: 12px; padding: 1px 0;">Tanggal</td>
            <td style="font-size: 12px; padding: 1px 0;">: {{ $data['tanggal'] }}</td>
        </tr>
        <tr style="vertical-align: top;">
            <td style="font-weight: normal; width: 45%; font-size: 12px; padding: 1px 0;">Nama Anggota</td>
            <td style="font-size: 12px; padding: 1px 0;">: {{ $data['nama_anggota'] }}</td>
        </tr>
        <tr style="vertical-align: top;">
            <td style="font-weight: normal; width: 45%; font-size: 12px; padding: 1px 0;">Tenor</td>
            <td style="font-size: 12px; padding: 1px 0;">: {{ $data['lama_pinjam'] }} bulan</td>
        </tr>
        <tr style="vertical-align: top;">
            <td style="font-weight: normal; width: 45%; font-size: 12px; padding: 1px 0;">Angsuran per Bln</td>
            <td style="font-size: 12px; padding: 1px 0;">: Rp {{ number_format($data['angsuran_pertama'], 0, ',', '.') }}</td>
        </tr>
        <tr style="vertical-align: top;">
            <td style="font-weight: normal; width: 45%; font-size: 12px; padding: 1px 0;">Metode</td>
            <td style="font-size: 12px; padding: 1px 0;">: {{ $data['metode'] }}</td>
        </tr>
    </table>
    
    <div style="border-top: 1px dashed #000; margin: 9px 0;"></div>
    
    <table style="width: 100%; border-collapse: collapse;">
        <tr style="vertical-align: top;">
            <td style="font-weight: bold; padding: 1px 0;">NOMINAL PINJAMAN</td>
            <td style="font-weight: bold; text-align: right; padding: 1px 0;">: Rp {{ number_format($data['jumlah_pinjam'], 0, ',', '.') }}</td>
        </tr>
    </table>
@else
    <table style="width: 100%; border-collapse: collapse;">
        <tr style="vertical-align: top;">
            <td style="font-weight: normal; width: 45%; font-size: 12px; padding: 1px 0;">ID Pinjaman</td>
            <td style="font-size: 12px; padding: 1px 0;">: {{ $data['no_pinjaman'] }}</td>
        </tr>
        <tr style="vertical-align: top;">
            <td style="font-weight: normal; width: 45%; font-size: 12px; padding: 1px 0;">Angsuran Ke</td>
            <td style="font-size: 12px; padding: 1px 0;">: {{ $data['angsuran_ke'] }}</td>
        </tr>
        <tr style="vertical-align: top;">
            <td style="font-weight: normal; width: 45%; font-size: 12px; padding: 1px 0;">Nama</td>
            <td style="font-size: 12px; padding: 1px 0;">: {{ $data['nama_anggota'] }}</td>
        </tr>
        <tr style="vertical-align: top;">
            <td style="font-weight: normal; width: 45%; font-size: 12px; padding: 1px 0;">Tanggal</td>
            <td style="font-size: 12px; padding: 1px 0;">: {{ $data['tanggal'] }}</td>
        </tr>
        <tr style="vertical-align: top;">
            <td style="font-weight: normal; width: 45%; font-size: 12px; padding: 1px 0;">Metode</td>
            <td style="font-size: 12px; padding: 1px 0;">: {{ $data['metode'] }}</td>
        </tr>
        <tr style="vertical-align: top;">
            <td style="font-weight: normal; width: 45%; font-size: 12px; padding: 1px 0;">Status</td>
            <td style="font-size: 12px; padding: 1px 0;">: {{ $data['status'] }}</td>
        </tr>
    </table>
    
    <div style="border-top: 1px dashed #000; margin: 9px 0;"></div>
    
    <table style="width: 100%; border-collapse: collapse;">
        <tr style="vertical-align: top;">
            <td style="font-weight: bold; padding: 1px 0;">NOMINAL</td>
            <td style="font-weight: bold; text-align: right; padding: 1px 0;">: Rp {{ number_format($data['nominal'], 0, ',', '.') }}</td>
        </tr>
    </table>
@endif

<div style="border-top: 1px dashed #000; margin: 9px 0;"></div>

<!-- FOOTER -->
<div class="text-center" style="margin-top: 12px; font-size: 10px;">
    @if(!empty($settings->email) || !empty($settings->website))
    <div style="margin-bottom: 4px;">
        @if(!empty($settings->email))Email: {{ $settings->email }}@endif
        @if(!empty($settings->website))<br>Website: {{ $settings->website }}@endif
    </div>
    @endif
    <div>Dicetak : {{ $data['tanggal_cetak'] ?? $data['tanggal'] }}</div>
    <div class="font-bold" style="margin-top: 4px;">Dicetak dari {{ $settings->nama_pt }}</div>
</div>
