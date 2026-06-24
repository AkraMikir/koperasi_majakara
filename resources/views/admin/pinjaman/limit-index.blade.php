@extends('layouts.admin')

@section('title', 'Monitoring Limit Pinjaman Nasabah')

@section('content')
<div class="container mx-auto px-4 py-6">
    <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6 gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-display">Monitoring Limit Pinjaman</h1>
            <p class="text-sm text-gray-500">Pantau dan kelola limit pinjaman masing-masing nasabah</p>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="mb-4">
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
            </svg>
            <p class="font-semibold">{{ session('success') }}</p>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="mb-4">
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md flex items-center">
            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
            </svg>
            <p class="font-semibold">{{ session('error') }}</p>
        </div>
    </div>
    @endif

    <!-- Search Card -->
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 mb-6">
        <form method="GET" action="{{ route('admin.pinjaman.limit.index') }}" class="flex flex-col md:flex-row gap-4">
            <div class="flex-1 relative">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                    </svg>
                </span>
                <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari nama nasabah atau email..."
                    class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#8b6f2f]/20 focus:border-[#8b6f2f] outline-none">
            </div>
            <button type="submit" class="px-6 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white font-bold rounded-xl shadow hover:shadow-md transition-all">
                Cari
            </button>
            @if(request('search'))
            <a href="{{ route('admin.pinjaman.limit.index') }}" class="px-6 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl text-center hover:bg-gray-200 transition-all">
                Reset
            </a>
            @endif
        </form>
    </div>

    <!-- Table Card -->
    <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-bold text-gray-500 uppercase tracking-wider">Nasabah</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Limit Nominal</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Nominal Terpakai</th>
                        <th class="px-6 py-4 text-right text-xs font-bold text-gray-500 uppercase tracking-wider">Sisa Limit</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Progress Terpakai</th>
                        <th class="px-6 py-4 text-center text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @forelse($nasabahs as $nasabah)
                    @php
                        $limit = $nasabah->limitPinjaman;
                        $limitNominal = $limit ? (float) $limit->limit_nominal : 1000000.00;
                        $nominalTerpakai = $limit ? (float) $limit->nominal_terpakai : 0.00;
                        $sisaLimit = max(0, $limitNominal - $nominalTerpakai);
                        $percent = $limitNominal > 0 ? min(100, ($nominalTerpakai / $limitNominal) * 100) : 0;
                    @endphp
                    <tr class="hover:bg-gray-50/50 transition-colors">
                        <td class="px-6 py-4">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-linear-to-br from-[#8b6f2f] to-[#d4af37] text-white rounded-full flex items-center justify-center font-bold">
                                    {{ strtoupper(substr($nasabah->user->nama ?? 'N', 0, 1)) }}
                                </div>
                                <div>
                                    <p class="font-bold text-gray-900">{{ $nasabah->user->nama ?? '-' }}</p>
                                    <p class="text-xs text-gray-500">{{ $nasabah->user->email ?? '-' }}</p>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-gray-900">
                            Rp {{ number_format($limitNominal, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-red-600">
                            Rp {{ number_format($nominalTerpakai, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4 text-right font-semibold text-green-600">
                            Rp {{ number_format($sisaLimit, 0, ',', '.') }}
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex flex-col items-center w-full max-w-[150px] mx-auto">
                                <div class="w-full bg-gray-100 rounded-full h-2">
                                    <div class="h-2 rounded-full @if($percent > 80) bg-red-500 @elseif($percent > 50) bg-amber-500 @else bg-green-500 @endif" style="width: {{ $percent }}%"></div>
                                </div>
                                <span class="text-xs text-gray-500 mt-1 font-semibold">{{ round($percent, 0) }}%</span>
                            </div>
                        </td>
                        <td class="px-6 py-4">
                            <div class="flex items-center justify-center gap-2">
                                <button type="button" onclick="openEditLimitModal({{ $nasabah->id }}, '{{ addslashes($nasabah->user->nama ?? '') }}', {{ $limitNominal }})"
                                    class="px-3 py-1.5 bg-amber-50 text-amber-700 border border-amber-200 text-xs font-bold rounded-lg hover:bg-amber-100 transition-all">
                                    Edit Limit
                                </button>
                                <a href="{{ route('admin.pinjaman.limit.logs', $nasabah->id) }}"
                                    class="px-3 py-1.5 bg-gray-50 text-gray-700 border border-gray-200 text-xs font-bold rounded-lg hover:bg-gray-100 transition-all">
                                    Log
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="6" class="px-6 py-12 text-center text-gray-500">Tidak ada data nasabah ditemukan</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($nasabahs->hasPages())
        <div class="p-6 border-t border-gray-100">
            {{ $nasabahs->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Edit Limit Modal -->
<div id="edit-limit-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-6">
            <h3 class="text-lg font-bold text-gray-900 font-display">Edit Limit Pinjaman</h3>
            <button type="button" onclick="closeEditLimitModal()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <form id="edit-limit-form" method="POST" action="" class="space-y-4">
            @csrf
            <div>
                <label class="block text-sm font-semibold text-gray-500 mb-1">Nama Nasabah</label>
                <p id="modal-nasabah-nama" class="font-bold text-gray-900 text-base"></p>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Limit Nominal Baru *</label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                    <input type="number" name="limit_nominal" id="modal-limit-input" required min="0" step="1000"
                        class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#8b6f2f]/20 focus:border-[#8b6f2f] outline-none font-bold text-lg">
                </div>
            </div>

            <div>
                <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan Perubahan *</label>
                <textarea name="keterangan" id="modal-keterangan-input" required rows="3" placeholder="Masukkan alasan penyesuaian limit..."
                    class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#8b6f2f]/20 focus:border-[#8b6f2f] outline-none resize-none"></textarea>
            </div>

            <div class="pt-4 flex gap-3">
                <button type="button" onclick="closeEditLimitModal()" class="flex-1 py-3 bg-gray-100 text-gray-700 font-bold rounded-xl hover:bg-gray-200 transition-all text-center">Batal</button>
                <button type="submit" class="flex-1 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white font-bold rounded-xl shadow hover:shadow-md transition-all text-center">Simpan</button>
            </div>
        </form>
    </div>
</div>

<script>
function openEditLimitModal(id, nama, limit) {
    const modal = document.getElementById('edit-limit-modal');
    const form = document.getElementById('edit-limit-form');
    
    // Set action URL dinamis
    form.action = `/admin/pinjaman/limit/${id}/update`;
    
    document.getElementById('modal-nasabah-nama').textContent = nama;
    document.getElementById('modal-limit-input').value = limit;
    document.getElementById('modal-keterangan-input').value = '';
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
}

function closeEditLimitModal() {
    const modal = document.getElementById('edit-limit-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
}
</script>
@endsection
