<div class="bg-amber-50/40 p-4 border border-dashed border-gray-300 rounded-lg max-w-[280px] mx-auto text-xs font-mono text-gray-800 shadow-sm relative">
    <div class="absolute -top-2 left-1/2 -translate-x-1/2 bg-gray-200 text-gray-500 px-2 py-0.5 rounded-full text-[9px] font-sans font-bold uppercase tracking-wider">Deposito Struk</div>
    
    <div class="text-center pb-2 border-b border-gray-400 mt-2">
        <div class="font-bold text-sm tracking-wide">{{ $settings->nama_koperasi }}</div>
        <div class="text-[10px] text-gray-600 leading-tight">{{ $settings->alamat_koperasi }}</div>
        <div class="text-[10px] text-gray-600">Telp: {{ $settings->no_telp }}</div>
        <div class="font-bold uppercase tracking-wider mt-2 border-t border-dashed border-gray-300 pt-2 text-[11px]">
            @if($data['jenis_trans'] === 'PENGAJUAN')
                STRUK PENEMPATAN DEPOSITO
            @elseif($data['jenis_trans'] === 'PENCAIRAN SEBELUM TEMPO')
                STRUK PENCAIRAN AWAL DEPOSITO
            @else
                STRUK PENCAIRAN DEPOSITO
            @endif
        </div>
    </div>
    
    <div class="py-3 space-y-1">
        <div class="flex justify-between"><span>No. Struk</span><span class="font-semibold">{{ $data['no_struk'] }}</span></div>
        <div class="flex justify-between"><span>Waktu</span><span>{{ $data['tanggal'] }}</span></div>
        <div class="flex justify-between"><span>Nasabah</span><span class="font-semibold">{{ $data['nama_anggota'] }}</span></div>
        <div class="flex justify-between"><span>No. Deposito</span><span>{{ $data['no_deposito'] }}</span></div>
        
        <div class="border-t border-dashed border-gray-300 my-2 pt-2"></div>
        
        @if($data['jenis_trans'] === 'PENGAJUAN')
            <div class="flex justify-between text-sm font-bold">
                <span>Nominal Pokok</span>
                <span>Rp {{ number_format($data['nominal_awal'], 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-[10px] text-gray-500">
                <span>Tenor</span>
                <span>{{ $data['jangka_waktu'] }} Bulan</span>
            </div>
            <div class="flex justify-between text-[10px] text-gray-500">
                <span>Suku Bunga</span>
                <span>{{ $data['bunga'] }}% p.a.</span>
            </div>
            <div class="flex justify-between text-[10px] text-gray-500">
                <span>Jatuh Tempo</span>
                <span>{{ $data['jatuh_tempo'] }}</span>
            </div>
        @elseif($data['jenis_trans'] === 'PENCAIRAN SEBELUM TEMPO')
            <div class="flex justify-between text-sm font-bold">
                <span>Pokok Cair</span>
                <span>Rp {{ number_format($data['nominal_awal'], 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-red-600 text-[10px]">
                <span>Bunga Diperoleh</span>
                <span>Rp 0 (Penalti Awal)</span>
            </div>
            <div class="flex justify-between text-[10px] text-gray-500">
                <span>Metode</span>
                <span>Transfer Tabungan</span>
            </div>
        @else
            <div class="flex justify-between text-[10px] text-gray-500">
                <span>Pokok Awal</span>
                <span>Rp {{ number_format($data['nominal_awal'], 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-[10px] text-gray-500">
                <span>Bunga (Gross)</span>
                @php $bungaGross = ($data['nominal_awal'] * ($data['bunga']/100) * ($data['jangka_waktu']/12)); @endphp
                <span>Rp {{ number_format($bungaGross, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-[10px] text-gray-500">
                <span>Pajak (20%)</span>
                <span>Rp {{ number_format($bungaGross * 0.20, 0, ',', '.') }}</span>
            </div>
            <div class="flex justify-between text-sm font-bold">
                <span>Total Dicairkan</span>
                <span>Rp {{ number_format($data['nominal_awal'] + ($bungaGross * 0.80), 0, ',', '.') }}</span>
            </div>
        @endif
    </div>
    
    <div class="text-center pt-2 border-t border-gray-400 text-[10px] text-gray-500 mt-2 leading-tight">
        Dicetak dari {{ $settings->nama_pt }}<br>
        Terima kasih atas kerjasama Anda
    </div>
</div>
