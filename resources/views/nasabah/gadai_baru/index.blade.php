@extends('layouts.nasabah')

@section('title', 'Layanan Gadai')

@section('content')
<div class="w-full pb-10">

    {{-- Header Premium --}}
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-gradient-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-3xl shadow-2xl p-7 border border-[#d4af37]/30 relative overflow-hidden">
            <div class="absolute inset-0 opacity-10">
                <div class="absolute top-0 right-0 w-64 h-64 bg-white rounded-full -mr-32 -mt-32"></div>
                <div class="absolute bottom-0 left-0 w-48 h-48 bg-white rounded-full -ml-24 -mb-24"></div>
            </div>
            <div class="relative z-10 flex items-center justify-between gap-4">
                <div>
                    <p class="text-white/80 text-xs font-black uppercase tracking-widest mb-1">Layanan Cepat & Aman</p>
                    <h1 class="text-3xl font-black text-white font-display leading-tight">Gadai Majakara</h1>
                    <p class="text-white/70 text-sm mt-1.5 max-w-sm">Solusi pendanaan cepat dengan jaminan barang berharga Anda.</p>
                </div>
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center border border-white/30 shrink-0">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                </div>
            </div>
        </div>
    </div>

    {{-- Session Alerts --}}
    @foreach(['success' => 'green', 'warning' => 'amber', 'error' => 'red'] as $type => $color)
    @if(session($type))
    <div class="mx-4 mb-4">
        <div class="bg-{{ $color }}-50 border border-{{ $color }}-200 rounded-2xl p-4 text-{{ $color }}-700 text-sm font-bold shadow-sm">{{ session($type) }}</div>
    </div>
    @endif
    @endforeach

    {{-- ===== GADAI AKTIF ===== --}}
    <div class="mx-4 mb-8">
        <div class="flex items-center justify-between mb-4">
            <h2 class="text-xl font-black text-gray-900 tracking-tight font-display">Gadai Aktif Saya</h2>
            @if($gadaiAktif->isNotEmpty())
                <span class="px-3 py-1 bg-amber-100 text-[#674c1d] text-[10px] font-black rounded-full border border-amber-200">{{ $gadaiAktif->count() }} Aktif</span>
            @endif
        </div>

        @if($gadaiAktif->isEmpty())
            <div class="bg-white rounded-3xl p-10 shadow-sm border border-gray-100 text-center flex flex-col items-center gap-4">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center text-gray-300">
                    <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"/></svg>
                </div>
                <div>
                    <p class="text-gray-500 font-bold">Tidak ada gadai aktif.</p>
                    <p class="text-xs text-gray-400 mt-1">Pilih kategori di bawah untuk pengajuan baru.</p>
                </div>
            </div>
        @else
            <div class="grid grid-cols-1 md:grid-cols-2 gap-5">
                @foreach($gadaiAktif as $gadai)
                @php
                    $isTenggang = $gadai->status == 'grace_period';
                    $today = now()->startOfDay();
                    $jatuhTempo = \Carbon\Carbon::parse($gadai->tgl_jatuh_tempo)->startOfDay();
                    $tenggang   = \Carbon\Carbon::parse($gadai->tgl_tenggang)->startOfDay();
                    $sisaHari   = (int) ($isTenggang ? $today->diffInDays($tenggang, false) : $today->diffInDays($jatuhTempo, false));
                    $totalTagihan   = $gadai->nominal_deal + $gadai->biaya_jasa + $gadai->biaya_inap + ($gadai->extra_pinjaman_nominal ?? 0);
                    $totalPerpanjang = $gadai->biaya_jasa + $gadai->denda_aktif + $gadai->biaya_inap;
                    // Progress bar
                    $mulai = \Carbon\Carbon::parse($gadai->tgl_mulai)->startOfDay();
                    $totalDays = max(1, (int) $mulai->diffInDays($jatuhTempo));
                    $elapsedDays = min($totalDays, (int) $mulai->diffInDays($today));
                    $progressPct = round(($elapsedDays / $totalDays) * 100);
                    $progressColor = $progressPct >= 90 ? 'from-red-500 to-red-600' : ($progressPct >= 70 ? 'from-amber-400 to-amber-500' : 'from-emerald-400 to-emerald-500');
                    // Format helper
                    $formatSisa = function($hari) {
                        $abs = abs((int) $hari);
                        $bulan = (int) floor($abs / 30);
                        $sisa  = $abs % 30;
                        if ($bulan > 0 && $sisa > 0) return "{$bulan} bulan {$sisa} hari";
                        if ($bulan > 0) return "{$bulan} bulan";
                        return "{$sisa} hari";
                    };
                @endphp
                <div class="bg-white rounded-3xl shadow-md border {{ $isTenggang ? 'border-red-200' : 'border-gray-100' }} relative overflow-hidden group hover:shadow-xl transition-all duration-300">
                    {{-- Urgency ribbon --}}
                    @if($isTenggang)
                    <div class="bg-red-600 text-white text-[10px] font-black text-center py-1.5 tracking-widest uppercase">
                        ⚠ MASA TENGGANG &mdash; {{ $sisaHari >= 0 ? $formatSisa($sisaHari) : '0 hari' }} TERSISA
                    </div>
                    @elseif($sisaHari <= 7 && $sisaHari >= 0)
                    <div class="bg-amber-500 text-white text-[10px] font-black text-center py-1.5 tracking-widest uppercase">
                        ⚡ Jatuh tempo dalam {{ $formatSisa($sisaHari) }} &mdash; segera tindak lanjuti
                    </div>
                    @endif

                    <div class="p-6">
                        {{-- Header card --}}
                        <div class="flex justify-between items-start mb-4">
                            <div>
                                <div class="flex items-center gap-2 mb-1">
                                    <span class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $gadai->kategori->nama_kategori }}</span>
                                    <span class="w-1 h-1 rounded-full bg-gray-300"></span>
                                    <span class="text-[9px] font-black text-[#674c1d] uppercase tracking-widest font-mono">{{ $gadai->slot_kode }}</span>
                                </div>
                                <a href="{{ route('nasabah.gadai_baru.aktif-detail', $gadai->id) }}" class="group/t flex items-center gap-1.5">
                                    <h3 class="font-black text-gray-900 text-lg leading-tight group-hover/t:text-[#674c1d] transition-colors">{{ $gadai->item->head_1 ?? $gadai->item->nama_item }}</h3>
                                    <svg class="w-4 h-4 text-gray-400 group-hover/t:text-[#674c1d] group-hover/t:translate-x-0.5 transition-all shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                                </a>
                                @if($gadai->item->head_2)
                                    <p class="text-[10px] text-gray-400 mt-1 italic">{{ $gadai->item->head_2 }}</p>
                                @endif
                            </div>
                            <span class="{{ $isTenggang ? 'bg-red-600 shadow-red-200' : 'bg-[#674c1d] shadow-amber-200' }} text-white text-[9px] font-black px-3 py-1 rounded-full shadow-lg shrink-0">
                                {{ $isTenggang ? 'TENGGANG' : 'AKTIF' }}
                            </span>
                        </div>

                        {{-- Stats --}}
                        <div class="grid grid-cols-2 gap-3 mb-4">
                            <div class="bg-gray-50 rounded-2xl p-3 border border-gray-100">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">Nilai Gadai</p>
                                <p class="font-black text-gray-900 text-base tracking-tight">Rp {{ number_format($gadai->nominal_deal, 0, ',', '.') }}</p>
                            </div>
                            <div class="bg-gray-50 rounded-2xl p-3 border border-gray-100">
                                <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest mb-0.5">{{ $isTenggang ? 'Batas Tenggang' : 'Jatuh Tempo' }}</p>
                                <p class="font-black {{ $isTenggang ? 'text-red-600' : 'text-gray-900' }} text-base tracking-tight">{{ ($isTenggang ? $gadai->tgl_tenggang : $gadai->tgl_jatuh_tempo)->format('d M Y') }}</p>
                            </div>
                        </div>

                        {{-- Progress timeline --}}
                        <div class="mb-5">
                            <div class="flex justify-between text-[9px] font-bold text-gray-400 mb-1.5">
                                <span>{{ \Carbon\Carbon::parse($gadai->tgl_mulai)->format('d M Y') }}</span>
                                <span class="{{ $progressPct >= 80 ? 'text-red-500' : 'text-gray-400' }}">{{ $progressPct }}% berlalu</span>
                                <span>{{ $jatuhTempo->format('d M Y') }}</span>
                            </div>
                            <div class="w-full bg-gray-100 rounded-full h-2.5 overflow-hidden">
                                <div class="h-2.5 rounded-full bg-gradient-to-r {{ $progressColor }} transition-all duration-700" style="width: {{ min(100, $progressPct) }}%"></div>
                            </div>
                        </div>

                        {{-- CTA Buttons --}}
                        <div class="grid grid-cols-2 gap-3">
                            <a href="{{ route('nasabah.gadai_baru.create-pengajuan', ['id' => $gadai->id, 'jenis' => 'lunas']) }}"
                               class="flex items-center justify-center gap-2 py-3.5 bg-emerald-600 hover:bg-emerald-700 text-white font-black rounded-2xl text-xs uppercase tracking-widest shadow-md shadow-emerald-600/20 active:scale-95 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                Tebus
                            </a>
                            @if($gadai->jumlah_perpanjangan < $gadai->kategori->max_extend_default)
                            <a href="{{ route('nasabah.gadai_baru.create-pengajuan', ['id' => $gadai->id, 'jenis' => 'perpanjang']) }}"
                               class="flex items-center justify-center gap-2 py-3.5 bg-amber-500 hover:bg-amber-600 text-white font-black rounded-2xl text-xs uppercase tracking-widest shadow-md shadow-amber-500/20 active:scale-95 transition-all">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                                Perpanjang
                            </a>
                            @else
                            <div class="flex flex-col items-center justify-center py-3.5 bg-gray-100 border border-gray-200 rounded-2xl opacity-60 cursor-not-allowed">
                                <span class="text-[10px] font-black text-gray-500 uppercase tracking-widest text-center">Batas Maks. Perpanjangan</span>
                            </div>
                            @endif
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>

    {{-- ===== RIWAYAT ===== --}}
    <div class="mx-4 mb-10 grid grid-cols-1 lg:grid-cols-2 gap-6">
        <div class="bg-white rounded-3xl p-6 shadow-md border border-gray-50">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-black text-gray-900 tracking-tight">Riwayat Pengajuan</h3>
                <a href="{{ route('nasabah.gadai_baru.status-pengajuan') }}" class="text-[10px] font-black text-[#674c1d] uppercase tracking-widest hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                @forelse($pengajuanLunas->merge($pengajuanPerpanjang)->sortByDesc('created_at')->take(5) as $pengajuan)
                <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl flex items-center justify-center shrink-0 {{ $pengajuan->jenis_pengajuan == 'lunas' ? 'bg-emerald-100 text-emerald-600' : 'bg-amber-100 text-amber-600' }}">
                            @if($pengajuan->jenis_pengajuan == 'lunas')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                            @else
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"/></svg>
                            @endif
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $pengajuan->jenis_pengajuan }}</p>
                            <p class="text-xs font-bold text-gray-900 leading-tight">{{ $pengajuan->gadaiActive->item->nama_item ?? 'Gadai Item' }}</p>
                            <p class="text-[9px] text-gray-400">{{ $pengajuan->created_at->format('d M H:i') }}</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-xs font-black text-gray-900">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p>
                        <span class="text-[9px] font-black uppercase {{ $pengajuan->status == 'approved' ? 'text-emerald-600' : ($pengajuan->status == 'pending' ? 'text-amber-600' : 'text-red-600') }}">{{ $pengajuan->status }}</span>
                    </div>
                </div>
                @empty
                    <p class="text-center py-10 text-[10px] font-black text-gray-300 uppercase tracking-widest">Belum ada aktivitas pengajuan.</p>
                @endforelse
            </div>
        </div>

        <div class="bg-white rounded-3xl p-6 shadow-md border border-gray-50">
            <div class="flex items-center justify-between mb-5">
                <h3 class="text-base font-black text-gray-900 tracking-tight">Riwayat Gadai Selesai</h3>
                <a href="{{ route('nasabah.gadai_baru.riwayat') }}" class="text-[10px] font-black text-[#674c1d] uppercase tracking-widest hover:underline">Lihat Semua</a>
            </div>
            <div class="space-y-3">
                @forelse($gadaiSelesai as $selesai)
                <div class="flex items-center justify-between p-3 rounded-2xl bg-gray-50 border border-gray-100 hover:bg-gray-100 transition-colors">
                    <div class="flex items-center gap-3">
                        <div class="w-9 h-9 rounded-xl bg-gray-100 text-gray-400 flex items-center justify-center shrink-0">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"/></svg>
                        </div>
                        <div>
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">{{ $selesai->status }}</p>
                            <p class="text-xs font-bold text-gray-900 leading-tight">{{ $selesai->item->nama_item }}</p>
                            <p class="text-[9px] text-gray-400">{{ $selesai->updated_at->format('d M Y') }}</p>
                        </div>
                    </div>
                    <div class="text-right shrink-0">
                        <p class="text-xs font-black text-gray-900">Rp {{ number_format($selesai->nominal_deal, 0, ',', '.') }}</p>
                        <span class="text-[9px] font-black uppercase text-gray-400">SELESAI</span>
                    </div>
                </div>
                @empty
                    <p class="text-center py-10 text-[10px] font-black text-gray-300 uppercase tracking-widest">Belum ada riwayat gadai selesai.</p>
                @endforelse
            </div>
        </div>
    </div>

    {{-- ===== AJUKAN GADAI BARU ===== --}}
    <div class="mx-4 space-y-6">
        <div class="flex items-center gap-4">
            <div class="h-px flex-1 bg-gradient-to-r from-transparent to-gray-200"></div>
            <h2 class="text-xs font-black text-gray-400 uppercase tracking-[0.3em]">Ajukan Gadai Baru</h2>
            <div class="h-px flex-1 bg-gradient-to-l from-transparent to-gray-200"></div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            @php
                $katConfig = [
                    'Emas'       => ['var' => $kategoriEmas,       'color' => 'amber',  'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                    'Elektronik' => ['var' => $kategoriElektronik, 'color' => 'blue',   'icon' => 'M9.75 17L9 20l-1 1h8l-1-1-.75-3M3 13h18M5 17h14a2 2 0 002-2V5a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z'],
                    'Kendaraan'  => ['var' => $kategoriKendaraan,  'color' => 'green',  'icon' => 'M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4'],
                ];
            @endphp
            @foreach($katConfig as $label => $cfg)
            @if($cfg['var'])
            <div class="space-y-3">
                <div class="flex items-center gap-2 px-1">
                    <div class="w-7 h-7 bg-{{ $cfg['color'] }}-100 text-{{ $cfg['color'] }}-600 rounded-lg flex items-center justify-center">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cfg['icon'] }}"/></svg>
                    </div>
                    <h3 class="text-xs font-black text-gray-700 uppercase tracking-widest">{{ $cfg['var']->nama_kategori }}</h3>
                    <span class="text-[9px] text-gray-400 font-bold">• {{ $cfg['var']->items->where('is_active', true)->count() }} item</span>
                </div>
                @foreach($cfg['var']->items->where('is_active', true) as $item)
                <a href="{{ route('nasabah.gadai_baru.show', ['kategori' => $cfg['var']->kode_kategori, 'item' => $item->id]) }}"
                   class="block bg-white p-4 rounded-3xl shadow-sm border border-gray-100 hover:border-{{ $cfg['color'] }}-300 hover:shadow-md transition-all group">
                    <div class="flex items-center gap-4">
                        <div class="w-14 h-14 rounded-2xl bg-gray-50 flex-shrink-0 overflow-hidden border border-gray-100 group-hover:border-{{ $cfg['color'] }}-200 transition-colors">
                            @if($item->file_pic)
                                <img src="{{ asset('storage/' . $item->file_pic) }}" class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-500">
                            @else
                                <div class="w-full h-full flex items-center justify-center text-gray-300">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $cfg['icon'] }}"/></svg>
                                </div>
                            @endif
                        </div>
                        <div class="min-w-0 flex-1">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-widest">Taksiran s/d</p>
                            <p class="text-sm font-black text-gray-900 tracking-tight">Rp {{ number_format($item->nominal_high, 0, ',', '.') }}</p>
                            <p class="text-xs font-bold text-{{ $cfg['color'] }}-600 truncate mt-0.5">{{ $item->head_1 }}</p>
                            @if($item->head_2)
                                <p class="text-[9px] text-gray-400 truncate italic">{{ $item->head_2 }}</p>
                            @endif
                        </div>
                        <svg class="w-4 h-4 text-gray-300 group-hover:text-{{ $cfg['color'] }}-400 group-hover:translate-x-0.5 transition-all shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/></svg>
                    </div>
                </a>
                @endforeach
            </div>
            @endif
            @endforeach
        </div>
    </div>
</div>
@endsection
