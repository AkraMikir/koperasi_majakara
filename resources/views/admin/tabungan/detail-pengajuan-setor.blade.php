@extends('layouts.admin')

@section('title', 'Detail Pengajuan Setor')

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
                    <div>
                        <p class="text-sm text-gray-600">Nama Pemilik Rekening</p>
                        <p class="font-semibold text-gray-900">{{ $pengajuan->nasabah->dataRek->nama_pemilik_rekening ?? 'N/A' }}</p>
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
                        <p class="text-sm text-gray-600">Status</p>
                        @php
                            $statusConfig = [
                                '1' => ['label' => 'Pending', 'bg' => 'bg-yellow-100', 'text' => 'text-yellow-700'],
                                '2' => ['label' => 'Disetujui', 'bg' => 'bg-green-100', 'text' => 'text-green-700'],
                                '3' => ['label' => 'Ditolak', 'bg' => 'bg-red-100', 'text' => 'text-red-700'],
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

            <!-- Bukti Foto -->
            @if($pengajuan->buktiFoto->count() > 0)
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Bukti Foto Transfer</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($pengajuan->buktiFoto as $bukti)
                    <div class="border border-gray-200 rounded-lg overflow-hidden">
                        <div class="w-full h-48 bg-gray-100 overflow-hidden">
                            @php
                                // File disimpan dengan store('bukti_tabungan', 'public')
                                // Path di database: bukti_tabungan/filename.jpg
                                // Verify file exists in storage
                                $fileExists = \Illuminate\Support\Facades\Storage::disk('public')->exists($bukti->file_photo);
                                
                                // Generate URL menggunakan request()->getSchemeAndHttpHost() untuk fleksibilitas
                                // Ini akan menggunakan URL yang sama dengan yang digunakan user (127.0.0.1:8000 atau localhost)
                                $baseUrl = request()->getSchemeAndHttpHost();
                                $imageUrl = $baseUrl . '/storage/' . $bukti->file_photo;
                            @endphp
                            @if($fileExists)
                                <img src="{{ $imageUrl }}?v={{ time() }}" alt="Bukti Foto" class="w-full h-full object-cover" 
                                    onerror="console.error('Image load error:', '{{ $imageUrl }}'); this.onerror=null; this.parentElement.innerHTML='<div class=\'w-full h-full flex items-center justify-center bg-gray-100\'><div class=\'text-center\'><svg class=\'w-16 h-16 text-gray-400 mx-auto mb-2\' fill=\'none\' stroke=\'currentColor\' viewBox=\'0 0 24 24\'><path stroke-linecap=\'round\' stroke-linejoin=\'round\' stroke-width=\'2\' d=\'M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z\'></path></svg><p class=\'text-sm text-gray-500 font-semibold\'>Gambar tidak dapat dimuat</p><p class=\'text-xs text-gray-400 mt-1 break-all px-2\'>URL: {{ $imageUrl }}</p><p class=\'text-xs text-gray-400 mt-1\'>Path DB: {{ $bukti->file_photo }}</p><p class=\'text-xs text-blue-500 mt-2\'>Coba hard refresh: Ctrl+F5</p></div></div>';">
                            @else
                                <div class="w-full h-full flex items-center justify-center">
                                    <div class="text-center">
                                        <svg class="w-16 h-16 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                        <p class="text-sm text-gray-500 font-semibold">File tidak ditemukan di storage</p>
                                        <p class="text-xs text-gray-400 mt-1 break-all px-2">Path: {{ $bukti->file_photo }}</p>
                                        <p class="text-xs text-gray-400 mt-1 break-all px-2">URL: {{ $imageUrl }}</p>
                                        <p class="text-xs text-blue-500 mt-2">Pastikan symlink sudah dibuat: php artisan storage:link</p>
                                    </div>
                                </div>
                            @endif
                        </div>
                        <div class="p-3 bg-gray-50">
                            <p class="text-sm font-semibold text-gray-900">Rp {{ number_format($bukti->nominal, 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-600">{{ $bukti->keterangan }}</p>
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
            <!-- Action Buttons -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Tindakan</h3>
                <div class="space-y-3">
                    <form method="POST" action="{{ route('admin.tabungan.approve-setor', $pengajuan->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menyetujui pengajuan ini?')">
                        @csrf
                        <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-green-600 to-green-700 text-white rounded-lg hover:from-green-700 hover:to-green-800 transition-all font-medium shadow-md">
                            ✓ Setujui Pengajuan
                        </button>
                    </form>
                    
                    <button onclick="showRejectModal()" class="w-full px-4 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-lg hover:from-red-700 hover:to-red-800 transition-all font-medium shadow-md">
                        ✗ Tolak Pengajuan
                    </button>

                    <button onclick="showEditModal()" class="w-full px-4 py-3 bg-gradient-to-r from-blue-600 to-blue-700 text-white rounded-lg hover:from-blue-700 hover:to-blue-800 transition-all font-medium shadow-md">
                        ✎ Edit Pengajuan
                    </button>

                    <form method="POST" action="{{ route('admin.tabungan.delete-pengajuan-setor', $pengajuan->id) }}" onsubmit="return confirm('Apakah Anda yakin ingin menghapus pengajuan ini? Tindakan ini tidak dapat dibatalkan.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="w-full px-4 py-3 bg-gradient-to-r from-gray-600 to-gray-700 text-white rounded-lg hover:from-gray-700 hover:to-gray-800 transition-all font-medium shadow-md">
                            🗑️ Hapus Pengajuan
                        </button>
                    </form>
                </div>
            </div>

            <!-- Reject Modal -->
            <div id="rejectModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
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

            <!-- Edit Modal -->
            <div id="editModal" class="hidden fixed inset-0 bg-black bg-opacity-50 z-50 flex items-center justify-center">
                <div class="bg-white rounded-2xl p-6 max-w-md w-full mx-4">
                    <h3 class="text-xl font-bold text-gray-900 mb-4">Edit Pengajuan</h3>
                    <form method="POST" action="{{ route('admin.tabungan.edit-pengajuan-setor', $pengajuan->id) }}">
                        @csrf
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Status</label>
                            <select name="status" required class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none">
                                <option value="1" {{ $pengajuan->status == '1' ? 'selected' : '' }}>Pending</option>
                                <option value="2" {{ $pengajuan->status == '2' ? 'selected' : '' }}>Disetujui</option>
                                <option value="3" {{ $pengajuan->status == '3' ? 'selected' : '' }}>Ditolak</option>
                            </select>
                        </div>
                        <div class="mb-4">
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                            <textarea name="keterangan" rows="4" class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none" placeholder="Masukkan keterangan...">{{ $pengajuan->keterangan }}</textarea>
                        </div>
                        <div class="flex space-x-3">
                            <button type="button" onclick="hideEditModal()" class="flex-1 px-4 py-2 border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors">
                                Batal
                            </button>
                            <button type="submit" class="flex-1 px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                Simpan
                            </button>
                        </div>
                    </form>
                </div>
            </div>
            @endif
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

    function showEditModal() {
        document.getElementById('editModal').classList.remove('hidden');
    }
    
    function hideEditModal() {
        document.getElementById('editModal').classList.add('hidden');
    }
</script>
@endsection


