@extends('layouts.admin')

@section('title', 'Pengajuan Penarikan Tabungan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl sm:text-3xl font-bold text-gray-900 font-display">Pengajuan Penarikan Tabungan</h1>
            <p class="text-gray-500 mt-1">Kelola pengajuan penarikan tabungan via <strong>Transfer</strong> dari nasabah</p>
        </div>
        <a href="{{ route('admin.tabungan.index') }}" class="inline-flex items-center gap-2 px-4 py-2.5 bg-white border-2 border-gray-200 rounded-xl text-gray-700 hover:border-[#674c1d]/40 hover:bg-[#674c1d]/5 transition-colors text-sm font-semibold">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    <!-- Info: Hanya Transfer (tema selaras, tidak biru mencolok) -->
    <div class="bg-amber-50/80 border border-amber-200/80 rounded-xl p-4 flex items-start gap-3">
        <span class="w-10 h-10 rounded-lg bg-[#674c1d]/10 flex items-center justify-center shrink-0">
            <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
        </span>
        <div>
            <p class="text-sm font-semibold text-gray-800">Hanya Penarikan via Transfer</p>
            <p class="text-sm text-gray-600 mt-0.5">Daftar ini menampilkan pengajuan penarikan dengan metode <strong>Transfer</strong>. Penarikan <strong>Tunai</strong> diproses melalui menu <a href="{{ route('admin.janji-temu.index') }}" class="text-[#674c1d] font-medium hover:underline">Janji Temu</a>.</p>
        </div>
    </div>

    <!-- Filter Section -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <p class="text-xs font-bold text-gray-400 uppercase tracking-widest mb-4">Filter</p>
        <form method="GET" action="{{ route('admin.tabungan.pengajuan-tarik') }}" class="flex flex-col md:flex-row gap-4 items-stretch md:items-center">
            <div class="flex-1">
                <label for="search-tarik" class="block text-sm font-medium text-gray-600 mb-1.5">Cari nasabah</label>
                <input id="search-tarik" type="text" name="search" value="{{ request('search') }}" placeholder="Nama atau email nasabah..."
                    class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d]/30 focus:border-[#674c1d] outline-none transition-colors placeholder:text-gray-400">
            </div>
            <div class="w-full md:w-48">
                <label for="status-tarik" class="block text-sm font-medium text-gray-600 mb-1.5">Status</label>
                <select id="status-tarik" name="status" class="w-full px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d]/30 focus:border-[#674c1d] outline-none transition-colors bg-white">
                    <option value="">Semua Status</option>
                    <option value="1" {{ request('status') == '1' ? 'selected' : '' }}>Pending</option>
                    <option value="2" {{ request('status') == '2' ? 'selected' : '' }}>Disetujui</option>
                    <option value="3" {{ request('status') == '3' ? 'selected' : '' }}>Ditolak</option>
                </select>
            </div>
            <div class="flex gap-2 pt-6 md:pt-8">
                <button type="submit" class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-md font-semibold text-sm">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z"></path></svg>
                    Terapkan
                </button>
                <a href="{{ route('admin.tabungan.pengajuan-tarik') }}" class="inline-flex items-center px-5 py-2.5 bg-gray-100 text-gray-700 rounded-xl hover:bg-gray-200 transition-colors font-medium text-sm">
                    Reset
                </a>
            </div>
        </form>
    </div>

    <!-- Table Section -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="border-b border-gray-200 bg-gray-50/80">
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">ID</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nasabah</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nominal</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Tanggal Pengajuan</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($pengajuan as $item)
                    <tr class="border-b border-gray-100 hover:bg-amber-50/30 transition-colors">
                        <td class="px-6 py-4 text-sm font-medium text-gray-900">#{{ $item->id }}</td>
                        <td class="px-6 py-4">
                            <div>
                                <p class="font-semibold text-gray-900">{{ $item->nasabah->user->nama ?? 'N/A' }}</p>
                                <p class="text-sm text-gray-500">{{ $item->nasabah->user->email ?? '-' }}</p>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-[#674c1d]">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item->created_at->format('d M Y, H:i') }}</td>
                        <td class="px-6 py-4">
                            @php
                                $statusConfig = [
                                    '1' => ['label' => 'Pending', 'bg' => 'bg-amber-100', 'text' => 'text-amber-800'],
                                    '2' => ['label' => 'Disetujui', 'bg' => 'bg-emerald-100', 'text' => 'text-emerald-800'],
                                    '3' => ['label' => 'Ditolak', 'bg' => 'bg-rose-100', 'text' => 'text-rose-800'],
                                ];
                                $status = $statusConfig[$item->status] ?? $statusConfig['1'];
                            @endphp
                            <span class="px-3 py-1 {{ $status['bg'] }} {{ $status['text'] }} rounded-lg text-xs font-semibold">
                                {{ $status['label'] }}
                            </span>
                        </td>
                        <td class="px-6 py-4">
                            <a href="{{ route('admin.tabungan.detail-pengajuan-tarik', $item->id) }}"
                                class="inline-flex items-center gap-2 px-4 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all shadow-sm hover:shadow font-semibold text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                Lihat
                            </a>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-16 text-center">
                            <div class="flex flex-col items-center gap-3">
                                <div class="w-16 h-16 rounded-2xl bg-gray-100 flex items-center justify-center">
                                    <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                    </svg>
                                </div>
                                <p class="text-gray-500 font-medium">Tidak ada pengajuan penarikan</p>
                            </div>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>

        @if($pengajuan->hasPages())
        <div class="px-6 py-4 border-t border-gray-200 bg-gray-50/50">
            {{ $pengajuan->links() }}
        </div>
        @endif
    </div>
</div>
@endsection
