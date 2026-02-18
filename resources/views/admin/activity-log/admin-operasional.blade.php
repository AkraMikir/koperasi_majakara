@extends('layouts.admin')

@section('title', 'Log Aktivitas Admin')

@section('content')
@php
$moduleColors = [
    'tabungan'    => 'bg-[#674c1d]/10 text-[#674c1d] border-[#674c1d]/20',
    'pinjaman'    => 'bg-yellow-50 text-yellow-700 border-yellow-200',
    'nasabah'     => 'bg-green-50 text-green-700 border-green-200',
    'akun'        => 'bg-purple-50 text-purple-700 border-purple-200',
    'master_data' => 'bg-gray-100 text-gray-600 border-gray-200',
];
$moduleLabels = [
    'tabungan'    => 'Tabungan',
    'pinjaman'    => 'Pinjaman',
    'nasabah'     => 'Nasabah',
    'akun'        => 'Akun',
    'master_data' => 'Master Data',
];
$actionColors = [
    'green'  => 'bg-green-50 text-green-700 border-green-200',
    'red'    => 'bg-red-50 text-red-700 border-red-200',
    'yellow' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
    'blue'   => 'bg-blue-50 text-blue-700 border-blue-200',
    'gray'   => 'bg-gray-100 text-gray-600 border-gray-200',
];
$roleColors = [
    'admin_utama'       => 'bg-red-100 text-red-700',
    'admin_operasional' => 'bg-yellow-100 text-yellow-700',
];
$roleLabels = [
    'admin_utama'       => 'Admin Utama',
    'admin_operasional' => 'Admin Operasional',
];
@endphp

<div class="space-y-6">
    {{-- Header --}}
    <div class="flex items-center justify-between">
        <div class="flex items-center gap-3">
            <div class="w-12 h-12 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center shadow-md">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <div>
                <h1 class="text-3xl font-bold text-gray-900 font-display">Log Aktivitas Admin</h1>
                <p class="text-gray-500 mt-0.5">Rekam jejak semua aktivitas Admin Utama & Admin Operasional</p>
            </div>
        </div>
        <a href="{{ route('admin.activity-log.nasabah') }}"
            class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border border-gray-200 rounded-xl text-sm font-medium text-gray-700 hover:bg-gray-50 transition-all shadow-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            Log Nasabah
        </a>
    </div>

    {{-- Statistik Cards --}}
    <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Hari Ini</p>
            <p class="text-3xl font-bold text-[#674c1d]">{{ $stats['total_hari_ini'] }}</p>
            <p class="text-xs text-gray-500 mt-1">aktivitas</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Minggu Ini</p>
            <p class="text-3xl font-bold text-[#8b6f2f]">{{ $stats['total_minggu_ini'] }}</p>
            <p class="text-xs text-gray-500 mt-1">aktivitas</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Bulan Ini</p>
            <p class="text-3xl font-bold text-[#d4af37]">{{ $stats['total_bulan_ini'] }}</p>
            <p class="text-xs text-gray-500 mt-1">aktivitas</p>
        </div>
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-5">
            <p class="text-xs text-gray-400 font-medium uppercase tracking-wider mb-1">Total</p>
            <p class="text-3xl font-bold text-gray-700">{{ $stats['total_keseluruhan'] }}</p>
            <p class="text-xs text-gray-500 mt-1">semua waktu</p>
        </div>
    </div>

    {{-- Filter Panel --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
        <form method="GET" action="{{ route('admin.activity-log.admin-operasional') }}" class="flex flex-wrap gap-3">
            <div class="flex-1 min-w-48">
                <div class="relative">
                    <svg class="absolute left-3 top-1/2 -translate-y-1/2 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama admin atau aktivitas..."
                        class="w-full pl-10 pr-4 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                </div>
            </div>
            <select name="role" class="px-3 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:border-[#674c1d] outline-none bg-white min-w-40">
                <option value="">Semua Admin</option>
                <option value="admin_utama" {{ request('role') === 'admin_utama' ? 'selected' : '' }}>Admin Utama</option>
                <option value="admin_operasional" {{ request('role') === 'admin_operasional' ? 'selected' : '' }}>Admin Operasional</option>
            </select>
            <select name="module" class="px-3 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:border-[#674c1d] outline-none bg-white min-w-36">
                <option value="">Semua Modul</option>
                <option value="tabungan" {{ request('module') === 'tabungan' ? 'selected' : '' }}>Tabungan</option>
                <option value="pinjaman" {{ request('module') === 'pinjaman' ? 'selected' : '' }}>Pinjaman</option>
                <option value="nasabah" {{ request('module') === 'nasabah' ? 'selected' : '' }}>Nasabah</option>
                <option value="master_data" {{ request('module') === 'master_data' ? 'selected' : '' }}>Master Data</option>
            </select>
            <input type="date" name="date_from" value="{{ request('date_from') }}"
                class="px-3 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:border-[#674c1d] outline-none">
            <input type="date" name="date_to" value="{{ request('date_to') }}"
                class="px-3 py-2.5 border-2 border-gray-200 rounded-xl text-sm focus:border-[#674c1d] outline-none">
            <button type="submit"
                class="px-5 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl text-sm font-semibold hover:shadow-md transition-all">
                Filter
            </button>
            @if(request()->hasAny(['search', 'role', 'module', 'action', 'date_from', 'date_to']))
            <a href="{{ route('admin.activity-log.admin-operasional') }}"
                class="px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl text-sm font-semibold hover:bg-gray-200 transition-all">
                Reset
            </a>
            @endif
        </form>
    </div>

    {{-- Tabel Log --}}
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="px-6 py-4 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-base font-bold text-gray-900">Riwayat Aktivitas Admin</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $logs->total() }} aktivitas ditemukan</p>
            </div>
        </div>

        @if($logs->count() > 0)
        <div class="divide-y divide-gray-50">
            @foreach($logs as $log)
            @php
                $moduleColor = $moduleColors[$log->module] ?? 'bg-gray-100 text-gray-600 border-gray-200';
                $moduleLabel = $moduleLabels[$log->module] ?? $log->module;
                $actionColor = $actionColors[$log->action_color] ?? $actionColors['gray'];
                $roleColor = $roleColors[$log->user_role] ?? 'bg-gray-100 text-gray-600';
                $roleLabel = $roleLabels[$log->user_role] ?? $log->user_role;
                $actionLabel = str_replace('_', ' ', $log->action);
            @endphp
            <div class="flex gap-4 px-6 py-4 hover:bg-gray-50/50 transition-colors" x-data="{ open: false }">
                {{-- Avatar --}}
                <div class="flex flex-col items-center gap-2 flex-shrink-0">
                    <div class="w-9 h-9 rounded-full flex items-center justify-center text-white font-bold text-sm flex-shrink-0 {{ $log->user_role === 'admin_utama' ? 'bg-gradient-to-br from-red-500 to-red-600' : 'bg-gradient-to-br from-[#674c1d] to-[#8b6f2f]' }}">
                        {{ strtoupper(substr($log->user_name, 0, 1)) }}
                    </div>
                    <div class="w-px flex-1 bg-gray-100 min-h-4"></div>
                </div>

                {{-- Konten --}}
                <div class="flex-1 pb-2">
                    <div class="flex items-start justify-between gap-3 flex-wrap">
                        <div class="flex-1">
                            <div class="flex items-center gap-2 flex-wrap mb-1">
                                <span class="text-sm font-semibold text-gray-900">{{ $log->user_name }}</span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-semibold {{ $roleColor }}">
                                    {{ $roleLabel }}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium border {{ $moduleColor }}">
                                    {{ $moduleLabel }}
                                </span>
                                <span class="px-2 py-0.5 rounded-full text-xs font-medium border {{ $actionColor }}">
                                    {{ $actionLabel }}
                                </span>
                            </div>
                            <p class="text-sm text-gray-700">{{ $log->description }}</p>

                            {{-- Nasabah yang terdampak (dari properties) --}}
                            @if(!empty($log->properties['nasabah']))
                            <div class="mt-1 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                                <span class="text-xs text-gray-500">Nasabah: <span class="font-medium text-gray-700">{{ $log->properties['nasabah'] }}</span></span>
                            </div>
                            @endif

                            {{-- IP Address --}}
                            @if($log->ip_address)
                            <div class="mt-0.5 flex items-center gap-1.5">
                                <svg class="w-3.5 h-3.5 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 12a9 9 0 01-9 9m9-9a9 9 0 00-9-9m9 9H3m9 9a9 9 0 01-9-9m9 9c1.657 0 3-4.03 3-9s-1.343-9-3-9m0 18c-1.657 0-3-4.03-3-9s1.343-9 3-9"></path>
                                </svg>
                                <span class="text-xs text-gray-400 font-mono">{{ $log->ip_address }}</span>
                            </div>
                            @endif
                        </div>
                        <div class="text-right flex-shrink-0">
                            <p class="text-xs font-medium text-gray-900">{{ $log->created_at->format('H:i') }}</p>
                            <p class="text-xs text-gray-400">{{ $log->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400 mt-0.5">{{ $log->created_at->diffForHumans() }}</p>
                        </div>
                    </div>

                    {{-- Properties (expandable) --}}
                    @php
                        $props = is_array($log->properties) ? $log->properties : (is_string($log->properties) ? json_decode($log->properties, true) : []);
                    @endphp
                    @if(!empty($props))
                    <div class="mt-2">
                        <button type="button" @click="open = !open"
                            class="text-xs text-[#674c1d] hover:underline flex items-center gap-1">
                            <svg class="w-3 h-3 transition-transform" :class="{ 'rotate-90': open }" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                            <span x-text="open ? 'Sembunyikan detail' : 'Lihat detail'"></span>
                        </button>
                        <div x-show="open" x-transition class="mt-2 p-3 bg-gray-50 rounded-xl border border-gray-200 text-xs">
                            <table class="w-full">
                                @foreach($props as $key => $value)
                                <tr>
                                    <td class="py-0.5 pr-3 text-gray-500 font-medium capitalize">{{ str_replace('_', ' ', $key) }}</td>
                                    <td class="py-0.5 text-gray-800">
                                        @if(is_numeric($value) && str_contains($key, 'nominal'))
                                            Rp {{ number_format($value, 0, ',', '.') }}
                                        @else
                                            {{ $value ?? '-' }}
                                        @endif
                                    </td>
                                </tr>
                                @endforeach
                            </table>
                        </div>
                    </div>
                    @endif
                </div>
            </div>
            @endforeach
        </div>

        {{-- Pagination --}}
        @if($logs->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
        @endif

        @else
        {{-- Empty State --}}
        <div class="py-20 text-center">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <p class="text-gray-500 text-lg font-medium">Belum ada log aktivitas admin</p>
            <p class="text-gray-400 text-sm mt-1">Aktivitas admin akan muncul di sini setelah mereka menggunakan sistem</p>
        </div>
        @endif
    </div>
</div>
@endsection
