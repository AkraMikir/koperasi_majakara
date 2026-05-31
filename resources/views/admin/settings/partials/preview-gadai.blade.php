<div class="bg-amber-50/40 p-4 border border-dashed border-gray-300 rounded-lg max-w-[280px] mx-auto text-xs font-mono text-gray-800 shadow-sm relative">
    <div class="absolute -top-2 left-1/2 -translate-x-1/2 bg-gray-200 text-gray-500 px-2 py-0.5 rounded-full text-[9px] font-sans font-bold uppercase tracking-wider">Gadai Struk</div>
    
    <div class="text-center pb-2 border-b border-gray-400 mt-2">
        <div class="font-bold text-sm tracking-wide">{{ $settings->nama_koperasi }}</div>
        <div class="text-[10px] text-gray-600 leading-tight">{{ $settings->alamat_koperasi }}</div>
        <div class="text-[10px] text-gray-600">Telp: {{ $settings->no_telp }}</div>
        <div class="font-bold uppercase tracking-wider mt-2 border-t border-dashed border-gray-300 pt-2 text-[11px]">
            @if($data['jenis_trans'] === 'AKTIF')
                STRUK PENERIMAAN GADAI
            @elseif($data['jenis_trans'] === 'PERPANJANGAN')
                STRUK PERPANJANGAN GADAI
            @else
                STRUK PELUNASAN GADAI
            @endif
        </div>
    </div>
    
    <div class="py-3 space-y-1">
        <div class="flex justify-between"><span>No. Struk</span><span class="font-semibold">{{ $data['no_struk'] }}</span></div>
        <div class="flex justify-between"><span>Waktu</span><span>{{ $data['tanggal'] }}</span></div>
        <div class="flex justify-between"><span>Nasabah</span><span class="font-semibold">{{ $data['nama_anggota'] }}</span></div>
        <div class="flex justify-between"><span>No. Gadai</span><span>{{ $data['no_gadai'] }}</span></div>
        <div class="flex justify-between"><span>Gudang Slot</span><span>{{ $data['slot_kode'] }}</span></div>
        <div class="flex justify-between"><span>Jaminan</span><span class="font-semibold">{{ $data['barang'] }}</span></div>
        
        <div class="border-t border-dashed border-gray-300 my-2 pt-2"></div>
        
        @if($data['jenis_trans'] === 'AKTIF')
            <div class="flex justify-between text-sm font-bold">
                <span>Nilai Pencairan</span>
                <span>Rp {{ number_format($data['nominal_deal'], 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-[10px] text-gray-500">
                <span>Biaya Admin ({{ $settings->bunga_admin_gadai }}%)</span>
                <span>Rp {{ number_format($data['biaya_jasa'], 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-[10px] text-gray-500">
                <span>Biaya Simpan/Inap</span>
                <span>Rp {{ number_format($data['biaya_inap'], 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-[10px] text-gray-500">
                <span>Jatuh Tempo</span>
                <span>{{ $data['jatuh_tempo'] }}</span>
            </div>
        @elseif($data['jenis_trans'] === 'PERPANJANGAN')
            <div class="flex justify-between"><span>Perpanjangan</span><span>Ke-1</span></div>
            <div class="flex justify-between text-sm font-bold">
                <span>Bayar Jasa & Inap</span>
                <span>Rp {{ number_format($data['biaya_jasa'] + $data['biaya_inap'], 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-[10px] text-gray-500">
                <span>Jatuh Tempo Baru</span>
                <span>{{ \Carbon\Carbon::parse(str_replace('/', '-', $data['jatuh_tempo']))->addMonths(1)->format('d/m/Y') }}</span>
            </div>
        @else
            <div class="flex justify-between text-[10px] text-gray-500">
                <span>Uang Pinjaman</span>
                <span>Rp {{ number_format($data['nominal_deal'], 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm font-bold">
                <span>Total Tebusan</span>
                <span>Rp {{ number_format($data['nominal_deal'] + $data['biaya_jasa'] + $data['biaya_inap'], 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-green-700 font-bold uppercase text-[10px] text-center w-full pt-1">
                -- BARANG JAMINAN DIKEMBALIKAN --
            </div>
        @endif
    </div>
    
    <div class="pt-2 border-t border-dashed border-gray-300 text-[8px] text-gray-500 mt-2">
        <div class="font-bold text-[9px] mb-1">Syarat & Ketentuan:</div>
        <div class="whitespace-pre-line leading-tight">{{ $settings->syarat_ketentuan_gadai }}</div>
    </div>
    
    <div class="text-center pt-2 border-t border-gray-400 text-[10px] text-gray-500 mt-2 leading-tight">
        Dicetak dari {{ $settings->nama_pt }}<br>
        Harap simpan struk ini sebagai bukti resmi
    </div>
</div>
