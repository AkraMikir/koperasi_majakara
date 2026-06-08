<div class="bg-white p-2 border border-gray-300 rounded-lg max-w-[240px] mx-auto text-[12px] font-mono text-black shadow-md relative leading-[1.5]">
    <div class="absolute -top-2 left-1/2 -translate-x-1/2 bg-gray-200 text-gray-500 px-2 py-0.5 rounded-full text-[9px] font-sans font-bold uppercase tracking-wider">Tabungan Struk</div>
    
    <!-- HEADER -->
    <div class="text-center mt-2">
        <div class="font-bold text-[14px] underline mb-[2px]">{{ $settings->nama_koperasi }}</div>
        <div class="text-[10px] leading-tight">{{ $settings->alamat_koperasi }}</div>
        <div class="text-[10px]">Telp: {{ $settings->no_telp }}</div>
    </div>
    
    <div class="text-center font-bold mt-2 text-[13px] mb-[9px]">
        STRUK TRANSAKSI TABUNGAN
    </div>
    
    <div class="text-center font-bold text-[11px] uppercase">
        {{ $data['jenis_trans'] === 'NABUNG' ? 'SETORAN' : 'PENARIKAN' }}
    </div>

    <div class="border-t border-dashed border-black my-[9px]"></div>
    
    <table class="w-full border-collapse">
        <tr class="align-top">
            <td class="font-bold w-[45%]">ID Transaksi</td>
            <td>: {{ $data['no_struk'] }}</td>
        </tr>
        <tr class="align-top">
            <td class="font-bold">Tanggal & Waktu</td>
            <td>: {{ $data['tanggal'] }}</td>
        </tr>
        <tr class="align-top">
            <td class="font-bold">Nama Anggota</td>
            <td>: {{ $data['nama_anggota'] }}</td>
        </tr>
        <tr class="align-top">
            <td class="font-bold">NIK</td>
            <td>: 320101XXXXXXXXXX</td>
        </tr>
        <tr class="align-top">
            <td class="font-bold">Jenis Transaksi</td>
            <td>: {{ $data['jenis_trans'] === 'NABUNG' ? 'Setoran' : 'Penarikan' }}</td>
        </tr>
        <tr class="align-top">
            <td class="font-bold">Via</td>
            <td>: Cash</td>
        </tr>
    </table>
    
    <div class="border-t border-dashed border-black my-[9px]"></div>
    
    <table class="w-full border-collapse">
        <tr class="align-top">
            <td class="font-bold">NOMINAL</td>
            <td class="font-bold text-right">: Rp {{ number_format($data['nominal'], 0, ',', '.') }}</td>
        </tr>
    </table>

    <div class="border-t border-dashed border-black my-[9px]"></div>
    
    <!-- FOOTER -->
    <div class="text-center text-[10px] mt-3">
        <div>Dicetak : {{ $data['tanggal'] }}</div>
        <div class="font-bold mt-1">Dicetak dari {{ $settings->nama_pt }}</div>
    </div>
</div>
