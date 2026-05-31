<div class="bg-amber-50/40 p-4 border border-dashed border-gray-300 rounded-lg max-w-[280px] mx-auto text-xs font-mono text-gray-800 shadow-sm relative">
    <div class="absolute -top-2 left-1/2 -translate-x-1/2 bg-gray-200 text-gray-500 px-2 py-0.5 rounded-full text-[9px] font-sans font-bold uppercase tracking-wider">Pinjaman Struk</div>
    
    <div class="text-center pb-2 border-b border-gray-400 mt-2">
        <div class="font-bold text-sm tracking-wide">{{ $settings->nama_koperasi }}</div>
        <div class="text-[10px] text-gray-600 leading-tight">{{ $settings->alamat_koperasi }}</div>
        <div class="text-[10px] text-gray-600">Telp: {{ $settings->no_telp }}</div>
        <div class="font-bold uppercase tracking-wider mt-2 border-t border-dashed border-gray-300 pt-2 text-[11px]">
            @if($data['jenis_trans'] === 'PENCAIRAN')
                STRUK PENCAIRAN PINJAMAN
            @elseif($data['jenis_trans'] === 'ANGSURAN')
                STRUK ANGSURAN PINJAMAN
            @else
                STRUK PELUNASAN PINJAMAN
            @endif
        </div>
    </div>
    
    <div class="py-3 space-y-1">
        <div class="flex justify-between"><span>No. Struk</span><span class="font-semibold">{{ $data['no_struk'] }}</span></div>
        <div class="flex justify-between"><span>Waktu</span><span>{{ $data['tanggal'] }}</span></div>
        <div class="flex justify-between"><span>Nasabah</span><span class="font-semibold">{{ $data['nama_anggota'] }}</span></div>
        <div class="flex justify-between"><span>No. Pinjaman</span><span>{{ $data['no_pinjaman'] }}</span></div>
        
        <div class="border-t border-dashed border-gray-300 my-2 pt-2"></div>
        
        @if($data['jenis_trans'] === 'PENCAIRAN')
            <div class="flex justify-between text-sm font-bold">
                <span>Pencairan</span>
                <span>Rp {{ number_format($data['jumlah_pinjam'], 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-[10px] text-gray-500">
                <span>Tenor</span>
                <span>{{ $data['lama_pinjam'] }} Bulan</span>
            </div>
            <div class="flex justify-between text-[10px] text-gray-500">
                <span>Suku Bunga</span>
                <span>{{ $data['bunga'] }}% p.a.</span>
            </div>
        @elseif($data['jenis_trans'] === 'ANGSURAN')
            <div class="flex justify-between"><span>Angsuran Ke</span><span>1 dari {{ $data['lama_pinjam'] }}</span></div>
            <div class="flex justify-between text-sm font-bold">
                <span>Bayar Angsuran</span>
                <span>Rp {{ number_format($data['jumlah_pinjam'] / $data['lama_pinjam'] + ($data['jumlah_pinjam'] * ($data['bunga']/100/12)), 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-[10px] text-gray-500">
                <span>Sisa Pinjaman</span>
                <span>Rp {{ number_format($data['jumlah_pinjam'] - ($data['jumlah_pinjam'] / $data['lama_pinjam']), 0, ',', '.') }}</span>
            </div>
        @else
            <div class="flex justify-between text-sm font-bold">
                <span>Pelunasan</span>
                <span>Rp {{ number_format($data['jumlah_pinjam'], 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-green-700 font-bold uppercase text-[10px] text-center w-full pt-1">
                -- STATUS: LUNAS --
            </div>
        @endif
    </div>
    
    <div class="text-center pt-2 border-t border-gray-400 text-[10px] text-gray-500 mt-2 leading-tight">
        Dicetak dari {{ $settings->nama_pt }}<br>
        Terima kasih atas kerjasama Anda
    </div>
</div>
