@extends('layouts.nasabah')

@section('title', 'Detail Pembayaran')

@section('content')
<div class="w-full pb-6">
    <!-- Back Button & Struk -->
    <div class="mx-4 mt-4 mb-4 flex flex-wrap items-center gap-2">
        {{-- 
        <a href="{{ route('nasabah.pinjaman.struk-pembayaran', $pengajuan->id) }}" target="_blank"
            class="inline-flex items-center gap-2 px-4 py-2 bg-[#674c1d] text-white rounded-xl shadow hover:shadow-md transition-all hover:bg-[#8b6f2f]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
            Download PDF Struk
        </a>
        --}}
        <a href="{{ route('nasabah.pinjaman.status-pembayaran') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-xl shadow hover:shadow-md transition-all text-gray-700 hover:text-[#674c1d]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Hero Section -->
    <div class="mx-4 mb-6">
        <div class="bg-linear-to-br from-[#8b6f2f] via-[#a0824d] to-[#d4af37] rounded-3xl shadow-2xl p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            <div class="relative z-10">
                <div class="flex items-center justify-between">
                    <div>
                        <h1 class="text-3xl font-bold text-white mb-2 font-display">Detail Pembayaran</h1>
                        <p class="text-white/90 text-sm">ID: #{{ str_pad($pengajuan->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    @php
                        $statusConfig = [
                            '1' => ['bg' => 'bg-yellow-500', 'label' => 'Pending'],
                            '2' => ['bg' => 'bg-red-500', 'label' => 'Ditolak'],
                            '3' => ['bg' => 'bg-blue-500', 'label' => 'Disetujui'],
                            '4' => ['bg' => 'bg-green-500', 'label' => 'Terlaksana'],
                        ];
                        $status = $statusConfig[$pengajuan->status] ?? $statusConfig['1'];
                    @endphp
                    <span class="px-4 py-2 {{ $status['bg'] }} text-white rounded-full text-sm font-semibold">
                        {{ $status['label'] }}
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Informasi Pembayaran -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#8b6f2f] mb-6 font-display">Informasi Pembayaran</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Pengajuan</p>
                    <p class="font-semibold text-gray-900 text-lg">{{ $pengajuan->created_at->format('d M Y, H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Nominal Pembayaran</p>
                    <p class="font-semibold text-[#8b6f2f] text-lg">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Pinjaman</p>
                    <p class="font-semibold text-gray-900">#{{ str_pad($pengajuan->pinjaman_id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Metode Pembayaran</p>
                    @if($pengajuan->rekening_tujuan)
                    <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                        Transfer
                    </span>
                    @else
                    <span class="px-3 py-1 bg-purple-100 text-purple-700 rounded-full text-sm font-semibold">
                        Cash (Janji Temu)
                    </span>
                    @endif
                </div>
                @if($pengajuan->rekening_tujuan)
                <div>
                    <p class="text-sm text-gray-500 mb-1">Rekening Tujuan</p>
                    <p class="font-semibold text-gray-900">{{ $pengajuan->rekening_tujuan }}</p>
                </div>
                @endif
                @if($pengajuan->tgl_pembayaran)
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal Pembayaran</p>
                    <p class="font-semibold text-gray-900">{{ $pengajuan->tgl_pembayaran->format('d M Y, H:i') }}</p>
                </div>
                @endif
            </div>

            @if($pengajuan->keterangan)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-500 mb-1">Keterangan Anda</p>
                <p class="text-gray-900">{{ $pengajuan->keterangan }}</p>
            </div>
            @endif
            @if($pengajuan->keterangan_admin)
            <div class="mt-6 pt-6 border-t border-gray-200">
                <p class="text-sm text-gray-500 mb-1">Keterangan Admin</p>
                <p class="text-gray-900 {{ in_array($pengajuan->status, ['2']) ? 'text-red-700 font-medium' : 'text-green-700' }}">{{ $pengajuan->keterangan_admin }}</p>
            </div>
            @endif
        </div>
    </div>

    <!-- Bukti Foto (transfer / serah terima dari admin) -->
    @if($pengajuan->buktiFoto->count() > 0)
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#8b6f2f] mb-4 font-display">Bukti Transfer</h2>
            <p class="text-sm text-gray-500 mb-4">Bukti pembayaran yang diberikan admin sebagai pegangan Anda. Klik untuk preview atau unduh.</p>
            <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4">
                @foreach($pengajuan->buktiFoto as $bukti)
                @php
                    $filePath = $bukti->file_path ?? null;
                    $fileExists = $filePath && \Illuminate\Support\Facades\Storage::disk('public')->exists($filePath);
                    $imageUrl = $fileExists ? asset('storage/' . $filePath) : null;
                    $fileName = $filePath ? basename($filePath) : 'bukti-transfer';
                @endphp
                @if($imageUrl)
                <div class="border border-gray-200 rounded-xl overflow-hidden bg-gray-50 hover:shadow-md transition-all group">
                    <div class="aspect-[4/3] bg-gray-100 overflow-hidden cursor-pointer" onclick="window.open('{{ $imageUrl }}', '_blank', 'width=800,height=600')">
                        <img src="{{ $imageUrl }}" alt="Bukti Transfer" class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-200">
                    </div>
                    <div class="p-3 flex items-center justify-between gap-2">
                        <span class="text-xs text-gray-500 truncate flex-1">{{ $bukti->keterangan ?: $fileName }}</span>
                        <a href="{{ $imageUrl }}" download="{{ $fileName }}" class="shrink-0 inline-flex items-center gap-1 px-3 py-1.5 bg-[#8b6f2f] text-white text-xs font-semibold rounded-lg hover:bg-[#674c1d] transition-colors">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path>
                            </svg>
                            Unduh
                        </a>
                    </div>
                </div>
                @endif
                @endforeach
            </div>
        </div>
    </div>
    @endif

    <!-- Janji Temu -->
    @if($pengajuan->janjiTemu)
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#8b6f2f] mb-6 font-display">Informasi Janji Temu</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div>
                    <p class="text-sm text-gray-500 mb-1">Tanggal & Waktu</p>
                    <p class="font-semibold text-gray-900">{{ $pengajuan->janjiTemu->tanggal_janji_temu->format('d M Y') }}, {{ $pengajuan->janjiTemu->waktu_janji_temu->format('H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 mb-1">Lokasi</p>
                    <p class="font-semibold text-gray-900">{{ $pengajuan->janjiTemu->lokasi->nama_lokasi ?? 'N/A' }}</p>
                    <p class="text-sm text-gray-600 mt-1">{{ $pengajuan->janjiTemu->lokasi->alamat_lengkap ?? '' }}</p>
                </div>
                @if($pengajuan->janjiTemu->keterangan)
                <div class="md:col-span-2">
                    <p class="text-sm text-gray-500 mb-1">Keterangan</p>
                    <p class="text-gray-900">{{ $pengajuan->janjiTemu->keterangan }}</p>
                </div>
                @endif
            </div>
        </div>
    </div>
    @endif


    <!-- Back Button -->
    <div class="mx-4 mb-6">
        <a href="{{ route('nasabah.pinjaman.status-pembayaran') }}" 
            class="inline-flex items-center gap-2 text-[#8b6f2f] font-semibold hover:underline">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Status Pembayaran
        </a>
    </div>
</div>
@endsection
