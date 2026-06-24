@extends('layouts.admin')

@section('title', 'Log Perubahan Limit Pinjaman')

@section('content')
<div class="container mx-auto px-4 py-6">
    <!-- Back Button -->
    <div class="mb-4">
        <a href="{{ route('admin.pinjaman.limit.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-xl shadow hover:shadow-md transition-all text-gray-700 hover:text-[#674c1d]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-display">Log Perubahan Limit Pinjaman</h1>
            <p class="text-sm text-gray-500">Nasabah: <span class="font-bold text-gray-800">{{ $nasabah->user->nama ?? '-' }}</span> ({{ $nasabah->user->email ?? '-' }})</p>
        </div>
    </div>

    <!-- Summary Info Card -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <p class="text-sm text-gray-500 font-medium">Limit Nominal Saat Ini</p>
            <p class="text-2xl font-bold text-gray-900">Rp {{ number_format($nasabah->limitPinjaman->limit_nominal ?? 1000000.00, 0, ',', '.') }}</p>
        </div>
        <div>
            <p class="text-sm text-gray-500 font-medium">Nominal Terpakai Saat Ini</p>
            <p class="text-2xl font-bold text-red-600">Rp {{ number_format($nasabah->limitPinjaman->nominal_terpakai ?? 0, 0, ',', '.') }}</p>
        </div>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal Perubahan</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Admin Pengubah</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Limit Sebelum</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Limit Sesudah</th>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Keterangan</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($logs as $log)
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-950 font-medium">
                            {{ $log->created_at->format('d M Y, H:i') }} WIB
                        </td>
                        <td class="px-6 py-4">
                            <div class="text-sm">
                                <p class="font-bold text-gray-900">{{ $log->admin->nama ?? '-' }}</p>
                                <p class="text-xs text-gray-500 uppercase">{{ $log->admin->role ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-gray-500">
                            Rp {{ number_format($log->limit_sebelum, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right font-bold text-gray-900">
                            Rp {{ number_format($log->limit_sesudah, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600 max-w-xs truncate" title="{{ $log->keterangan }}">
                            {{ $log->keterangan ?? '-' }}
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">Belum ada riwayat perubahan limit pinjaman</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($logs->hasPages())
        <div class="p-6 border-t border-gray-100">
            {{ $logs->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
