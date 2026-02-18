@extends('layouts.admin')

@section('title', 'Manajemen Nasabah')

@section('content')
<div class="space-y-6">
    <!-- Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Manajemen Nasabah</h1>
            <p class="text-gray-600 mt-1">Kelola data nasabah koperasi</p>
        </div>
        <a href="{{ route('admin.nasabah.pending-changes') }}" class="px-5 py-3 bg-linear-to-r from-yellow-500 to-yellow-600 text-white rounded-xl font-semibold shadow-lg hover:shadow-xl transition-all flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            Pengajuan Pending
            @if($pendingChangesCount > 0)
                <span class="px-2 py-1 bg-white text-yellow-600 rounded-full text-xs font-bold">{{ $pendingChangesCount }}</span>
            @endif
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 rounded-r-xl p-4 shadow-md">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-green-700 font-semibold">{{ session('success') }}</p>
        </div>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 shadow-md">
        <div class="flex items-center gap-3">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <p class="text-red-700 font-semibold">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Search & Filter -->
    <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.nasabah.index') }}" class="flex gap-4">
            <div class="flex-1">
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari berdasarkan nama, email, atau nomor HP..."
                        class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                </div>
            </div>
            <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:shadow-lg transition-all">
                Cari
            </button>
            @if(request('search'))
            <a href="{{ route('admin.nasabah.index') }}" class="px-6 py-3 bg-gray-200 text-gray-700 rounded-xl font-semibold hover:bg-gray-300 transition-all">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Daftar Nasabah -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-200">
            <h2 class="text-xl font-bold text-gray-900 font-display">Daftar Nasabah ({{ $nasabahList->total() }})</h2>
        </div>

        @if($nasabahList->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white">
                    <tr>
                        <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Foto</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Nama</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">No. HP</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">NIK</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Status</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @foreach($nasabahList as $index => $nasabah)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $nasabahList->firstItem() + $index }}</td>
                        <td class="px-6 py-4">
                            @if($nasabah->user->foto && $nasabah->user->foto !== 'default-avatar.jpg')
                                <img src="{{ asset('storage/' . $nasabah->user->foto) }}" alt="Foto" class="w-12 h-12 rounded-full object-cover border-2 border-[#674c1d]/20">
                            @else
                                <div class="w-12 h-12 rounded-full bg-linear-to-br from-[#674c1d] to-[#8b6f2f] flex items-center justify-center text-white font-bold text-lg">
                                    {{ strtoupper(substr($nasabah->user->nama ?? 'N', 0, 1)) }}
                                </div>
                            @endif
                        </td>
                        <td class="px-6 py-4">
                            <p class="font-semibold text-gray-900">{{ $nasabah->user->nama ?? 'N/A' }}</p>
                            <p class="text-xs text-gray-500">ID: {{ $nasabah->id }}</p>
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-700">{{ $nasabah->user->email ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 font-mono">{{ $nasabah->user->nomor_hp ?? 'N/A' }}</td>
                        <td class="px-6 py-4 text-sm text-gray-700 font-mono">{{ $nasabah->dataKtp->nik ?? 'N/A' }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-xs font-semibold">Aktif</span>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <a href="{{ route('admin.nasabah.show', $nasabah->id) }}" class="inline-flex items-center gap-1 px-4 py-2 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg font-semibold hover:shadow-lg transition-all text-sm">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                </svg>
                                Detail
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        <div class="px-6 py-4 border-t border-gray-200">
            {{ $nasabahList->links() }}
        </div>
        @else
        <div class="p-12 text-center">
            <svg class="w-20 h-20 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
            </svg>
            <p class="text-gray-500 text-lg">Tidak ada data nasabah</p>
        </div>
        @endif
    </div>
</div>
@endsection
