@extends('layouts.admin')

@section('title', 'Detail Pengajuan Setor')

@push('styles')
<!-- GLightbox CSS -->
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/glightbox/dist/css/glightbox.min.css">
@endpush

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Pengajuan Setor</h1>
            <p class="text-gray-600 mt-1">ID Pengajuan: #{{ $pengajuan->id }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.tabungan.pengajuan-setor') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Data Nasabah -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Data Nasabah</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Lengkap</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->user->nama ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nomor HP</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->user->nomor_hp ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">NIK</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->dataKtp->nik ?? 'N/A' }}</p>
                    </div>
                    @if($pengajuan->nasabah->dataRek)
                    <div>
                        <p class="text-sm text-gray-600">No. Rekening</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->dataRek->no_rekening ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nama Bank</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->dataRek->nama_bank ?? 'N/A' }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Informasi Pengajuan -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Informasi Pengajuan</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nominal Setoran</p>
                        <p class="font-semibold text-[#674c1d] text-2xl">Rp {{ number_format($pengajuan->nominal ?? 0, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        @php
                            $statusConfig = [
                                '1' => ['label' => 'Pending', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
                                '3' => ['label' => 'Disetujui', 'bg' => 'bg-green-100', 'text' => 'text-green-700'],
                                '2' => ['label' => 'Ditolak', 'bg' => 'bg-red-100', 'text' => 'text-red-700'],
                            ];
                            $status = $statusConfig[$pengajuan->status] ?? $statusConfig['1'];
                        @endphp
                        <span class="inline-block mt-2 px-4 py-2 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-sm font-semibold">
                            {{ $status['label'] }}
                        </span>
                    </div>
                    @if($pengajuan->keterangan)
                    <div>
                        <p class="text-sm text-gray-600">Keterangan</p>
                        <p class="text-gray-900 mt-1">{{ $pengajuan->keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Bukti Foto with Lightbox -->
            @if($pengajuan->buktiFoto->count() > 0)
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Bukti Foto Transfer</h2>
                <p class="text-sm text-gray-600 mb-4">Klik foto untuk memperbesar</p>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($pengajuan->buktiFoto as $index => $bukti)
                    <div class="border border-gray-200 rounded-lg overflow-hidden hover:shadow-lg transition-shadow">
                        <a href="{{ Storage::url($bukti->file_path) }}" class="glightbox" data-gallery="bukti-transfer" data-title="Bukti Transfer {{ $index + 1 }}">
                            <div class="w-full h-64 bg-gray-100 overflow-hidden cursor-pointer group">
                                @if(Storage::disk('public')->exists($bukti->file_path))
                                    <img src="{{ Storage::url($bukti->file_path) }}?v={{ time() }}" 
                                         alt="Bukti Transfer" 
                                         class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
                                         onerror="this.onerror=null; this.src='data:image/svg+xml,<svg xmlns=%22http://www.w3.org/2000/svg%22 width=%22200%22 height=%22200%22><text x=%2250%22 y=%22100%22 font-size=%2220%22 fill=%22%23999%22>Image Error</text></svg>';">
                                @else
                                    <div class="w-full h-full flex items-center justify-center">
                                        <div class="text-center">
                                            <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                            <p class="text-sm text-gray-500">File tidak ditemukan</p>
                                        </div>
                                    </div>
                                @endif
                            </div>
                        </a>
                        <div class="p-3 bg-gray-50">
                            <p class="text-sm font-semibold text-gray-700">Bukti Transfer {{ $index + 1 }}</p>
                            <p class="text-xs text-gray-500 mt-1">Uploaded: {{ $bukti->created_at->format('d M Y H:i') }}</p>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @endif

            <!-- Janji Temu -->
            @if($pengajuan->janjiTemu)
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Janji Temu</h2>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal & Waktu</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->janjiTemu->tanggal_janji_temu->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Lokasi</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->janjiTemu->lokasi->nama_lokasi ?? 'N/A' }}</p>
                        <p class="text-sm text-gray-600 mt-1">{{ $pengajuan->janjiTemu->lokasi->alamat_lengkap ?? '' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nominal</p>
                        <p class="font-semibold text-[#674c1d]">Rp {{ number_format($pengajuan->janjiTemu->nominal, 0, ',', '.') }}</p>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            @if($pengajuan->status == '1')
            <!-- Edit & Approve Form -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Edit & Setujui</h3>
                <form method="POST" action="{{ route('admin.tabungan.edit-pengajuan-setor', $pengajuan->id) }}" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Nominal (Rp)</label>
                        <input type="text" name="nominal" id="nominal" value="{{ number_format($pengajuan->nominal ?? 0, 0, '.', '') }}" 
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none"
                            oninput="formatNominal(this)">
                        <p class="text-xs text-gray-500 mt-1">Min: Rp 10.000</p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="3" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">{{ $pengajuan->keterangan }}</textarea>
                    </div>
                    <input type="hidden" name="status" value="2">
                    <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-medium shadow-md">
                        ✓ Update & Setujui
                    </button>
                </form>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Tindakan Lain</h3>
                <div class="space-y-3">
                    <form method="POST" action="{{ route('admin.tabungan.approve-setor', $pengajuan->id) }}" onsubmit="return confirm('Setujui tanpa edit?')">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-medium shadow-md">
                            ⚡ Setujui Cepat
                        </button>
                    </form>
                    
                    <button onclick="showRejectModal()" class="w-full px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all font-medium shadow-md">
                        ✗ Tolak Pengajuan
                    </button>

                    <form method="POST" action="{{ route('admin.tabungan.delete-pengajuan-setor', $pengajuan->id) }}" onsubmit="return confirm('Yakin hapus? Tidak dapat dibatalkan!')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-lg hover:from-gray-700 hover:to-gray-800 transition-all font-medium shadow-md">
                            🗑️ Hapus Pengajuan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Reject Modal -->
            <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Tolak Pengajuan</h3>
                    <form method="POST" action="{{ route('admin.tabungan.reject-setor', $pengajuan->id) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan</label>
                            <textarea name="keterangan" rows="4" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none" placeholder="Masukkan alasan penolakan..."></textarea>
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="hideRejectModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                Tolak
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
        </div>
    </div>
</div>

@push('scripts')
<!-- GLightbox JS -->
<script src="https://cdn.jsdelivr.net/npm/glightbox/dist/js/glightbox.min.js"></script>
<script>
    // Initialize GLightbox
    const lightbox = GLightbox({
        selector: '.glightbox',
        touchNavigation: true,
        loop: true,
        autoplayVideos: true
    });

    function formatNominal(input) {
        let value = input.value.replace(/\D/g, '');
        input.value = value.replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    function showRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    
    function hideRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    // Convert formatted nominal back to number before submit
    document.querySelectorAll('form').forEach(form => {
        form.addEventListener('submit', function(e) {
            const nominalInput = this.querySelector('input[name="nominal"]');
            if (nominalInput) {
                nominalInput.value = nominalInput.value.replace(/\./g, '');
            }
        });
    });
</script>
@endpush
@endsection
