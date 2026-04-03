@extends('layouts.admin')

@section('title', 'Dashboard Operasional Petty Cash')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Dashboard Petty Cash</h1>
            <p class="text-gray-600 mt-1">Saku operasional dan mutasi harian Anda.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
        <div class="bg-gradient-to-br {{ $isLowSaldo ? 'from-red-600 to-red-800' : 'from-green-600 to-green-800' }} rounded-2xl shadow-lg p-6 text-white border {{ $isLowSaldo ? 'border-red-500' : 'border-green-500/30' }}">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </div>
                <span class="px-3 py-1 bg-white/20 text-white rounded-full text-xs font-bold uppercase tracking-wider">{{ $alertMessage }}</span>
            </div>
            <p class="text-white/80 text-sm mb-1 uppercase font-semibold">Saku Cash</p>
            <p class="text-3xl font-bold">Rp {{ number_format($saldoCash, 0, ',', '.') }}</p>
            @if($isLowSaldo)
            <p class="text-xs text-red-100 mt-2 font-medium bg-red-900/50 inline-block px-2 py-1 rounded-md">Saldo di bawah Rp 500.000</p>
            @else
            <p class="text-xs text-green-100 mt-2">Saldo uang fisik di tangan</p>
            @endif
        </div>

        <div class="bg-gradient-to-br from-blue-600 to-blue-800 rounded-2xl shadow-lg p-6 text-white border border-blue-500/30">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"/>
                    </svg>
                </div>
                <span class="text-white/60 text-xs font-medium uppercase tracking-wider">OK</span>
            </div>
            <p class="text-white/80 text-sm mb-1 uppercase font-semibold">Rekening TF</p>
            <p class="text-3xl font-bold">Rp {{ number_format($saldoTransfer, 0, ',', '.') }}</p>
            <p class="text-xs text-blue-100 mt-2">Saldo digital operasional</p>
        </div>
    </div>

    {{-- Setoran Pending Alert --}}
    @if($mySetoranPending > 0)
    <div class="bg-amber-50 border border-amber-200 text-amber-800 px-4 py-4 rounded-xl flex items-center gap-3 shadow-sm">
        <svg class="w-6 h-6 text-amber-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
        </svg>
        <div>
            <p class="font-bold">Setoran Menunggu Verifikasi</p>
            <p class="text-sm">Ada {{ $mySetoranPending }} riwayat setoran Anda hari ini yang sedang menunggu verifikasi Owner.</p>
        </div>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        {{-- Mutasi Terakhir --}}
        <div class="lg:col-span-2 space-y-6">
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
                    <div class="flex items-center space-x-3">
                        <div class="w-10 h-10 bg-gradient-to-br from-gray-600 to-gray-800 rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                            </svg>
                        </div>
                        <h2 class="text-lg font-bold text-gray-800 font-display">Mutasi Terakhir Saya</h2>
                    </div>
                </div>

                <div class="space-y-4">
                    @forelse($mutasiTerakhir as $mutasi)
                    <div class="flex items-center justify-between p-4 rounded-xl border border-gray-100 hover:bg-gray-50 transition-colors shadow-sm">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full flex items-center justify-center {{ $mutasi->mutasi > 0 ? 'bg-green-100 text-green-600' : 'bg-red-100 text-red-600' }}">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $mutasi->mutasi > 0 ? 'M7 11l5-5m0 0l5 5m-5-5v12' : 'M17 13l-5 5m0 0l-5-5m5 5V6' }}"/>
                                </svg>
                            </div>
                            <div>
                                <h3 class="font-bold text-gray-900">{{ $mutasi->keterangan }}</h3>
                                <p class="text-xs text-gray-500 mt-1 flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                                    {{ $mutasi->created_at->format('d M Y, H:i') }} 
                                    <span class="mx-1">•</span>
                                    <span class="uppercase font-semibold {{ $mutasi->tipe == 'cash' ? 'text-green-600' : 'text-blue-600' }}">{{ $mutasi->tipe }}</span>
                                </p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-lg {{ $mutasi->mutasi > 0 ? 'text-green-600' : 'text-red-600' }}">
                                {{ $mutasi->mutasi > 0 ? '+' : '' }}Rp {{ number_format($mutasi->mutasi, 0, ',', '.') }}
                            </p>
                            <p class="text-xs text-gray-400 mt-1">Sisa: Rp {{ number_format($mutasi->saldo_akhir, 0, ',', '.') }}</p>
                        </div>
                    </div>
                    @empty
                    <div class="text-center py-8 text-gray-500 bg-gray-50 rounded-xl border border-dashed border-gray-300">
                        <svg class="w-12 h-12 mx-auto text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                        </svg>
                        <p class="font-medium">Belum ada mutasi tercatat</p>
                        <p class="text-xs mt-1">Mutasi setoran/penarikan Anda akan muncul di sini</p>
                    </div>
                    @endforelse
                </div>
            </div>
        </div>

        {{-- Quick Actions --}}
        <div>
            <div class="bg-gradient-to-b from-gray-50 to-white rounded-2xl shadow-md border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-200 bg-white">
                    <h2 class="font-bold text-gray-800">Aksi Cepat</h2>
                </div>
                <div class="p-2">

                    
                    <a href="{{ route('admin.petty-cash.setoran-kantor.index') }}" class="flex items-center gap-3 p-4 hover:bg-amber-50 rounded-xl transition-colors group">
                        <div class="w-10 h-10 bg-amber-100 text-amber-600 rounded-lg flex items-center justify-center group-hover:scale-110 transition-transform">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 19l9 2-9-18-9 18 9-2zm0 0v-8"/></svg>
                        </div>
                        <div>
                            <p class="font-bold text-gray-800 group-hover:text-amber-700 transition-colors">Setor ke Kantor</p>
                            <p class="text-xs text-gray-500">Serahkan cash/rekap TF</p>
                        </div>
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
