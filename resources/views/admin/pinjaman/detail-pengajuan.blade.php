@extends('layouts.admin')

@section('title', 'Detail Pengajuan Pinjaman')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Pengajuan Pinjaman</h1>
            <p class="text-gray-600 mt-1">ID Pengajuan: #{{ $pengajuan->id }}</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.pinjaman.pengajuan') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
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
                    @if($pengajuan->nasabah->pekerjaan)
                    <div>
                        <p class="text-sm text-gray-600">Pekerjaan</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->pekerjaan->pekerjaan ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Penghasilan</p>
                        <p class="font-semibold text-gray-900">Rp {{ number_format($pengajuan->nasabah->pekerjaan->penghasilan ?? 0, 0, ',', '.') }}</p>
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
                        <p class="text-sm text-gray-600">Nominal Pinjaman</p>
                        <p class="font-semibold text-[#674c1d] text-2xl">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-600">Jenis</p>
                            <span class="inline-block mt-2 px-4 py-2 {{ $pengajuan->jenis === 'bulanan' ? 'bg-blue-100 text-blue-700' : 'bg-purple-100 text-purple-700' }} rounded-full text-sm font-semibold">
                                {{ ucfirst($pengajuan->jenis) }}
                            </span>
                        </div>
                        <div>
                            <p class="text-sm text-gray-600">Durasi</p>
                            <p class="font-semibold text-gray-900 mt-2">{{ $pengajuan->durasi }} {{ $pengajuan->jenis === 'bulanan' ? 'bulan' : 'minggu' }}</p>
                        </div>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Status</p>
                        @if($pengajuan->status === '1')
                            <span class="inline-block mt-2 px-4 py-2 bg-yellow-100 text-yellow-700 rounded-full text-sm font-semibold">
                                Pending
                            </span>
                        @elseif($pengajuan->status === '2')
                            <span class="inline-block mt-2 px-4 py-2 bg-red-100 text-red-700 rounded-full text-sm font-semibold">
                                Ditolak
                            </span>
                        @elseif($pengajuan->status === '3')
                            <span class="inline-block mt-2 px-4 py-2 bg-blue-100 text-blue-700 rounded-full text-sm font-semibold">
                                Disetujui (Menunggu Pencairan)
                            </span>
                        @elseif($pengajuan->status === '4')
                            <span class="inline-block mt-2 px-4 py-2 bg-green-100 text-green-700 rounded-full text-sm font-semibold">
                                Terlaksana
                            </span>
                        @endif
                    </div>
                </div>
            </div>
        </div>

        <!-- Sidebar Actions -->
        <div class="space-y-6">
            <!-- Action Buttons -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Tindakan</h3>
                
                @if($pengajuan->status === '1')
                    <!-- Status Pending - Show Approve/Reject -->
                    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <h4 class="font-semibold text-blue-900 mb-2">Informasi Bunga & Denda</h4>
                        @if($masterBunga)
                            <p class="text-sm text-blue-800 mb-1">
                                <strong>Bunga:</strong> {{ $masterBunga->bunga_persen }}% 
                                (Durasi {{ $masterBunga->durasi_min }}-{{ $masterBunga->durasi_max }} bulan)
                            </p>
                            <p class="text-sm text-blue-800 mb-1">
                                <strong>Bunga Total:</strong> Rp {{ number_format(($pengajuan->nominal * $masterBunga->bunga_persen) / 100, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-blue-800 mb-1">
                                <strong>Yang Diterima Nasabah:</strong> Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}
                            </p>
                            <p class="text-sm text-blue-800 mb-1">
                                <strong>Total Tagihan:</strong> Rp {{ number_format($pengajuan->nominal + (($pengajuan->nominal * $masterBunga->bunga_persen) / 100), 0, ',', '.') }}
                            </p>
                        @else
                            <p class="text-sm text-red-600">Bunga untuk durasi {{ $pengajuan->durasi }} bulan belum diatur di master data!</p>
                        @endif
                        @if($masterDenda)
                            <p class="text-sm text-blue-800">
                                <strong>Denda:</strong> {{ $masterDenda->denda_persen }}% per hari dari POKOK per bulan
                            </p>
                        @else
                            <p class="text-sm text-red-600">Denda belum diatur di master data!</p>
                        @endif
                    </div>
                    
                    <form method="POST" action="{{ route('admin.pinjaman.approve-pengajuan', $pengajuan->id) }}" onsubmit="return confirm('Setujui pengajuan ini? Status akan berubah menjadi DISETUJUI. Anda masih perlu CAIRKAN dana setelah ini.')">
                        @csrf
                        @if(!$masterBunga || !$masterDenda)
                            <div class="mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
                                <p class="text-sm text-red-600">Tidak dapat menyetujui pengajuan karena master data belum lengkap!</p>
                            </div>
                            <button type="submit" disabled class="w-full px-4 py-3 bg-gray-400 text-white rounded-lg cursor-not-allowed font-medium shadow-md mb-3">
                                ✓ Setujui Pengajuan
                            </button>
                        @else
                            <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-medium shadow-md mb-3">
                                ✓ Setujui Pengajuan
                            </button>
                        @endif
                    </form>
                    
                    <button onclick="showRejectModal()" class="w-full px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all font-medium shadow-md">
                        ✗ Tolak Pengajuan
                    </button>

                @elseif($pengajuan->status === '3')
                    <!-- Status Disetujui - Show Cairkan -->
                    <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-lg">
                        <p class="text-sm text-blue-800 font-semibold mb-2">✓ Pengajuan telah disetujui</p>
                        <p class="text-sm text-blue-800 mb-1">
                            <strong>Bunga:</strong> {{ $pengajuan->bunga_persen }}%
                        </p>
                        <p class="text-sm text-blue-800 mb-1">
                            <strong>Total Bunga:</strong> Rp {{ number_format(($pengajuan->nominal * $pengajuan->bunga_persen) / 100, 0, ',', '.') }}
                        </p>
                        <p class="text-sm text-blue-800 mb-1">
                            <strong>Yang Diterima Nasabah:</strong> Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}
                        </p>
                    </div>
                    
                    <button onclick="showCairkanModal()" class="w-full px-4 py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-lg hover:from-[#8b6f2f] hover:to-[#a0824d] transition-all font-medium shadow-md">
                        💰 Cairkan Pinjaman
                    </button>

                @elseif($pengajuan->status === '2')
                    <!-- Status Ditolak -->
                    <div class="p-4 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-800 font-semibold mb-2">✗ Pengajuan ditolak</p>
                        @if($pengajuan->keterangan)
                            <p class="text-sm text-red-700">
                                <strong>Alasan:</strong> {{ $pengajuan->keterangan }}
                            </p>
                        @endif
                    </div>

                @elseif($pengajuan->status === '4')
                    <!-- Status Terlaksana -->
                    <div class="p-4 bg-green-50 border border-green-200 rounded-lg">
                        <p class="text-sm text-green-800 font-semibold mb-2">✓ Pinjaman telah dicairkan</p>
                        @if($pengajuan->tgl_cair)
                            <p class="text-sm text-green-700">
                                <strong>Tanggal Cair:</strong> {{ \Carbon\Carbon::parse($pengajuan->tgl_cair)->format('d M Y') }}
                            </p>
                        @endif
                        @if($pengajuan->pinjaman)
                            <a href="{{ route('admin.pinjaman.detail-pinjaman', $pengajuan->pinjaman->id) }}" class="mt-3 block w-full px-4 py-2 bg-[#674c1d] text-white text-center rounded-lg hover:bg-[#8b6f2f] transition-colors">
                                Lihat Detail Pinjaman →
                            </a>
                        @endif
                    </div>
                @endif
            </div>

            <!-- Reject Modal -->
            <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Tolak Pengajuan</h3>
                    <form method="POST" action="{{ route('admin.pinjaman.reject-pengajuan', $pengajuan->id) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Penolakan *</label>
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

            <!-- Cairkan Modal -->
            <div id="cairkanModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center p-4">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Cairkan Pinjaman</h3>
                    <form method="POST" action="{{ route('admin.pinjaman.cairkan-pinjaman', $pengajuan->id) }}" enctype="multipart/form-data">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Cair *</label>
                            <input type="date" name="tgl_cair" required value="{{ date('Y-m-d') }}"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Transfer (Opsional)</label>
                            <input type="file" name="bukti_transfer" accept="image/*"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
                            <p class="text-xs text-gray-500 mt-1">Format: JPG, PNG (Max 5MB)</p>
                        </div>
                        <div class="p-3 bg-yellow-50 border border-yellow-200 rounded-lg mb-4">
                            <p class="text-sm text-yellow-800">
                                <strong>⚠️ Perhatian:</strong> Setelah cairkan, sistem akan:
                            </p>
                            <ul class="text-xs text-yellow-700 mt-2 list-disc list-inside space-y-1">
                                <li>Membuat data pinjaman di database</li>
                                <li>Generate jadwal angsuran otomatis</li>
                                <li>Status pengajuan berubah menjadi TERLAKSANA</li>
                            </ul>
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="hideCairkanModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#8b6f2f] transition-colors">
                                Cairkan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    function showRejectModal() {
        document.getElementById('rejectModal').classList.remove('hidden');
    }
    
    function hideRejectModal() {
        document.getElementById('rejectModal').classList.add('hidden');
    }

    function showCairkanModal() {
        document.getElementById('cairkanModal').classList.remove('hidden');
    }
    
    function hideCairkanModal() {
        document.getElementById('cairkanModal').classList.add('hidden');
    }

    // Close modals on outside click
    document.getElementById('rejectModal')?.addEventListener('click', function(e) {
        if (e.target === this) hideRejectModal();
    });

    document.getElementById('cairkanModal')?.addEventListener('click', function(e) {
        if (e.target === this) hideCairkanModal();
    });
</script>
@endsection
