@extends('layouts.admin')

@section('title', 'Manajemen Admin Operasional')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.master-data.index') }}" class="text-gray-400 hover:text-[#674c1d] transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900 font-display">Manajemen Admin Operasional</h1>
            </div>
            <p class="text-gray-600 mt-1 ml-8">Kelola akun-akun Admin Operasional sistem koperasi</p>
        </div>
        <a href="{{ route('admin.master-data.admin-operasional.create') }}"
            class="inline-flex items-center gap-2 px-5 py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold shadow-lg hover:shadow-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
            </svg>
            Tambah Admin Operasional
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 rounded-r-xl p-4 shadow-md flex items-center gap-3">
        <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-green-700 font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 shadow-md flex items-center gap-3">
        <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-red-700 font-semibold">{{ session('error') }}</p>
    </div>
    @endif

    <!-- Stats Summary Cards -->
    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        <div class="bg-white rounded-2xl shadow-md p-5 border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gradient-to-br from-[#674c1d]/20 to-[#8b6f2f]/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-[#674c1d]">{{ $adminList->total() }}</p>
                    <p class="text-sm text-gray-500">Total Admin</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-5 border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-green-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-green-600">{{ \App\Models\AdminOperasional::where('status', 'aktif')->count() }}</p>
                    <p class="text-sm text-gray-500">Admin Aktif</p>
                </div>
            </div>
        </div>
        <div class="bg-white rounded-2xl shadow-md p-5 border border-gray-100">
            <div class="flex items-center gap-4">
                <div class="w-12 h-12 bg-gray-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18.364 18.364A9 9 0 005.636 5.636m12.728 12.728A9 9 0 015.636 5.636m12.728 12.728L5.636 5.636"></path>
                    </svg>
                </div>
                <div>
                    <p class="text-2xl font-bold text-gray-500">{{ \App\Models\AdminOperasional::where('status', 'nonaktif')->count() }}</p>
                    <p class="text-sm text-gray-500">Admin Nonaktif</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Search & Filter -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <form method="GET" action="{{ route('admin.master-data.admin-operasional.index') }}" class="flex flex-wrap gap-3">
            <div class="flex-1 min-w-56">
                <div class="relative">
                    <svg class="absolute left-4 top-1/2 -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                    <input type="text" name="search" value="{{ request('search') }}"
                        placeholder="Cari nama, email, atau nomor HP..."
                        class="w-full pl-12 pr-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-sm">
                </div>
            </div>
            <select name="status"
                class="px-4 py-2.5 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] outline-none text-sm bg-white">
                <option value="">Semua Status</option>
                <option value="aktif" {{ request('status') === 'aktif' ? 'selected' : '' }}>Aktif</option>
                <option value="nonaktif" {{ request('status') === 'nonaktif' ? 'selected' : '' }}>Nonaktif</option>
            </select>
            <button type="submit"
                class="px-6 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:shadow-lg transition-all text-sm">
                Cari
            </button>
            @if(request('search') || request('status'))
            <a href="{{ route('admin.master-data.admin-operasional.index') }}"
                class="px-6 py-2.5 bg-gray-100 text-gray-700 rounded-xl font-semibold hover:bg-gray-200 transition-all text-sm">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Tabel Daftar Admin Operasional -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between">
            <div>
                <h2 class="text-lg font-bold text-gray-900 font-display">Daftar Admin Operasional</h2>
                <p class="text-sm text-gray-500 mt-0.5">{{ $adminList->total() }} akun terdaftar</p>
            </div>
        </div>

        @if($adminList->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white">
                        <th class="px-6 py-4 text-left text-sm font-semibold">No</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Admin</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Email</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">No. HP</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Status</th>
                        <th class="px-6 py-4 text-left text-sm font-semibold">Bergabung</th>
                        <th class="px-6 py-4 text-center text-sm font-semibold">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($adminList as $index => $admin)
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 text-sm text-gray-500 font-medium">
                            {{ $adminList->firstItem() + $index }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                @if($admin->user->foto && $admin->user->foto !== 'default-avatar.jpg')
                                    <img src="{{ asset('storage/' . $admin->user->foto) }}"
                                        alt="Foto"
                                        class="w-10 h-10 rounded-full object-cover border-2 border-[#674c1d]/20">
                                @else
                                    <div class="w-10 h-10 rounded-full bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] flex items-center justify-center text-white font-bold text-sm flex-shrink-0">
                                        {{ strtoupper(substr($admin->user->nama ?? 'A', 0, 1)) }}
                                    </div>
                                @endif
                                <div>
                                    <p class="font-semibold text-gray-900 text-sm">{{ $admin->user->nama ?? 'N/A' }}</p>
                                    <p class="text-xs text-gray-400">ID: {{ $admin->id }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-700">{{ $admin->user->email ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-700 font-mono">{{ $admin->user->nomor_hp ?? 'N/A' }}</p>
                        </td>
                        <td class="px-6 py-4 text-center">
                            <form action="{{ route('admin.master-data.admin-operasional.toggle-status', $admin->id) }}" method="POST" class="inline">
                                @csrf
                                <button type="submit"
                                    onclick="return confirm('Ubah status akun ini?')"
                                    class="px-3 py-1.5 rounded-full text-xs font-semibold transition-all hover:opacity-80 {{ $admin->status === 'aktif' ? 'bg-green-100 text-green-700 border border-green-200' : 'bg-gray-100 text-gray-500 border border-gray-200' }}">
                                    {{ $admin->status === 'aktif' ? 'Aktif' : 'Nonaktif' }}
                                </button>
                            </form>
                        </td>
                        <td class="px-6 py-4">
                            <p class="text-sm text-gray-600">{{ $admin->created_at->format('d M Y') }}</p>
                            <p class="text-xs text-gray-400">{{ $admin->created_at->format('H:i') }}</p>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                @canCrudMasterData
                                <a href="{{ route('admin.master-data.admin-operasional.edit', $admin->id) }}"
                                    class="inline-flex items-center gap-1 px-3 py-1.5 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-all text-xs font-medium">
                                    <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                                    </svg>
                                    Edit
                                </a>
                                <form action="{{ route('admin.master-data.admin-operasional.destroy', $admin->id) }}" method="POST" class="inline"
                                    onsubmit="return confirm('Yakin ingin menghapus akun Admin Operasional ini? Aksi ini tidak dapat dibatalkan.')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit"
                                        class="inline-flex items-center gap-1 px-3 py-1.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-all text-xs font-medium">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                                        </svg>
                                        Hapus
                                    </button>
                                </form>
                                @endcanCrudMasterData
                            </div>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>

        <!-- Pagination -->
        @if($adminList->hasPages())
        <div class="px-6 py-4 border-t border-gray-100">
            {{ $adminList->links() }}
        </div>
        @endif

        @else
        <!-- Empty State -->
        <div class="py-20 text-center">
            <div class="w-20 h-20 bg-gradient-to-br from-[#674c1d]/10 to-[#8b6f2f]/10 rounded-full flex items-center justify-center mx-auto mb-4">
                <svg class="w-10 h-10 text-[#674c1d]/40" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
            </div>
            <p class="text-gray-500 text-lg font-medium">Belum ada Admin Operasional</p>
            <p class="text-gray-400 text-sm mt-1 mb-6">Klik tombol "Tambah Admin Operasional" untuk menambahkan akun baru</p>
            <a href="{{ route('admin.master-data.admin-operasional.create') }}"
                class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:shadow-lg transition-all text-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                </svg>
                Tambah Admin Operasional
            </a>
        </div>
        @endif
    </div>
</div>
@endsection
