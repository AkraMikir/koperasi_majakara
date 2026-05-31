<div class="bg-amber-50/40 p-4 border border-dashed border-gray-300 rounded-lg max-w-[280px] mx-auto text-xs font-mono text-gray-800 shadow-sm relative">
    <div class="absolute -top-2 left-1/2 -translate-x-1/2 bg-gray-200 text-gray-500 px-2 py-0.5 rounded-full text-[9px] font-sans font-bold uppercase tracking-wider">Tabungan Struk</div>
    
    <div class="text-center pb-2 border-b border-gray-400 mt-2">
        <div class="font-bold text-sm tracking-wide">{{ $settings->nama_koperasi }}</div>
        <div class="text-[10px] text-gray-600 leading-tight">{{ $settings->alamat_koperasi }}</div>
        <div class="text-[10px] text-gray-600">Telp: {{ $settings->no_telp }}</div>
        <div class="font-bold uppercase tracking-wider mt-2 border-t border-dashed border-gray-300 pt-2 text-[11px]">
            STRUK TRANSAKSI TABUNGAN
        </div>
        <div class="text-[10px] text-gray-600">{{ $data['jenis_trans'] }}</div>
    </div>
    
    <div class="py-3 space-y-1">
        <div class="flex justify-between"><span>No. Struk</span><span class="font-semibold">{{ $data['no_struk'] }}</span></div>
        <div class="flex justify-between"><span>Waktu</span><span>{{ $data['tanggal'] }}</span></div>
        <div class="flex justify-between"><span>Nasabah</span><span class="font-semibold">{{ $data['nama_anggota'] }}</span></div>
        <div class="flex justify-between"><span>Jenis</span><span>{{ $data['jenis_trans'] }}</span></div>
        <div class="border-t border-dashed border-gray-300 my-2 pt-2"></div>
        <div class="flex justify-between text-sm font-bold">
            <span>Nominal</span>
            <span>Rp {{ number_format($data['nominal'], 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-[10px] text-gray-500">
            <span>Saldo Awal</span>
            <span>Rp {{ number_format($data['saldo_sebelum'], 0, ',', '.') }}</span>
        </div>
        <div class="flex justify-between text-[10px] text-gray-500">
            <span>Saldo Akhir</span>
            <span>Rp {{ number_format($data['saldo_sekarang'], 0, ',', '.') }}</span>
        </div>
    </div>
    
    <div class="text-center pt-2 border-t border-gray-400 text-[10px] text-gray-500 mt-2 leading-tight">
        Dicetak dari {{ $settings->nama_pt }}<br>
        Terima kasih atas kepercayaan Anda
    </div>
</div>
