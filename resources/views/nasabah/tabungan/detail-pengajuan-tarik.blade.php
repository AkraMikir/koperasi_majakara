@extends('layouts.nasabah')

@section('title', 'Detail Pengajuan Penarikan')

@section('content')
<div class="w-full pb-6">
    <!-- Header Section -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-linear-to-br from-[#8b6f2f] via-[#d4af37] to-[#674c1d] rounded-3xl shadow-2xl p-6 border-2 border-[#d4af37]/30">
            <div class="flex items-center justify-between">
                <div class="flex items-center gap-4">
                    <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h1 class="text-2xl font-bold text-white font-display mb-1">Detail Pengajuan Penarikan</h1>
                        <p class="text-white/90 text-sm">ID: #{{ $pengajuan->id }}</p>
                    </div>
                </div>
                <a href="{{ route('nasabah.tabungan.status-pengajuan-tarik') }}" class="px-4 py-2 bg-white/20 backdrop-blur-sm text-white rounded-xl hover:bg-white/30 transition-colors">
                    ← Kembali
                </a>
            </div>
        </div>
    </div>

    <div class="mx-4 mb-6 space-y-6">
        <!-- Status Card -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-sm text-gray-600 mb-1">Status Pengajuan</p>
                    @php
                        $statusConfig = [
                            '1' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Menunggu Persetujuan'],
                            '2' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Disetujui'],
                            '3' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                        ];
                        $status = $statusConfig[$pengajuan->status] ?? $statusConfig['1'];
                    @endphp
                    <span class="inline-block mt-2 px-4 py-2 {{ $status['bg'] }} {{ $status['text'] }} rounded-full text-sm font-semibold">
                        {{ $status['label'] }}
                    </span>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-600">Tanggal Pengajuan</p>
                    <p class="font-semibold text-gray-900">{{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
                </div>
            </div>
        </div>

        <!-- Informasi Pengajuan -->
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#674c1d] font-display mb-4">Informasi Pengajuan</h2>
            
            <div class="space-y-4">
                <div>
                    <p class="text-sm text-gray-600 mb-2">Nominal Penarikan</p>
                    <p class="text-3xl font-bold text-[#8b6f2f]">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p>
                </div>

                @if($pengajuan->status == '2' && $pengajuan->metode_transfer === 'transfer' && isset($pengajuan->biaya_transfer) && (float)$pengajuan->biaya_transfer > 0)
                <div>
                    <p class="text-sm text-gray-600 mb-1">Biaya transfer admin (ditanggung nasabah)</p>
                    <p class="font-semibold text-gray-900">Rp {{ number_format($pengajuan->biaya_transfer, 0, ',', '.') }}</p>
                    <p class="text-xs text-gray-500 mt-1">Total yang didebet dari saldo: Rp {{ number_format($pengajuan->nominal + $pengajuan->biaya_transfer, 0, ',', '.') }}</p>
                </div>
                @endif

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Metode</p>
                        <p class="font-semibold text-gray-900">{{ ucfirst($pengajuan->metode_transfer ?? 'Tunai') }}</p>
                    </div>
                    @if($pengajuan->metode_transfer === 'transfer')
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Bank Tujuan</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->nama_bank ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Nomor Rekening</p>
                        <p class="font-semibold text-gray-900 font-mono">{{ $pengajuan->no_rekening ?? 'N/A' }}</p>
                    </div>
                    @else
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Lokasi Pertemuan</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->lokasi->nama_lokasi ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600 mb-1">Jadwal</p>
                        <p class="font-semibold text-gray-900">
                            {{ $pengajuan->tanggal_janji_temu ? $pengajuan->tanggal_janji_temu->format('d M Y') : '-' }} , 
                            {{ $pengajuan->waktu_janji_temu ? \Carbon\Carbon::parse($pengajuan->waktu_janji_temu)->format('H:i') : '-' }} WIB
                        </p>
                    </div>
                    @endif
                </div>

                @if($pengajuan->keterangan)
                <div>
                    <p class="text-sm text-gray-600 mb-2">Keterangan</p>
                    <p class="text-gray-900">{{ $pengajuan->keterangan }}</p>
                </div>
                @endif

                @if($pengajuan->status == '2' && $pengajuan->foto_bukti_tf_admin)
                <div class="pt-6 border-t border-gray-100">
                    <h3 class="text-md font-bold text-[#674c1d] mb-4">Bukti Transfer dari Admin</h3>
                    <div class="max-w-md border border-gray-200 rounded-2xl overflow-hidden shadow-sm hover:shadow-md transition-shadow cursor-pointer"
                        onclick="showPhotoPreview('{{ asset('storage/' . $pengajuan->foto_bukti_tf_admin) }}', 'Bukti Transfer Admin')">
                        <img src="{{ asset('storage/' . $pengajuan->foto_bukti_tf_admin) }}" alt="Bukti Transfer Admin" class="w-full h-auto">
                        <div class="p-3 bg-gray-50 text-center text-xs text-gray-500">
                            Klik untuk memperbesar
                        </div>
                    </div>
                </div>
                @endif

                @if($pengajuan->keterangan_admin)
                <div class="mt-4 p-4 bg-gray-50 rounded-xl border border-gray-200">
                    <p class="text-sm font-semibold text-gray-700 mb-1">Pesan dari Admin:</p>
                    <p class="text-gray-600">{{ $pengajuan->keterangan_admin }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
</div>

<!-- Photo Preview Modal -->
<div id="photoPreviewModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-50 hidden flex items-center justify-center p-4" onclick="closePhotoPreview()">
    <div class="relative max-w-4xl w-full" onclick="event.stopPropagation()">
        <button onclick="closePhotoPreview()" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors">
            <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <div class="bg-white rounded-2xl overflow-hidden shadow-2xl">
            <div class="p-4 bg-linear-to-r from-[#674c1d] to-[#8b6f2f]">
                <h3 id="photoTitle" class="text-white font-bold text-lg"></h3>
            </div>
            <div class="p-4 bg-gray-50">
                <img id="photoPreview" src="" alt="Preview" class="w-full h-auto rounded-lg">
            </div>
        </div>
    </div>
</div>

<script>
function showPhotoPreview(imageSrc, title) {
    document.getElementById('photoPreview').src = imageSrc;
    document.getElementById('photoTitle').textContent = title;
    document.getElementById('photoPreviewModal').classList.remove('hidden');
    document.getElementById('photoPreviewModal').classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closePhotoPreview() {
    document.getElementById('photoPreviewModal').classList.add('hidden');
    document.getElementById('photoPreviewModal').classList.remove('flex');
    document.body.style.overflow = 'auto';
}

// Close modal on ESC key
document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closePhotoPreview();
    }
});
</script>
@endsection
