<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Struk Gadai - {{ $gadai->slot_kode }}</title>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        body {
            font-family: 'Courier', monospace;
            font-size: 10px;
            line-height: 1.3;
            color: #000;
            padding: 8px;
        }
        .center { text-align: center; }
        .bold { font-weight: bold; }
        .underline { text-decoration: underline; }
        .dashed { border-top: 1px dashed #000; margin: 6px 0; }
        .table-row {
            width: 100%;
            margin-bottom: 3px;
        }
        .table-row td {
            vertical-align: top;
        }
        .label { font-weight: bold; width: 45%; }
        .value { text-align: left; }
        .header { margin-bottom: 8px; }
        .footer { margin-top: 8px; font-size: 8px; }
        .syarat { 
            font-size: 8px; 
            margin-top: 6px; 
            text-align: justify;
            line-height: 1.2;
            white-space: pre-line;
        }
        table { width: 100%; border-collapse: collapse; }
        td { padding: 1px 0; }
        .nominal-table td {
            padding: 2px 0;
        }
        .text-right {
            text-align: right;
        }
    </style>
</head>
<body>
    <!-- HEADER -->
    <div class="header">
        <div class="center bold underline" style="font-size: 12px; margin-bottom: 2px;">
            {{ $settings->nama_koperasi }}
        </div>
        <div class="center" style="font-size: 8px;">
            {{ $settings->alamat_koperasi }}<br>
            Telp: {{ $settings->no_telp }}
        </div>
    </div>
    
    <div class="center bold" style="margin-bottom: 6px; font-size: 11px;">
        STRUK GADAI
    </div>
    
    <table style="font-size: 8px; margin-bottom: 4px; width: 100%;">
        <tr>
            <td>No. Struk: {{ $no_struk }}</td>
            <td style="text-align: right;">Tanggal: {{ $tanggal_cetak }}</td>
        </tr>
    </table>
    
    <div class="dashed"></div>
    
    <!-- INFO NASABAH -->
    <table class="table-row">
        <tr>
            <td class="label">Nama Anggota</td>
            <td>: {{ $gadai->nasabah->user->nama ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">No. Anggota</td>
            <td>: {{ $gadai->nasabah->id ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Kategori</td>
            <td>: {{ $gadai->kategori->nama_kategori ?? '-' }}</td>
        </tr>
        <tr>
            <td class="label">Barang</td>
            <td>: {{ $gadai->item->head_1 ?? '-' }} {{ $gadai->item->head_2 ?? '' }}</td>
        </tr>
    </table>
    
    <div class="dashed"></div>
    
    <!-- INFO GADAI -->
    <div class="center bold" style="margin-bottom: 2px;">
        DETAIL GADAI
    </div>
    
    <table class="table-row">
        <tr>
            <td class="label">No. Gadai</td>
            <td>: {{ $gadai->id }}</td>
        </tr>
        <tr>
            <td class="label">Slot Kode</td>
            <td>: <span class="bold">{{ $gadai->slot_kode }}</span></td>
        </tr>
        <tr>
            <td class="label">Tanggal Mulai</td>
            <td>: {{ \Carbon\Carbon::parse($gadai->tgl_mulai)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="label">Jatuh Tempo</td>
            <td>: {{ \Carbon\Carbon::parse($gadai->tgl_jatuh_tempo)->format('d/m/Y') }}</td>
        </tr>
        <tr>
            <td class="label">Status</td>
            <td>: <span class="bold">{{ ucfirst($gadai->status) }}</span></td>
        </tr>
    </table>
    
    <div class="dashed"></div>
    
    <!-- NOMINAL -->
    <div class="center bold" style="margin-bottom: 2px;">
        NOMINAL
    </div>
    
    <table class="nominal-table">
        <tr>
            <td class="label">Nominal Deal</td>
            <td class="text-right">: Rp {{ number_format($gadai->nominal_deal, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Biaya Jasa</td>
            <td class="text-right">: Rp {{ number_format($gadai->biaya_jasa, 0, ',', '.') }}</td>
        </tr>
        <tr>
            <td class="label">Biaya Inap</td>
            <td class="text-right">: Rp {{ number_format($gadai->biaya_inap, 0, ',', '.') }}</td>
        </tr>
        @if($gadai->denda_aktif > 0)
        <tr>
            <td class="label">Denda Aktif</td>
            <td class="text-right">: Rp {{ number_format($gadai->denda_aktif, 0, ',', '.') }}</td>
        </tr>
        @endif
        @if($gadai->extra_pinjaman_nominal)
        <tr>
            <td class="label">Extra Pinjaman</td>
            <td class="text-right">: Rp {{ number_format($gadai->extra_pinjaman_nominal, 0, ',', '.') }}</td>
        </tr>
        @if($gadai->extra_pinjaman_reason)
        <tr>
            <td></td>
            <td class="text-right" style="font-size: 8px;">({{ $gadai->extra_pinjaman_reason }})</td>
        </tr>
        @endif
        @endif
    </table>
    
    <div class="dashed"></div>
    
    <table class="nominal-table">
        <tr>
            <td class="bold">TOTAL TAGIHAN</td>
            <td class="bold text-right">: Rp {{ number_format($total_tagihan, 0, ',', '.') }}</td>
        </tr>
    </table>
    
    <div class="dashed"></div>
    
    <!-- SYARAT & KETENTUAN -->
    @if($settings->syarat_ketentuan_gadai)
    <div class="center bold" style="margin-bottom: 2px; font-size: 9px;">
        SYARAT & KETENTUAN
    </div>
    <div class="syarat">
        {{ $settings->syarat_ketentuan_gadai }}
    </div>
    <div class="dashed"></div>
    @endif
    
    <!-- FOOTER -->
    <div class="footer center">
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
</body>
</html>
