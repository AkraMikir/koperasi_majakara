@extends('layouts.admin')

@section('title', 'Dashboard Deposito')

@section('content')
<div class="p-6">
    {{-- Header --}}
    <div class="flex items-center justify-between mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Dashboard Deposito</h1>
            <p class="text-sm text-gray-500">Kelola pengajuan dan deposito aktif nasabah</p>
        </div>
        <a href="{{ route('admin.deposito.pengajuan-list') }}" class="inline-flex items-center gap-2 bg-[#674c1d] text-white px-4 py-2 rounded-lg text-sm font-semibold hover:bg-[#8b6f2f] transition">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/></svg>
            Semua Pengajuan
        </a>
    </div>

    {{-- Stats cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 mb-1">Pengajuan Pending</p>
            <p class="text-2xl font-bold text-amber-600">{{ $stats['pengajuan_pending'] }}</p>
            <p class="text-xs text-gray-400 mt-1">{{ $stats['pending_transfer'] }} transfer · {{ $stats['pending_tabungan'] }} tabungan</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 mb-1">Disetujui</p>
            <p class="text-2xl font-bold text-green-600">{{ $stats['pengajuan_approved'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 mb-1">Ditolak</p>
            <p class="text-2xl font-bold text-red-500">{{ $stats['pengajuan_rejected'] }}</p>
        </div>
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm p-4">
            <p class="text-xs text-gray-500 mb-1">Deposito Aktif</p>
            <p class="text-2xl font-bold text-[#674c1d]">{{ $stats['total_deposito_aktif'] }}</p>
            <p class="text-xs text-gray-400 mt-1">Rp {{ number_format($stats['total_nominal_aktif'], 0, ',', '.') }}</p>
        </div>
    </div>

    <div class="grid md:grid-cols-2 gap-6">
        {{-- Pengajuan Pending --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-sm">Pengajuan Pending</h2>
                <a href="{{ route('admin.deposito.pengajuan-list') }}" class="text-xs text-[#674c1d] font-semibold hover:underline">Lihat semua →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($pengajuan_terbaru as $p)
                <a href="{{ route('admin.deposito.detail-pengajuan', $p->id) }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition">
                    <div class="w-9 h-9 rounded-full bg-amber-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm truncate">{{ $p->nasabah->user->nama ?? '-' }}</p>
                        <p class="text-xs text-gray-500">Rp {{ number_format($p->nominal, 0, ',', '.') }} · {{ $p->tenor->tenor_bulan ?? '-' }} bulan</p>
                    </div>
                    <span class="text-xs px-2 py-0.5 rounded-full {{ $p->metode_setor === 'transfer' ? 'bg-blue-50 text-blue-600' : 'bg-green-50 text-green-700' }}">
                        {{ $p->metode_setor === 'transfer' ? 'Transfer' : 'Tabungan' }}
                    </span>
                </a>
                @empty
                <div class="px-5 py-8 text-center text-gray-400 text-sm">Tidak ada pengajuan pending</div>
                @endforelse
            </div>
        </div>

        {{-- Deposito Aktif Terbaru --}}
        <div class="bg-white rounded-xl border border-gray-100 shadow-sm">
            <div class="flex items-center justify-between px-5 py-4 border-b border-gray-100">
                <h2 class="font-bold text-gray-800 text-sm">Deposito Aktif Terbaru</h2>
                <a href="{{ route('admin.deposito.deposito-list') }}" class="text-xs text-[#674c1d] font-semibold hover:underline">Lihat semua →</a>
            </div>
            <div class="divide-y divide-gray-50">
                @forelse($deposito_terbaru as $d)
                <a href="{{ route('admin.deposito.deposito-detail', $d->id) }}" class="flex items-center gap-3 px-5 py-3 hover:bg-gray-50 transition">
                    <div class="w-9 h-9 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                        <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/></svg>
                    </div>
                    <div class="flex-1 min-w-0">
                        <p class="font-semibold text-gray-800 text-sm truncate">{{ $d->nasabah->user->nama ?? '-' }}</p>
                        <p class="text-xs text-gray-500">{{ $d->nomor_deposito }} · Rp {{ number_format($d->nominal_awal, 0, ',', '.') }}</p>
                    </div>
                    <p class="text-xs text-gray-400">{{ $d->tgl_jatuh_tempo ? $d->tgl_jatuh_tempo->format('d M Y') : '-' }}</p>
                </a>
                @empty
                <div class="px-5 py-8 text-center text-gray-400 text-sm">Belum ada deposito aktif</div>
                @endforelse
            </div>
        </div>
    </div>
</div>
@endsection
