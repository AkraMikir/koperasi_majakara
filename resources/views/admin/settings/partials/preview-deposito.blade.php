<div class="bg-white p-2 border border-gray-300 rounded-lg max-w-[240px] mx-auto text-[12px] font-mono text-black shadow-md relative leading-[1.5]">
    <div class="absolute -top-2 left-1/2 -translate-x-1/2 bg-gray-200 text-gray-500 px-2 py-0.5 rounded-full text-[9px] font-sans font-bold uppercase tracking-wider">Deposito Struk</div>
    
    <!-- HEADER -->
    <div class="text-center mt-2">
        <div class="font-bold text-[14px] underline mb-[2px]">{{ $settings->nama_koperasi }}</div>
        <div class="text-[10px] leading-tight">{{ $settings->alamat_koperasi }}</div>
        <div class="text-[10px]">Telp: {{ $settings->no_telp }}</div>
    </div>
    
    <div class="text-center font-bold mt-2 text-[13px] mb-[9px]">
        STRUK BUKTI DEPOSITO
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
            <td class="font-bold">Nomor Deposito</td>
            <td>: <span class="font-bold">{{ $data['no_deposito'] }}</span></td>
        </tr>
        <tr class="align-top">
            <td class="font-bold">Tenor</td>
            <td>: {{ $data['jangka_waktu'] }} Bulan</td>
        </tr>
        <tr class="align-top">
            <td class="font-bold">Suku Bunga</td>
            <td>: {{ number_format($data['bunga'], 2) }}% p.a.</td>
        </tr>
        <tr class="align-top">
            <td class="font-bold">Tanggal Mulai</td>
            <td>: {{ $data['tanggal'] }}</td>
        </tr>
        <tr class="align-top">
            <td class="font-bold">Jatuh Tempo</td>
            <td>: {{ $data['jatuh_tempo'] }}</td>
        </tr>
        <tr class="align-top">
            <td class="font-bold">Status</td>
            <td>: <span class="font-bold">ACTIVE</span></td>
        </tr>
    </table>
    
    <div class="border-t border-dashed border-black my-[9px]"></div>
    
    <div class="text-center font-bold mb-1">NOMINAL</div>
    
    <table class="w-full border-collapse">
        <tr class="align-top">
            <td class="font-bold">Nominal Deposito</td>
            <td class="text-right">: Rp {{ number_format($data['nominal_awal'], 0, ',', '.') }}</td>
        </tr>
        <tr class="align-top">
            <td class="font-bold">Estimasi Bunga</td>
            @php $bungaGross = ($data['nominal_awal'] * ($data['bunga']/100) * ($data['jangka_waktu']/12)); @endphp
            <td class="text-right">: Rp {{ number_format($bungaGross, 0, ',', '.') }}</td>
        </tr>
    </table>
    
    <div class="border-t border-dashed border-black my-[9px]"></div>
    
    <table class="w-full border-collapse">
        <tr class="align-top">
            <td class="font-bold">TOTAL PENERIMAAN JT</td>
            <td class="font-bold text-right">: Rp {{ number_format($data['nominal_awal'] + $bungaGross, 0, ',', '.') }}</td>
        </tr>
    </table>
    
    <div class="border-t border-dashed border-black my-[9px]"></div>
    
    <!-- FOOTER -->
    <div class="text-center text-[10px] mt-3">
        <div>Dicetak : {{ $data['tanggal'] }}</div>
        <div class="font-bold mt-1">Dicetak dari {{ $settings->nama_pt }}</div>
    </div>
</div>
