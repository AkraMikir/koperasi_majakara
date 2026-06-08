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
            <div class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 text-center flex flex-col items-center gap-4 relative overflow-hidden">
                <div class="absolute inset-0 bg-gradient-to-b from-gray-50 to-white -z-10"></div>
                <div class="w-20 h-20 bg-white shadow-lg rounded-full flex items-center justify-center border border-gray-100">
                    <svg class="w-10 h-10 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                </div>
                <div>
                    <p class="text-gray-900 font-bold tracking-tight mb-1">Tidak ada gadai aktif saat ini</p>
                    <a href="{{ route('nasabah.gadai_baru.index') }}" class="text-[#674c1d] text-sm font-black hover:text-[#d4af37] transition-colors underline-offset-4 flex items-center justify-center gap-1 mt-2">
                        Ajukan Gadai Baru 
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                @foreach($gadaiAktif as $gadai)
                @php
                    $isTenggang = $gadai->status == 'grace_period';
                @endphp
                <div class="bg-white rounded-3xl p-6 shadow-xl border border-gray-100 {{ $isTenggang ? 'ring-2 ring-red-500 bg-red-50/10' : '' }} transition-all hover:-translate-y-1 hover:shadow-2xl duration-300 relative overflow-hidden group">
                    <!-- Decor -->
                    <div class="absolute -right-6 -bottom-6 opacity-[0.03] group-hover:rotate-12 group-hover:scale-110 transition-transform duration-500">
                        <svg class="w-40 h-40" fill="currentColor" viewBox="0 0 24 24"><path d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                    </div>

                    @if($isTenggang)
                        <!-- Urgency Ribbon -->
                        <div class="absolute top-0 right-0 overflow-hidden w-28 h-28">
                            <div class="bg-gradient-to-r from-red-600 to-red-500 text-white text-[10px] font-black uppercase tracking-widest text-center py-1 absolute top-5 -right-8 w-36 rotate-45 shadow-lg">
                                MASA TENGGANG
                            </div>
                        </div>
                    @endif

                    <div class="flex justify-between items-start mb-6">
                        <div class="pr-12">
                            <div class="flex items-center gap-2 mb-2">
                                <span class="bg-gray-100 text-gray-500 px-2 py-0.5 rounded text-[9px] font-black uppercase tracking-widest">{{ $gadai->kategori->nama_kategori }}</span>
                                <span class="w-1 h-1 rounded-full bg-[#d4af37]"></span>
                                <span class="text-[9px] font-black text-[#674c1d] uppercase tracking-widest">SLOT {{ $gadai->slot_kode }}</span>
                            </div>
                            <a href="{{ route('nasabah.gadai_baru.aktif-detail', $gadai->id) }}" class="group/title flex items-center gap-1.5">
                                <h3 class="font-black text-gray-900 text-xl tracking-tight leading-tight group-hover/title:text-[#674c1d] transition-colors line-clamp-1">{{ $gadai->item->head_1 ?? $gadai->item->nama_item }}</h3>
                            </a>
                            @if($gadai->item->head_2)
                                <p class="text-xs text-gray-400 mt-1 font-medium">{{ $gadai->item->head_2 }}</p>
                            @endif
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6 relative z-10">
                        <div class="bg-gray-50/80 rounded-2xl p-3 border border-gray-100 group-hover:bg-amber-50/30 transition-colors">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                Nilai Gadai
                            </p>
                            <p class="font-black text-[#674c1d] text-lg tracking-tight">Rp {{ number_format($gadai->nominal_deal, 0, ',', '.') }}</p>
                        </div>
                        <div class="bg-gray-50/80 rounded-2xl p-3 border border-gray-100 group-hover:bg-amber-50/30 transition-colors">
                            <p class="text-[10px] font-bold text-gray-400 uppercase tracking-widest mb-1 flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                Jatuh Tempo
                            </p>
                            <p class="font-black {{ $isTenggang ? 'text-red-600' : 'text-gray-900' }} text-lg tracking-tight">{{ $gadai->tgl_jatuh_tempo->format('d M') }}</p>
                        </div>
                    </div>
                    
                    <div class="mt-6 flex gap-3 relative z-10">
                        <a href="{{ route('nasabah.gadai_baru.aktif-detail', $gadai->id) }}" class="flex-1 flex justify-center items-center gap-2 bg-gradient-to-r from-[#4a3614] via-[#674c1d] to-[#8b6f2f] hover:from-[#3a2a0f] hover:to-[#674c1d] text-white font-bold py-3 px-4 rounded-xl transition-all shadow-lg shadow-[#d4af37]/20 hover:shadow-xl active:scale-95 text-sm">
                            Lihat Rincian
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                        </a>
                        <a href="https://wa.me/628139552626?text=Halo%20Admin,%20saya%20ingin%20info%20gadai%20{{$gadai->slot_kode}}" target="_blank" class="w-12 h-12 flex items-center justify-center bg-gray-50 hover:bg-[#25D366] hover:text-white text-gray-500 border border-gray-200 rounded-xl transition-all active:scale-95 group/wa">
                            <svg class="w-5 h-5 group-hover/wa:scale-110 transition-transform" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" /></svg>
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
            <div class="space-y-4">
                @foreach($gadaiSelesai as $selesai)
                    @php
                        $colorClass = match($selesai->status) {
                            'lunas' => 'from-green-50 to-emerald-50 border-green-100',
                            'auctioned' => 'from-amber-50 to-orange-50 border-amber-100',
                            'returned' => 'from-gray-50 to-slate-50 border-gray-100',
                            default => 'from-gray-50 to-slate-50 border-gray-100'
                        };
                        
                        $iconColor = match($selesai->status) {
                            'lunas' => 'text-green-600 bg-green-100',
                            'auctioned' => 'text-amber-600 bg-amber-100',
                            'returned' => 'text-gray-600 bg-gray-200',
                            default => 'text-gray-600 bg-gray-200'
                        };
                        
                        $badge = match($selesai->status) {
                            'lunas' => '<span class="px-2.5 py-1 text-[10px] font-black text-green-700 bg-green-100 rounded-lg">LUNAS</span>',
                            'auctioned' => '<span class="px-2.5 py-1 text-[10px] font-black text-amber-700 bg-amber-100 rounded-lg">LELANG</span>',
                            'returned' => '<span class="px-2.5 py-1 text-[10px] font-black text-gray-700 bg-gray-200 rounded-lg">KEMBALI</span>',
                            default => '<span class="px-2.5 py-1 text-[10px] font-black text-gray-600 bg-gray-100 rounded-lg">'.strtoupper($selesai->status).'</span>'
                        };
                    @endphp
                    
                    <div class="bg-gradient-to-r {{ $colorClass }} rounded-2xl p-4 sm:p-5 shadow-sm border hover:shadow-md transition-shadow group relative overflow-hidden flex flex-col sm:flex-row gap-4 sm:items-center">
                        <!-- Icon -->
                        <div class="w-12 h-12 rounded-xl {{ $iconColor }} flex items-center justify-center shrink-0">
                            @if($selesai->status == 'lunas')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path></svg>
                            @elseif($selesai->status == 'auctioned')
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 11V9a2 2 0 00-2-2m2 4v4a2 2 0 104 0v-1m-4-3H9m2 0h4m6 1a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            @else
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h10a8 8 0 018 8v2M3 10l6 6m-6-6l6-6"></path></svg>
                            @endif
                        </div>
                        
                        <!-- Content -->
                        <div class="flex-1">
                            <div class="flex items-start justify-between sm:justify-start gap-3 mb-1">
                                <h4 class="font-black text-gray-900 text-base sm:text-lg tracking-tight leading-none group-hover:text-[#674c1d] transition-colors">{{ $selesai->item->head_1 ?? $selesai->item->nama_item }}</h4>
                                <div class="sm:hidden">{!! $badge !!}</div>
                            </div>
                            <div class="flex flex-wrap items-center gap-x-3 gap-y-1 mt-1.5">
                                <span class="text-[10px] font-bold text-gray-500 uppercase tracking-widest">{{ $selesai->kategori->nama_kategori }}</span>
                                <span class="hidden sm:inline text-gray-300">&bull;</span>
                                <span class="text-[11px] font-medium text-gray-500 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                    {{ $selesai->tgl_mulai->format('d M y') }} - {{ $selesai->updated_at->format('d M y') }}
                                </span>
                            </div>
                        </div>
                        
                        <!-- Right Value & Status -->
                        <div class="flex flex-row sm:flex-col items-center sm:items-end justify-between sm:justify-center border-t sm:border-t-0 sm:border-l border-gray-200/60 pt-3 sm:pt-0 sm:pl-5 sm:min-w-[120px]">
                            <div class="font-black text-[#674c1d] text-lg tracking-tighter">Rp {{ number_format($selesai->nominal_deal, 0, ',', '.') }}</div>
                            <div class="hidden sm:block mt-1">{!! $badge !!}</div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</div>
@endsection
