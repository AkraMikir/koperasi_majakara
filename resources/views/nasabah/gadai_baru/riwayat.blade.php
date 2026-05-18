@extends('layouts.nasabah')

@section('title', 'Riwayat Gadai')

@section('content')
<div class="w-full pb-6 space-y-8">
    <!-- Active Gadai Section -->
    <div class="mx-4 mt-6">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-black text-gray-900 tracking-tight font-display">Gadai Aktif</h2>
            @if($gadaiAktif->isNotEmpty())
                <span class="px-3 py-1 bg-amber-100 text-[#674c1d] text-[10px] font-black rounded-full uppercase tracking-widest border border-amber-200">
                    {{ $gadaiAktif->count() }} Aktif
                </span>
            @endif
        </div>
        
        @if($gadaiAktif->isEmpty())
            <div class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 text-center flex flex-col items-center gap-4">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <div>
                    <p class="text-gray-400 font-bold tracking-tight mb-1">Tidak ada gadai aktif</p>
                    <a href="{{ route('nasabah.gadai_baru.index') }}" class="text-[#674c1d] text-sm font-black hover:underline underline-offset-4">Ajukan Gadai Baru →</a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($gadaiAktif as $gadai)
                @php
                    $isTenggang = $gadai->status == 'grace_period';
                @endphp
                <div class="bg-white rounded-3xl p-6 shadow-xl border-2 {{ $isTenggang ? 'border-red-500 bg-red-50/30' : 'border-gray-50' }} transition-all hover:scale-[1.02] duration-300 relative overflow-hidden group">
                    <!-- Decor -->
                    <div class="absolute -right-4 -bottom-4 opacity-5 group-hover:rotate-12 transition-transform duration-500">
                        <svg class="w-32 h-32" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>

                    <div class="flex justify-between items-start mb-6">
                        <div>
                            <div class="flex items-center gap-2 mb-1">
                                <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $gadai->kategori->nama_kategori }}</span>
                                <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                <span class="text-[9px] font-black text-[#674c1d] uppercase tracking-widest">Slot {{ $gadai->slot_kode }}</span>
                            </div>
                            <a href="{{ route('nasabah.gadai_baru.aktif-detail', $gadai->id) }}" class="group/title flex items-center gap-1.5">
                                <h3 class="font-black text-gray-900 text-xl tracking-tight leading-none group-hover/title:text-[#674c1d] transition-colors">{{ $gadai->item->head_1 ?? $gadai->item->nama_item }}</h3>
                                <svg class="w-4 h-4 text-gray-400 group-hover/title:text-[#674c1d] group-hover/title:translate-x-0.5 transition-all shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                            @if($gadai->item->head_2)
                                <p class="text-[10px] text-gray-400 mt-1 italic">{{ $gadai->item->head_2 }}</p>
                            @endif
                        </div>
                        <div class="flex flex-col items-end gap-2 shrink-0">
                            @if($isTenggang)
                                <span class="bg-red-600 text-white text-[9px] font-black px-3 py-1 rounded-full shadow-lg shadow-red-200">TENGGANG</span>
                            @else
                                <span class="bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white text-[9px] font-black px-3 py-1 rounded-full shadow-lg shadow-amber-200">AKTIF</span>
                            @endif
                            <a href="{{ route('nasabah.gadai_baru.aktif-detail', $gadai->id) }}" class="text-[9px] font-black text-[#674c1d] uppercase tracking-widest hover:underline flex items-center gap-1">
                                Rincian
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                            </a>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6 relative z-10">
                        <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-3 border border-gray-100">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Nilai Gadai</p>
                            <p class="font-black text-gray-900 text-lg tracking-tighter">Rp {{ number_format($gadai->nominal_deal, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-white/50 backdrop-blur-sm rounded-2xl p-3 border border-gray-100">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-1">Jatuh Tempo</p>
                            <p class="font-black {{ $isTenggang ? 'text-red-600' : 'text-gray-900' }} text-lg tracking-tighter">{{ $gadai->tgl_jatuh_tempo->format('d M Y') }}</p>
                        </div>
                    </div>
                    
                    <div class="space-y-3 bg-gray-50/80 backdrop-blur-sm rounded-2xl p-4 border border-gray-100 text-xs relative z-10">
                        <div class="flex justify-between items-center">
                            <span class="font-bold text-gray-500">Biaya Jasa:</span>
                            <span class="font-black text-gray-900">Rp {{ number_format($gadai->biaya_jasa, 0, ',', '.') }}</span>
                        </div>
                        @if($gadai->denda_aktif > 0)
                        <div class="flex justify-between items-center text-red-600">
                            <span class="font-bold">Denda:</span>
                            <span class="font-black">Rp {{ number_format($gadai->denda_aktif, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($gadai->biaya_inap > 0)
                        <div class="flex justify-between items-center text-amber-700">
                            <span class="font-bold">Biaya Inap:</span>
                            <span class="font-black">Rp {{ number_format($gadai->biaya_inap, 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>
                    
                    <div class="mt-6 flex gap-2 relative z-10">
                        <a href="{{ route('nasabah.gadai_baru.aktif-detail', $gadai->id) }}" class="flex-1 text-center bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white font-black py-3 px-4 rounded-2xl transition-all shadow-xl shadow-amber-100 active:scale-95 text-xs">Lihat Rincian &amp; Kelola</a>
                        <a href="https://wa.me/628139552626?text=Halo%20Admin,%20saya%20ingin%20info%20gadai%20{{$gadai->slot_kode}}" target="_blank" class="w-12 h-12 flex items-center justify-center bg-gray-100 hover:bg-gray-200 text-gray-800 rounded-2xl transition-all active:scale-95">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 12h.01M12 12h.01M16 12h.01M21 12c0 4.418-4.03 8-9 8a9.863 9.863 0 01-4.255-.949L3 20l1.395-3.72C3.512 15.042 3 13.574 3 12c0-4.418 4.03-8 9-8s9 3.582 9 8z"></path></svg>
                        </a>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    <!-- Completed Gadai Section -->
    <div class="mx-4 pb-10">
        <h2 class="text-xl font-black text-gray-900 tracking-tight font-display mb-4">Riwayat Selesai</h2>
        
        @if($gadaiSelesai->isEmpty())
            <div class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 text-center text-gray-400 font-bold tracking-tight">
                Belum ada riwayat gadai yang selesai.
            </div>
        @else
            <div class="bg-white rounded-3xl shadow-xl border border-gray-50 overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="w-full text-left">
                        <thead>
                            <tr class="bg-gray-50/50 border-b border-gray-100">
                                <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest">Item & Kategori</th>
                                <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Periode</th>
                                <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-right">Nominal</th>
                                <th class="px-6 py-4 text-[9px] font-black text-gray-400 uppercase tracking-widest text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-100">
                            @foreach($gadaiSelesai as $selesai)
                            <tr class="hover:bg-gray-50/30 transition-colors">
                                <td class="px-6 py-4">
                                    <div class="font-black text-gray-900 tracking-tight leading-none mb-1">{{ $selesai->item->head_1 ?? $selesai->item->nama_item }}</div>
                                    <div class="text-[10px] font-bold text-gray-400 uppercase tracking-widest">{{ $selesai->kategori->nama_kategori }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    <div class="text-[10px] font-bold text-gray-500">{{ $selesai->tgl_mulai->format('d M Y') }}</div>
                                    <div class="text-[9px] text-gray-300">s/d {{ $selesai->updated_at->format('d M Y') }}</div>
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <div class="font-black text-gray-900 tracking-tighter">Rp {{ number_format($selesai->nominal_deal, 0, ',', '.') }}</div>
                                </td>
                                <td class="px-6 py-4 text-center">
                                    @php
                                        $color = match($selesai->status) {
                                            'lunas' => 'bg-green-100 text-green-700',
                                            'auctioned' => 'bg-amber-100 text-amber-700',
                                            'returned' => 'bg-gray-100 text-gray-700',
                                            default => 'bg-gray-100 text-gray-500'
                                        };
                                        $label = match($selesai->status) {
                                            'lunas' => 'LUNAS',
                                            'auctioned' => 'LELANG',
                                            'returned' => 'KEMBALI',
                                            default => strtoupper($selesai->status)
                                        };
                                    @endphp
                                    <span class="px-3 py-1 rounded-lg text-[9px] font-black {{ $color }}">{{ $label }}</span>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        @endif
    </div>
</div>
@endsection
