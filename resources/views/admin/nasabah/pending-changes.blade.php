@extends('layouts.admin')

@section('title', 'Pengajuan Perubahan Data Nasabah')

@section('content')
<div class="container-fluid">
    <!-- Header -->
    <div class="mb-6">
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-2xl font-bold text-gray-800 mb-1">Pengajuan Perubahan Data Nasabah</h1>
                <p class="text-gray-600 text-sm">Review dan setujui perubahan data yang diajukan nasabah</p>
            </div>
            <a href="{{ route('admin.nasabah.index') }}" class="px-4 py-2 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Kembali
            </a>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-r-lg mb-6 shadow-md">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-green-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-green-800 font-medium">{{ session('success') }}</p>
            </div>
        </div>
    @endif

    @if(session('error'))
        <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg mb-6 shadow-md">
            <div class="flex items-center gap-3">
                <svg class="w-6 h-6 text-red-500 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <p class="text-red-800 font-medium">{{ session('error') }}</p>
            </div>
        </div>
    @endif

    <!-- Main Card -->
    <div class="bg-white rounded-2xl shadow-lg border border-gray-200">
        <div class="p-6 border-b border-gray-200">
            <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-linear-to-br from-yellow-500 to-orange-500 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Daftar Pengajuan Pending</h2>
                    <p class="text-sm text-gray-600">Total: {{ $pendingRequests->total() }} pengajuan</p>
                </div>
            </div>
        </div>

        <div class="p-6">
            @if($pendingRequests->count() > 0)
                <div class="space-y-4">
                    @foreach($pendingRequests as $index => $request)
                    <!-- Pengajuan Card -->
                    <div class="border border-gray-200 rounded-xl hover:shadow-md transition-shadow">
                        <div class="p-5">
                            <div class="flex items-start justify-between mb-4">
                                <div class="flex items-start gap-4">
                                    <!-- Avatar -->
                                    <div class="w-12 h-12 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] rounded-full flex items-center justify-center text-white font-bold text-lg shrink-0">
                                        {{ strtoupper(substr($request->nasabah->user->nama, 0, 1)) }}
                                    </div>
                                    <!-- Info -->
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-lg mb-1">{{ $request->nasabah->user->nama }}</h3>
                                        <div class="flex flex-wrap items-center gap-3 text-sm text-gray-600">
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                                </svg>
                                                {{ $request->nasabah->user->email }}
                                            </span>
                                            <span class="flex items-center gap-1">
                                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                                </svg>
                                                {{ $request->nasabah->user->nomor_hp }}
                                            </span>
                                        </div>
                                        <div class="mt-2 flex items-center gap-2">
                                            <span class="px-3 py-1 bg-blue-100 text-blue-800 text-xs font-semibold rounded-full">
                                                {{ $request->jenis_data_label }}
                                            </span>
                                            <span class="px-3 py-1 bg-yellow-100 text-yellow-800 text-xs font-semibold rounded-full flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                </svg>
                                                {{ $request->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <!-- Action Button -->
                                <button type="button" onclick="openDetailModal('modal{{ $request->id }}')"
                                        class="px-4 py-2 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:shadow-lg transition-all font-medium text-sm flex items-center gap-2 shrink-0">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                                    </svg>
                                    Review
                                </button>
                            </div>

                            <!-- Quick Preview (collapsed) -->
                            <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                                <p class="text-sm text-gray-700 mb-2 font-semibold">Perubahan yang Diajukan:</p>
                                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-3">
                                    @foreach($request->data_baru as $key => $value)
                                        @if(($request->data_lama[$key] ?? '') != $value)
                                        <div class="text-sm">
                                            <span class="text-gray-600">{{ ucwords(str_replace('_', ' ', $key)) }}:</span>
                                            <span class="font-semibold text-[#674c1d] ml-1">{{ $value }}</span>
                                        </div>
                                        @endif
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Modal Detail (Modern Design) -->
                    <div id="modal{{ $request->id }}" class="fixed inset-0 bg-black bg-opacity-60 backdrop-blur-sm hidden z-50 flex items-center justify-center p-4">
                        <div class="bg-white rounded-2xl shadow-2xl max-w-4xl w-full max-h-[90vh] overflow-hidden">
                            <!-- Header -->
                            <div class="bg-linear-to-r from-[#674c1d] to-[#8b6f2f] p-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex items-center gap-3">
                                        <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                                            <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                            </svg>
                                        </div>
                                        <div>
                                            <h3 class="text-xl font-bold text-white">Detail Perubahan Data</h3>
                                            <p class="text-white/80 text-sm">{{ $request->jenis_data_label }}</p>
                                        </div>
                                    </div>
                                    <button onclick="closeDetailModal('modal{{ $request->id }}')" class="text-white hover:bg-white/20 rounded-lg p-2 transition-colors">
                                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <!-- Body -->
                            <div class="p-6 overflow-y-auto" style="max-height: calc(90vh - 250px);">
                                <!-- Nasabah Info -->
                                <div class="bg-linear-to-br from-blue-50 to-indigo-50 rounded-xl p-5 mb-6 border border-blue-200">
                                    <div class="flex items-center gap-4 mb-3">
                                        <div class="w-14 h-14 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] rounded-full flex items-center justify-center text-white font-bold text-xl">
                                            {{ strtoupper(substr($request->nasabah->user->nama, 0, 1)) }}
                                        </div>
                                        <div>
                                            <h4 class="font-bold text-gray-900 text-lg">{{ $request->nasabah->user->nama }}</h4>
                                            <p class="text-sm text-gray-600">ID Nasabah: {{ $request->id_nasabah }}</p>
                                        </div>
                                    </div>
                                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 text-sm">
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                                            </svg>
                                            <span class="text-gray-700">{{ $request->nasabah->user->email }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                            </svg>
                                            <span class="text-gray-700">{{ $request->nasabah->user->nomor_hp }}</span>
                                        </div>
                                        <div class="flex items-center gap-2">
                                            <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <span class="text-gray-700">Diajukan: {{ $request->created_at->format('d M Y, H:i') }}</span>
                                        </div>
                                    </div>
                                </div>

                                <!-- Comparison Table -->
                                <h4 class="font-bold text-gray-800 mb-4 flex items-center gap-2">
                                    <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                    </svg>
                                    Perbandingan Data
                                </h4>

                                <div class="space-y-3">
                                    @foreach($request->data_baru as $key => $value)
                                    @php
                                        $dataLama = $request->data_lama[$key] ?? '-';
                                        $hasChange = $dataLama != $value;
                                    @endphp
                                    <div class="border border-gray-200 rounded-lg overflow-hidden {{ $hasChange ? 'border-yellow-300 bg-yellow-50/30' : '' }}">
                                        <div class="bg-gray-100 px-4 py-2 border-b border-gray-200">
                                            <h5 class="font-semibold text-gray-800 text-sm flex items-center gap-2">
                                                {{ ucwords(str_replace('_', ' ', $key)) }}
                                                @if($hasChange)
                                                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                                    </svg>
                                                @endif
                                            </h5>
                                        </div>
                                        <div class="grid grid-cols-2 divide-x divide-gray-200">
                                            <!-- Data Lama -->
                                            <div class="p-4 bg-red-50/50">
                                                <p class="text-xs text-gray-600 mb-1 font-medium uppercase">Data Lama</p>
                                                <p class="text-gray-700 {{ $hasChange ? 'line-through opacity-60' : '' }}">
                                                    {{ $dataLama }}
                                                </p>
                                            </div>
                                            <!-- Data Baru -->
                                            <div class="p-4 bg-green-50/50">
                                                <p class="text-xs text-gray-600 mb-1 font-medium uppercase">Data Baru</p>
                                                <p class="font-semibold text-[#674c1d]">
                                                    {{ $value ?? '-' }}
                                                    @if($hasChange)
                                                        <svg class="w-4 h-4 inline ml-1 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7l5 5m0 0l-5 5m5-5H6"></path>
                                                        </svg>
                                                    @endif
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                    @endforeach
                                </div>

                                <!-- Catatan Admin -->
                                <div class="mt-6">
                                    <label class="block text-sm font-semibold text-gray-700 mb-2">Catatan Admin (Opsional)</label>
                                    <textarea id="catatan_admin_{{ $request->id }}" class="w-full px-4 py-3 border border-gray-300 rounded-xl focus:ring-2 focus:ring-[#8b6f2f] focus:border-[#8b6f2f] resize-none" rows="3" placeholder="Tambahkan catatan jika diperlukan..."></textarea>
                                </div>
                            </div>

                            <!-- Footer Actions -->
                            <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200">
                                <button type="button" onclick="closeDetailModal('modal{{ $request->id }}')"
                                        class="px-6 py-2.5 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-100 transition-colors font-medium">
                                    Tutup
                                </button>
                                <div class="flex gap-3">
                                    <form action="{{ route('admin.nasabah.reject-change', $request->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="catatan_admin" id="reject_catatan_{{ $request->id }}">
                                        <button type="submit" onclick="return confirmReject({{ $request->id }})"
                                                class="px-6 py-2.5 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors font-medium flex items-center gap-2 shadow-md hover:shadow-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Tolak
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.nasabah.approve-change', $request->id) }}" method="POST" class="inline">
                                        @csrf
                                        <input type="hidden" name="catatan_admin" id="approve_catatan_{{ $request->id }}">
                                        <button type="submit" onclick="return confirmApprove({{ $request->id }})"
                                                class="px-6 py-2.5 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-medium flex items-center gap-2 shadow-md hover:shadow-lg">
                                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                            Setujui
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>

                <!-- Pagination -->
                <div class="mt-6">
                    {{ $pendingRequests->links() }}
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="w-24 h-24 bg-linear-to-br from-blue-100 to-blue-200 rounded-full flex items-center justify-center mx-auto mb-4">
                        <svg class="w-12 h-12 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-2">Tidak Ada Pengajuan Pending</h3>
                    <p class="text-gray-600 mb-4">Saat ini tidak ada pengajuan perubahan data yang menunggu persetujuan.</p>
                    <a href="{{ route('admin.nasabah.index') }}" class="inline-flex items-center gap-2 px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#8b6f2f] transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        Lihat Daftar Nasabah
                    </a>
                </div>
            @endif
        </div>
    </div>
</div>

<script>
// Modal functions
function openDetailModal(modalId) {
    document.getElementById(modalId).classList.remove('hidden');
    document.body.style.overflow = 'hidden';
}

function closeDetailModal(modalId) {
    document.getElementById(modalId).classList.add('hidden');
    document.body.style.overflow = 'auto';
}

// Confirm approve with catatan
function confirmApprove(requestId) {
    const catatan = document.getElementById('catatan_admin_' + requestId).value;
    document.getElementById('approve_catatan_' + requestId).value = catatan;
    return confirm('Apakah Anda yakin ingin MENYETUJUI perubahan data ini?\n\nData akan langsung diupdate di database.');
}

// Confirm reject with catatan
function confirmReject(requestId) {
    const catatan = document.getElementById('catatan_admin_' + requestId).value;
    
    if (!catatan || catatan.trim() === '') {
        const userCatatan = prompt('Berikan alasan penolakan (opsional):');
        if (userCatatan !== null) {
            document.getElementById('reject_catatan_' + requestId).value = userCatatan;
        }
    } else {
        document.getElementById('reject_catatan_' + requestId).value = catatan;
    }
    
    return confirm('Apakah Anda yakin ingin MENOLAK perubahan data ini?');
}

// Close modal when clicking outside
document.addEventListener('DOMContentLoaded', function() {
    @foreach($pendingRequests as $request)
    document.getElementById('modal{{ $request->id }}').addEventListener('click', function(e) {
        if (e.target === this) {
            closeDetailModal('modal{{ $request->id }}');
        }
    });
    @endforeach
});
</script>
@endsection
