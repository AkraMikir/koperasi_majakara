<div class="bg-white p-2 border border-gray-300 rounded-lg max-w-[240px] mx-auto text-[12px] font-mono text-black shadow-md relative leading-[1.5]">
    <div class="absolute -top-2 left-1/2 -translate-x-1/2 bg-gray-200 text-gray-500 px-2 py-0.5 rounded-full text-[9px] font-sans font-bold uppercase tracking-wider">Gadai Struk</div>
    
    <!-- HEADER -->
    <div class="text-center mt-2">
        <div class="font-bold text-[14px] underline mb-[2px]">{{ $settings->nama_koperasi }}</div>
        <div class="text-[10px] leading-tight">{{ $settings->alamat_koperasi }}</div>
        <div class="text-[10px]">Telp: {{ $settings->no_telp }}</div>
    </div>
    
    <div class="text-center font-bold mt-2 text-[13px] mb-[9px]">
        @if($data['jenis_trans'] === 'AKTIF')
            STRUK GADAI AWAL
        @elseif($data['jenis_trans'] === 'PERPANJANGAN')
            STRUK PERPANJANGAN GADAI
        @else
            STRUK PELUNASAN GADAI
        @endif
    </div>
    
    <div class="border-t border-dashed border-black my-[9px]"></div>
    
    <table class="w-full border-collapse">
        <tr class="align-top">
            <td class="font-bold w-[45%]">Nama Anggota</td>
            <td>: {{ $data['nama_anggota'] }}</td>
        </tr>
        <tr class="align-top">
            <td class="font-bold">No. Anggota</td>
            <td>: 12345</td>
        </tr>
        <tr class="align-top">
            <td class="font-bold">Kategori</td>
            <td>: {{ $data['kategori'] }}</td>
        </tr>
        <tr class="align-top">
            <td class="font-bold">Barang</td>
            <td>: {{ $data['barang'] }}</td>
        </tr>
        <tr class="align-top">
            <td class="font-bold">Slot Kode</td>
            <td>: <span class="font-bold">{{ $data['slot_kode'] }}</span></td>
        </tr>
    </table>
    
    <div class="border-t border-dashed border-black my-[9px]"></div>
    
    <div class="text-center font-bold mb-1">DETAIL GADAI</div>
    
    <table class="w-full border-collapse">
        <tr class="align-top">
            <td class="font-bold">Tanggal Mulai</td>
            <td>: {{ $data['tgl_mulai'] }}</td>
        </tr>
        <tr class="align-top">
            <td class="font-bold">Jatuh Tempo</td>
            <td>: {{ $data['jatuh_tempo'] }}</td>
        </tr>
        <tr class="align-top">
            <td class="font-bold">Nominal Deal</td>
            <td class="text-right">: Rp {{ number_format($data['nominal_deal'], 0, ',', '.') }}</td>
        </tr>
        <tr class="align-top">
            <td class="font-bold">Biaya Jasa</td>
            <td class="text-right">: Rp {{ number_format($data['biaya_jasa'], 0, ',', '.') }}</td>
        </tr>
        <tr class="align-top">
            <td class="font-bold">Biaya Inap</td>
            <td class="text-right">: Rp {{ number_format($data['biaya_inap'], 0, ',', '.') }}</td>
        </tr>
    </table>
    
    <div class="border-t border-dashed border-black my-[9px]"></div>
    
    <table class="w-full border-collapse">
        <tr class="align-top">
            <td class="font-bold">TOTAL TAGIHAN</td>
            <td class="font-bold text-right">: Rp {{ number_format($data['total_tagihan'], 0, ',', '.') }}</td>
        </tr>
    </table>
    
    <div class="border-t border-dashed border-black my-[9px]"></div>
    
    <!-- SIGNATURES -->
    <table class="w-full text-center mt-3 text-[10px]">
        <tr>
            <td class="pb-10">Nasabah / Anggota</td>
            <td class="pb-10">Petugas Admin</td>
        </tr>
        <tr class="font-bold">
            <td>(...................)</td>
            <td>(...................)</td>
        </tr>
    </table>
    
    <div class="border-t border-dashed border-black my-[9px]"></div>
    
    <!-- FOOTER -->
    <div class="text-center text-[10px] mt-3">
        <div>Dicetak : {{ $data['tanggal'] }}</div>
        <div class="font-bold mt-1">Dicetak dari {{ $settings->nama_pt }}</div>
    </div>
</div>
