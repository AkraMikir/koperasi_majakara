@extends('layouts.nasabah')

@section('title', 'Status Pengajuan Setoran')

@section('content')
<div class="w-full pb-6">
    <!-- Header Section -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-linear-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-3xl shadow-2xl p-6 border-2 border-[#d4af37]/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white font-display mb-1">Status Pengajuan Setoran</h1>
                        <p class="text-white/90 text-sm">Lihat status pengajuan setoran Anda</p>
                    </div>
                </div>
                <a href="{{ route('nasabah.tabungan.index') }}" 
                    class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition-all text-sm font-semibold flex items-center gap-2">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                    </svg>
                    Kembali
                </a>
            </div>
        </div>
    </div>

    <!-- Notifikasi sukses / error setelah submit -->
    @if(session('success'))
    <div class="mx-4 mb-6">
        <div class="relative overflow-hidden bg-white rounded-2xl shadow-md border border-emerald-100 p-5 flex items-start gap-4">
            <div class="absolute inset-0 bg-gradient-to-br from-emerald-50/80 to-teal-50/50 pointer-events-none"></div>
            <div class="absolute top-0 right-0 w-32 h-32 bg-[#d4af37]/10 rounded-full -translate-y-1/2 translate-x-1/2 pointer-events-none"></div>
            <div class="relative flex-shrink-0 w-14 h-14 bg-gradient-to-br from-emerald-400 to-teal-500 rounded-2xl flex items-center justify-center shadow-lg shadow-emerald-200/50">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="relative flex-1 min-w-0">
                <p class="font-bold text-emerald-800 text-lg mb-1">Berhasil!</p>
                <p class="text-gray-700 text-sm leading-relaxed">{{ session('success') }}</p>
                <p class="text-gray-500 text-xs mt-2.5 flex items-center gap-1.5">
                    <span class="w-1.5 h-1.5 rounded-full bg-emerald-400"></span>
                    Anda bisa cek status pengajuan di bawah. Admin akan memproses segera.
                </p>
            </div>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="mx-4 mb-6">
        <div class="relative overflow-hidden bg-white rounded-2xl shadow-md border border-red-100 p-5 flex items-start gap-4">
            <div class="absolute inset-0 bg-gradient-to-br from-red-50/60 to-rose-50/40 pointer-events-none"></div>
            <div class="relative flex-shrink-0 w-14 h-14 bg-gradient-to-br from-red-400 to-rose-500 rounded-2xl flex items-center justify-center shadow-lg shadow-red-200/40">
                <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="relative flex-1 min-w-0">
                <p class="font-bold text-red-800 text-lg mb-1">Perhatian</p>
                <p class="text-gray-700 text-sm">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    <!-- Filters Section -->
    <div class="mx-4 mb-6 -mt-2">
        <div class="flex items-center gap-3 overflow-x-auto pb-4 no-scrollbar">
            @php
                $filters = [
                    null => ['label' => 'Semua', 'icon' => 'M4 6h16M4 12h16M4 18h16'],
                    '1' => ['label' => 'Menunggu', 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                    '2' => ['label' => 'Disetujui', 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                    '3' => ['label' => 'Dibatalkan', 'icon' => 'M10 14l2-2m0 0l2-2m-2 2l-2-2m2 2l2 2m7-2a9 9 0 11-18 0 9 9 0 0118 0z'],
                ];
            @endphp
            @foreach($filters as $val => $f)
                <a href="{{ route('nasabah.tabungan.status-pengajuan-setor', ['status' => $val]) }}"
                    class="flex-shrink-0 flex items-center gap-2 px-4 sm:px-5 py-2 sm:py-2.5 rounded-2xl text-sm font-bold transition-all border-2
                    {{ ($currentStatus ?? null) == $val 
                        ? 'bg-[#674c1d] text-white border-[#674c1d] shadow-xl shadow-[#674c1d]/20' 
                        : 'bg-white text-gray-500 border-gray-100 hover:border-[#674c1d]/30 hover:text-[#674c1d] shadow-sm' }}">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="{{ $f['icon'] }}"></path>
                    </svg>
                    {{ $f['label'] }}
                </a>
            @endforeach
        </div>
    </div>

    <!-- List Pengajuan -->
    <div class="mx-4 mb-6">
        <div class="space-y-4">
            @forelse($pengajuan ?? [] as $item)
            @php
                if ($item->status == '2') {
                    $statusLabel = 'Disetujui';
                    $statusClass = 'bg-emerald-100 text-emerald-800 border-emerald-200';
                    $dotClass = 'bg-emerald-500';
                    $borderClass = 'border-l-emerald-500';
                } elseif ($item->status == '3') {
                    $statusLabel = 'Dibatalkan';
                    $statusClass = 'bg-rose-100 text-rose-800 border-rose-200';
                    $dotClass = 'bg-rose-500';
                    $borderClass = 'border-l-rose-500';
                } else {
                    $statusLabel = 'Menunggu';
                    $statusClass = 'bg-amber-100 text-amber-800 border-amber-200';
                    $dotClass = 'bg-amber-500';
                    $borderClass = 'border-l-amber-500';
                }
            @endphp
            <div class="group bg-white rounded-3xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-300 border-l-4 {{ $borderClass }}">
                <div class="p-6">
                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4 mb-6">
                        <div class="flex items-center gap-3">
                            <div class="w-12 h-12 bg-gray-50 rounded-2xl flex items-center justify-center text-[#674c1d]">
                                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-xs font-bold text-gray-400 uppercase tracking-widest">ID Pengajuan</p>
                                <div class="flex items-center gap-2">
                                    <p class="text-lg font-extrabold text-[#674c1d]">#{{ $item->id }}</p>
                                    <span class="px-2 py-0.5 bg-blue-50 text-blue-600 rounded-md text-[10px] font-bold uppercase tracking-tighter">Via Transfer</span>
                                </div>
                            </div>
                        </div>
                        <div class="flex items-center {{ $statusClass }} px-4 py-1.5 rounded-full border text-xs font-bold uppercase tracking-wider gap-2 w-fit">
                            <span class="relative flex h-2 w-2">
                                <span class="animate-ping absolute inline-flex h-full w-full rounded-full {{ $dotClass }} opacity-75"></span>
                                <span class="relative inline-flex rounded-full h-2 w-2 {{ $dotClass }}"></span>
                            </span>
                            {{ $statusLabel }}
                        </div>
                    </div>

                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-6 mb-6">
                        <div class="space-y-1">
                            <p class="text-xs font-bold text-gray-400 uppercase">Tanggal Pengajuan</p>
                            <p class="font-bold text-gray-900">{{ $item->created_at->format('d M Y, H:i') }} WIB</p>
                        </div>
                        <div class="space-y-1">
                            <p class="text-xs font-bold text-gray-400 uppercase">Total Nominal</p>
                            <p class="font-extrabold text-[#674c1d] text-xl">Rp {{ number_format($item->nominal ?? 0, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    @if($item->keterangan)
                    <div class="mb-6 p-4 bg-gray-50 rounded-2xl border border-gray-100">
                        <p class="text-xs font-bold text-gray-400 uppercase mb-2 mr-2">Keterangan Anda</p>
                        <p class="text-sm text-gray-700 leading-relaxed font-medium italic">"{{ $item->keterangan }}"</p>
                    </div>
                    @endif

                    @if($item->keterangan_admin)
                    <div class="mb-6 p-4 {{ $item->status == '2' ? 'bg-emerald-50 border border-emerald-100' : 'bg-rose-50 border border-rose-100' }} rounded-2xl">
                        <div class="flex items-start gap-3">
                            <div class="p-1.5 {{ $item->status == '2' ? 'bg-emerald-100' : 'bg-rose-100' }} rounded-lg shrink-0">
                                <svg class="w-4 h-4 {{ $item->status == '2' ? 'text-emerald-600' : 'text-rose-600' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div class="flex-1">
                                <p class="text-xs font-extrabold {{ $item->status == '2' ? 'text-emerald-700' : 'text-rose-700' }} uppercase tracking-wider mb-1">Catatan Admin</p>
                                <p class="text-sm {{ $item->status == '2' ? 'text-emerald-900' : 'text-rose-900' }} font-bold">{{ $item->keterangan_admin }}</p>
                            </div>
                        </div>
                    </div>
                    @endif

                    <div class="flex items-center gap-3">
                        <a href="{{ route('nasabah.tabungan.detail-pengajuan-setor', $item->id) }}" 
                            class="flex-1 py-3.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-2xl font-bold text-sm hover:translate-y-[-2px] hover:shadow-lg transition-all duration-300 flex items-center justify-center gap-2">
                            <span>Lihat Detail Pengajuan</span>
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                            </svg>
                        </a>
                        @if($item->status == '1')
                        <button type="button" 
                            onclick="openCancelModal('{{ route('nasabah.tabungan.cancel-pengajuan-setor', $item->id) }}', 'Pengajuan Setoran #{{ $item->id }}')"
                            class="w-full sm:w-fit px-8 py-3.5 bg-rose-50 text-rose-600 rounded-2xl font-bold text-sm hover:bg-rose-100 transition-all">
                            Batalkan
                        </button>
                        @endif
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-white rounded-3xl shadow-sm border border-gray-100 p-8 sm:p-16 text-center">
                <div class="w-20 h-20 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-6">
                    <svg class="w-10 h-10 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-2">Tidak Ada Data</h3>
                <p class="text-gray-500 mb-8 max-w-sm mx-auto font-medium">
                    Kami tidak menemukan data pengajuan setoran untuk kategori 
                    <span class="text-[#674c1d] font-bold">"{{ $filters[$currentStatus ?? null]['label'] }}"</span>.
                </p>
                @if(!($currentStatus ?? null))
                <a href="{{ route('nasabah.tabungan.nabung-sekarang') }}" 
                    class="inline-flex items-center gap-2 px-8 py-3.5 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-2xl font-bold hover:translate-y-[-2px] hover:shadow-lg transition-all">
                    <span>Buat Pengajuan Sekarang</span>
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                    </svg>
                </a>
                @else
                <a href="{{ route('nasabah.tabungan.status-pengajuan-setor') }}" 
                    class="inline-flex items-center gap-2 px-8 py-3.5 bg-gray-100 text-gray-700 rounded-2xl font-bold hover:bg-gray-200 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
                    </svg>
                    <span>Lihat Semua Status</span>
                </a>
                @endif
            </div>
            @endforelse
        </div>

        @if($pengajuan && $pengajuan->hasPages())
        <div class="mt-6">
            {{ $pengajuan->links() }}
        </div>
        @endif
    </div>
</div>

<!-- Modal Pembatalan -->
<div id="cancel-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all scale-95 opacity-0 duration-300" id="cancel-modal-content">
        <div class="p-8">
            <div class="w-20 h-20 bg-rose-50 rounded-2xl flex items-center justify-center mx-auto mb-6">
                <svg class="w-10 h-10 text-rose-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            
            <h3 class="text-2xl font-extrabold text-gray-900 text-center mb-2">Konfirmasi Pembatalan</h3>
            <p class="text-gray-500 text-center text-sm mb-8" id="cancel-modal-description">Apakah Anda yakin ingin membatalkan item ini?</p>

            <form id="cancel-form" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Ketik "SETUJU" untuk konfirmasi</label>
                    <input type="text" id="confirm-text" placeholder="SETUJU" 
                        class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-100 rounded-xl focus:border-rose-500 focus:ring-4 focus:ring-rose-500/10 outline-none transition-all font-bold text-center">
                </div>

                <div>
                    <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Masukkan PIN 6 Digit</label>
                    <input type="password" name="pin" id="pin-input" maxlength="6" placeholder="••••••" 
                        class="w-full px-4 py-3 bg-gray-50 border-2 border-gray-100 rounded-xl focus:border-[#674c1d] focus:ring-4 focus:ring-[#674c1d]/10 outline-none transition-all font-mono text-center text-2xl tracking-[0.5em]">
                </div>

                <div class="flex gap-3 pt-4">
                    <button type="button" onclick="closeCancelModal()" class="flex-1 py-4 bg-gray-100 text-gray-600 rounded-2xl font-bold hover:bg-gray-200 transition-all">
                        Batal
                    </button>
                    <button type="submit" id="submit-btn" disabled class="flex-1 py-4 bg-rose-500 text-white rounded-2xl font-bold shadow-lg shadow-rose-200 opacity-50 cursor-not-allowed transition-all hover:bg-rose-600">
                        Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
    const modal = document.getElementById('cancel-modal');
    const modalContent = document.getElementById('cancel-modal-content');
    const confirmInput = document.getElementById('confirm-text');
    const pinInput = document.getElementById('pin-input');
    const submitBtn = document.getElementById('submit-btn');
    const cancelForm = document.getElementById('cancel-form');

    function openCancelModal(action, title) {
        cancelForm.action = action;
        document.getElementById('cancel-modal-description').textContent = `Anda akan membatalkan: ${title}. Tindakan ini tidak dapat dibatalkan.`;
        
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Trigger animation
        setTimeout(() => {
            modalContent.classList.remove('scale-95', 'opacity-0');
            modalContent.classList.add('scale-100', 'opacity-100');
        }, 10);

        confirmInput.value = '';
        pinInput.value = '';
        updateSubmitButton();
        
        setTimeout(() => confirmInput.focus(), 350);
    }

    function closeCancelModal() {
        modalContent.classList.remove('scale-100', 'opacity-100');
        modalContent.classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 300);
    }

    function updateSubmitButton() {
        const isConfirmMatch = confirmInput.value === 'SETUJU';
        const isPinValid = pinInput.value.length === 6;
        
        if (isConfirmMatch && isPinValid) {
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
        } else {
            submitBtn.disabled = true;
            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
        }
    }

    confirmInput.addEventListener('input', updateSubmitButton);
    pinInput.addEventListener('input', (e) => {
        e.target.value = e.target.value.replace(/[^0-9]/g, '');
        updateSubmitButton();
    });

    cancelForm.addEventListener('submit', function(e) {
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        `;
    });

    // Close on outside click
    modal.addEventListener('click', (e) => {
        if (e.target === modal) closeCancelModal();
    });
</script>
@endpush
@endsection
