@extends('layouts.admin')

@section('title', 'Detail Transaksi Tabungan')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Detail Transaksi Tabungan</h1>
            <p class="text-gray-600 mt-1">ID Transaksi: {{ $transaksi->id_transaksi ?? str_pad($transaksi->id, 5, '0', STR_PAD_LEFT) }}</p>
        </div>
        <div class="flex items-center space-x-3">
            @if(!$transaksi->id_pengajuan_setor && !$transaksi->id_pengajuan_tarik)
            @canCrudTabungan
            <a href="{{ route('admin.tabungan.edit-transaksi', $transaksi->id) }}" class="px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm font-medium">
                Edit Transaksi
            </a>
            <form method="POST" action="{{ route('admin.tabungan.destroy-transaksi', $transaksi->id) }}" class="inline" onsubmit="return confirm('Yakin hapus transaksi ini?')">
                @csrf
                @method('DELETE')
                <button type="submit" class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors text-sm font-medium">
                    Hapus
                </button>
            </form>
            @endcanCrudTabungan
            @endif
            <!-- <a href="{{ route('admin.tabungan.print-struk-transaksi', $transaksi->id) }}" target="_blank" class="px-4 py-2 bg-[#674c1d] text-white rounded-lg hover:bg-[#8b6f2f] transition-colors text-sm font-medium inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
                Cetak Struk (Langsung)
            </a> -->
            <a href="{{ route('admin.tabungan.struk-transaksi', $transaksi->id) }}" class="px-4 py-2 bg-amber-600 text-white rounded-lg hover:bg-amber-700 transition-colors text-sm font-medium inline-flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                Download PDF
            </a>
            <a href="{{ route('admin.tabungan.transaksi') }}" class="px-4 py-2 bg-white border border-gray-300 rounded-lg text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                ← Kembali
            </a>
        </div>
    </div>

    @if(session('download_struk'))
    <div class="rounded-xl bg-green-50 border border-green-200 p-4 flex items-center justify-between">
        <p class="text-green-800 font-medium">{{ session('success') }}</p>
        <!-- <a href="{{ route('admin.tabungan.print-struk-transaksi', $transaksi->id) }}" target="_blank" class="shrink-0 px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm font-medium inline-flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path></svg>
            Cetak Struk (Langsung)
        </a> -->
    </div>
    @elseif(session('success'))
    <div class="rounded-xl bg-green-50 border border-green-200 p-4">
        <p class="text-green-800 font-medium">{{ session('success') }}</p>
    </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Data Nasabah -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Data Nasabah</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <p class="text-sm text-gray-600">Nama Lengkap</p>
                        <p class="font-semibold text-gray-900">{{ $transaksi->nasabah->user->nama ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Email</p>
                        <p class="font-semibold text-gray-900">{{ $transaksi->nasabah->user->email ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nomor HP</p>
                        <p class="font-semibold text-gray-900">{{ $transaksi->nasabah->user->nomor_hp ?? 'N/A' }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">NIK</p>
                        <p class="font-semibold text-gray-900">{{ $transaksi->nasabah->dataKtp->nik ?? 'N/A' }}</p>
                    </div>
                </div>
            </div>

            <!-- Informasi Transaksi -->
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Informasi Transaksi</h2>
                <div class="space-y-4">
                    <div>
                        <p class="text-sm text-gray-600">Tanggal Transaksi</p>
                        <p class="font-semibold text-gray-900">{{ $transaksi->tgl_transaksi->format('d M Y, H:i') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Jenis</p>
                        <span class="inline-block mt-2 px-4 py-2 {{ $transaksi->jenis === 'setoran' ? 'bg-[[#674c1d]]/10 text-[[#674c1d]]' : 'bg-[[#8b6f2f]]/10 text-[[#8b6f2f]]' }} rounded-full text-sm font-semibold">
                            {{ ucfirst($transaksi->jenis) }}
                        </span>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Nominal</p>
                        <p class="font-semibold text-[[#674c1d]] text-2xl">Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Via</p>
                        <p class="font-semibold text-gray-900">{{ ucfirst($transaksi->via) }}</p>
                    </div>
                    @if($transaksi->keterangan)
                    <div>
                        <p class="text-sm text-gray-600">Keterangan</p>
                        <p class="text-gray-900 mt-1">{{ $transaksi->keterangan }}</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Bukti Foto (jika setoran dari pengajuan) -->
            @if($transaksi->jenis === 'setoran' && $transaksi->pengajuanSetor && $transaksi->pengajuanSetor->buktiFoto->count() > 0)
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Bukti Foto Transfer</h2>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    @foreach($transaksi->pengajuanSetor->buktiFoto as $bukti)
                    <div class="border border-gray-200 rounded-lg overflow-hidden group relative">
                        @if($bukti->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($bukti->file_path))
                        <img src="{{ asset('storage/' . $bukti->file_path) }}" alt="Bukti Foto" class="w-full h-48 object-cover cursor-pointer hover:opacity-90 transition-opacity"
                            onclick="openImageModal(this.src)"
                            onerror="this.onerror=null; this.src='data:image/svg+xml,%3Csvg xmlns=\'http://www.w3.org/2000/svg\' width=\'400\' height=\'200\'%3E%3Crect fill=\'%23f3f4f6\' width=\'400\' height=\'200\'/%3E%3Ctext x=\'50%25\' y=\'50%25\' text-anchor=\'middle\' dy=\'.3em\' fill=\'%239ca3af\' font-family=\'Arial\' font-size=\'14\'%3EGambar tidak dapat dimuat%3C/text%3E%3C/svg%3E';">
                        @else
                        <div class="w-full h-48 bg-gray-100 flex items-center justify-center text-gray-500 text-sm">File tidak ditemukan</div>
                        @endif
                        <div class="p-3 bg-gray-50 flex items-center justify-between">
                            <p class="text-xs text-gray-600">{{ $bukti->keterangan ?? 'Bukti Transfer' }}</p>
                            @if($bukti->file_path && \Illuminate\Support\Facades\Storage::disk('public')->exists($bukti->file_path))
                            <a href="{{ asset('storage/' . $bukti->file_path) }}" download class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                                <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                                Unduh
                            </a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
            @elseif($transaksi->jenis === 'penarikan' && $transaksi->pengajuanTarik && $transaksi->pengajuanTarik->foto_bukti_tf_admin)
            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h2 class="text-lg font-bold text-primary font-display mb-4 pb-4 border-b border-gray-200">Bukti Transfer Admin</h2>
                <div class="max-w-md border border-gray-200 rounded-lg overflow-hidden shadow-sm group relative">
                    <img src="{{ asset('storage/' . $transaksi->pengajuanTarik->foto_bukti_tf_admin) }}" alt="Bukti Transfer" class="w-full h-auto cursor-pointer hover:opacity-90 transition-opacity" onclick="openImageModal(this.src)">
                    <div class="p-3 bg-gray-50 flex items-center justify-between">
                        <span class="text-xs text-gray-500">Bukti transfer di-upload saat persetujuan</span>
                        <a href="{{ asset('storage/' . $transaksi->pengajuanTarik->foto_bukti_tf_admin) }}" download class="text-xs text-blue-600 hover:text-blue-800 font-medium flex items-center gap-1">
                            <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
                            Unduh
                        </a>
                    </div>
                </div>
            </div>
            @endif
        </div>

        <!-- Sidebar Info -->
        <div class="space-y-6">
            <!-- Admin Pengelola Card -->
            @if($transaksi->adminPengelola)
            <div class="bg-gradient-to-br from-blue-50 to-blue-100/50 rounded-2xl shadow-sm p-6 border border-blue-100">
                <h3 class="text-sm font-bold text-blue-900 font-display mb-3 uppercase tracking-wider">Diproses Oleh</h3>
                <div class="flex items-center gap-3">
                    <div class="w-12 h-12 bg-blue-600 text-white rounded-xl flex items-center justify-center font-bold text-xl shadow-inner">
                        {{ substr($transaksi->adminPengelola->nama ?? 'A', 0, 1) }}
                    </div>
                    <div>
                        <p class="font-bold text-blue-900">{{ $transaksi->adminPengelola->nama }}</p>
                        <div class="flex items-center gap-2 mt-1">
                            <span class="text-xs font-semibold text-blue-700 bg-blue-200/50 px-2 py-0.5 rounded-md">Admin</span>
                            @if($transaksi->is_petty_cash)
                            <span class="text-xs font-semibold text-amber-700 bg-amber-200/50 px-2 py-0.5 rounded-md flex items-center gap-1">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                                Petty Cash
                            </span>
                            @endif
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
                <h3 class="text-lg font-bold text-primary font-display mb-4">Informasi</h3>
                <div class="space-y-3">
                    <div>
                        <p class="text-sm text-gray-600">ID Transaksi</p>
                        <p class="font-semibold text-gray-900">#{{ $transaksi->id }}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-600">Waktu Dibuat</p>
                        <p class="font-semibold text-gray-900">{{ $transaksi->created_at->format('d M Y, H:i') }}</p>
                    </div>
                    @if($transaksi->pengajuanSetor)
                    <div>
                        <p class="text-sm text-gray-600">ID Pengajuan Setor</p>
                        <p class="font-semibold text-gray-900">#{{ $transaksi->pengajuanSetor->id }}</p>
                    </div>
                    @endif
                    @if($transaksi->pengajuanTarik)
                    <div>
                        <p class="text-sm text-gray-600">ID Pengajuan Tarik</p>
                        <p class="font-semibold text-gray-900">#{{ $transaksi->pengajuanTarik->id }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Image Lightbox Modal -->
<div id="image-modal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[110] hidden items-center justify-center p-4">
    <div class="relative max-w-4xl w-full flex flex-col items-center">
        <!-- Close Button -->
        <button onclick="closeImageModal()" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors flex items-center gap-1 font-medium bg-black/40 px-3 py-1.5 rounded-full text-sm">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
            Tutup
        </button>

        <!-- Image Container -->
        <div class="bg-white rounded-2xl p-2 shadow-2xl max-h-[80vh] overflow-hidden flex items-center justify-center">
            <img id="modal-img" src="" alt="Bukti Pembesaran" class="max-w-full max-h-[75vh] object-contain rounded-xl">
        </div>

        <!-- Download Button inside Modal -->
        <a id="modal-download-btn" href="" download class="mt-4 px-6 py-2.5 bg-blue-600 hover:bg-blue-700 text-white rounded-xl font-medium transition-colors shadow-lg flex items-center gap-2 text-sm">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4"></path></svg>
            Unduh Gambar
        </a>
    </div>
</div>

<script>
function openImageModal(src) {
    const modal = document.getElementById('image-modal');
    const modalImg = document.getElementById('modal-img');
    const downloadBtn = document.getElementById('modal-download-btn');
    
    modalImg.src = src;
    downloadBtn.href = src;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');
    document.body.style.overflow = 'hidden';
}

function closeImageModal() {
    const modal = document.getElementById('image-modal');
    modal.classList.add('hidden');
    modal.classList.remove('flex');
    document.body.style.overflow = '';
}

// Tutup ketika klik background
document.getElementById('image-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeImageModal();
    }
});

// Tutup ketika tombol Escape ditekan
document.addEventListener('keydown', function(e) {
    if (e.key === 'Escape') {
        closeImageModal();
    }
});
</script>
@endsection
