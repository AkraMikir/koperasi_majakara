@extends('layouts.nasabah')

@section('title', 'Riwayat Deposito')

@section('content')
<div class="w-full pb-6">

    {{-- HEADER --}}
    <div class="bg-gradient-to-br from-[#4a3514] to-[#8b6f2f] px-4 pt-6 pb-12 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-40 h-40 bg-white/10 rounded-full -mr-20 -mt-20"></div>
        <div class="relative z-10">
            <div class="flex items-center gap-3 mb-4">
                <a href="{{ route('nasabah.deposito.index') }}" class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center text-white hover:bg-white/30 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                    </svg>
                </a>
                <h1 class="text-white font-bold text-xl">Riwayat Transaksi Deposito</h1>
            </div>
            <p class="text-white/80 text-sm">Semua riwayat pengajuan dan deposito Anda ada di sini.</p>
        </div>
    </div>

    {{-- LIST MAPPING --}}
    <div class="px-4 -mt-6 relative z-10">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 min-h-[300px]">
            @if($riwayat->isEmpty())
                <div class="flex flex-col items-center justify-center py-10 opacity-60">
                    <svg class="w-16 h-16 text-gray-300 mb-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    <p class="text-sm font-semibold text-gray-800">Belum Ada Transaksi</p>
                    <p class="text-xs text-gray-500 text-center mt-1">Anda belum pernah melakukan pengajuan deposito.</p>
                </div>
            @else
                <div class="space-y-3">
                    @foreach($riwayat as $item)
                        @php
                            // MAPPING STATUS
                            $statusLabel = 'Unknown';
                            $statusClass = 'bg-gray-100 text-gray-700';
                            $iconColorClass = 'text-gray-400';
                            $linkTujuan = null;
                            $depo = $item->deposito;

                            if ($item->status == '1') {
                                $statusLabel = 'Menunggu Persetujuan';
                                $statusClass = 'bg-amber-100 text-amber-700';
                                $iconColorClass = 'text-amber-500';
                                $linkTujuan = route('nasabah.deposito.status-pengajuan', $item->id);
                            } elseif ($item->status == '3') {
                                $statusLabel = 'Ditolak';
                                $statusClass = 'bg-red-100 text-red-700';
                                $iconColorClass = 'text-red-500';
                                $linkTujuan = route('nasabah.deposito.status-pengajuan', $item->id); // to see rejection note
                            } elseif ($item->status == '2') {
                                if ($depo) {
                                    if ($depo->status === 'aktif') {
                                        $statusLabel = 'Aktif Berjalan';
                                        $statusClass = 'bg-green-100 text-green-700';
                                        $iconColorClass = 'text-green-500';
                                        $linkTujuan = route('nasabah.deposito.detail', $depo->id);
                                    } elseif ($depo->status === 'dicairkan') {
                                        $statusLabel = 'Selesai (Dicairkan)';
                                        $statusClass = 'bg-blue-100 text-blue-700';
                                        $iconColorClass = 'text-blue-500';
                                        $linkTujuan = route('nasabah.deposito.detail', $depo->id);
                                    } elseif ($depo->status === 'ditutup') {
                                        $statusLabel = 'Dibatalkan (Ditutup)';
                                        $statusClass = 'bg-red-100 text-red-700';
                                        $iconColorClass = 'text-red-500';
                                        $linkTujuan = route('nasabah.deposito.detail', $depo->id);
                                    } else {
                                        $statusLabel = ucfirst($depo->status);
                                        $statusClass = 'bg-yellow-100 text-yellow-700';
                                        $iconColorClass = 'text-yellow-500';
                                        $linkTujuan = route('nasabah.deposito.detail', $depo->id);
                                    }
                                } else {
                                    // Should rarely happen if DB uses transactions properly, but just in case
                                    $statusLabel = 'Disetujui';
                                    $statusClass = 'bg-green-100 text-green-700';
                                    $iconColorClass = 'text-green-500';
                                    $linkTujuan = route('nasabah.deposito.status-pengajuan', $item->id);
                                }
                            }
                        @endphp
                        
                        <a href="{{ $linkTujuan ?? '#' }}" class="block p-4 rounded-xl border border-gray-100 hover:border-[#674c1d]/30 bg-gray-50/50 hover:bg-[#faf9f0] transition-colors relative group">
                            <div class="flex justify-between items-start mb-2">
                                <div class="flex items-center gap-2">
                                    <h3 class="font-bold text-[#674c1d] text-sm">Deposito {{ $item->tenor?->tenor_bulan ?? '-' }} Bulan</h3>
                                </div>
                                <span class="text-[10px] font-bold px-2 py-0.5 rounded-full {{ $statusClass }}">
                                    {{ $statusLabel }}
                                </span>
                            </div>
                            
                            <div class="flex items-end justify-between mt-3">
                                <div>
                                    <p class="text-xs text-gray-500 mb-0.5">Nominal</p>
                                    <p class="font-bold text-gray-800">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                                </div>
                                <div class="text-right">
                                    <p class="text-[10px] text-gray-400 mb-0.5">Tgl Pengajuan</p>
                                    <p class="text-xs font-semibold text-gray-600">{{ $item->created_at->format('d M Y') }}</p>
                                </div>
                            </div>

                            @if($depo && $depo->status === 'aktif' && $depo->tgl_jatuh_tempo)
                                <div class="mt-3 pt-3 border-t border-gray-200 flex justify-between items-center">
                                    <span class="text-xs text-gray-500 flex items-center gap-1">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                        Jatuh Tempo:
                                    </span>
                                    <span class="text-xs font-bold {{ $depo->tgl_jatuh_tempo->isPast() ? 'text-red-500' : 'text-gray-700' }}">
                                        {{ $depo->tgl_jatuh_tempo->format('d M Y') }}
                                    </span>
                                </div>
                            @endif
                        </a>
                    @endforeach
                </div>

                {{-- Pagination Pagination --}}
                <div class="mt-6 flex justify-center">
                    {{ $riwayat->links('pagination::tailwind') }}
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
