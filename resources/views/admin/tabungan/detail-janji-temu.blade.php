@extends('layouts.admin')

@section('title', 'Detail Janji Temu')

@section('content')
<div class="space-y-6">
    <!-- Back Button -->
    <div>
        <a href="{{ route('admin.janji-temu.index') }}" class="inline-flex items-center text-gray-500 hover:text-[#674c1d] transition-colors">
            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali ke Janji Temu
        </a>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Main Content -->
        <div class="lg:col-span-2 space-y-6">
            <!-- Data Nasabah Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 relative overflow-hidden">
                <div class="absolute top-0 right-0 w-32 h-32 bg-[#674c1d]/5 rounded-full -mr-16 -mt-16"></div>
                
                <h2 class="text-xl font-bold text-gray-900 font-display mb-6 relative">Informasi Nasabah</h2>
                
                <div class="flex items-start gap-6 relative">
                    <div class="shrink-0">
                        <div class="w-20 h-20 rounded-2xl bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] flex items-center justify-center shadow-lg transform rotate-3">
                            <span class="text-3xl font-bold text-white font-display">
                                {{ substr($janjiTemu->nasabah->user->nama, 0, 1) }}
                            </span>
                        </div>
                    </div>
                    
                    <div class="space-y-4 flex-1">
                        <div>
                            <p class="text-sm text-gray-500 mb-1">Nama Lengkap</p>
                            <p class="font-bold text-gray-900 text-lg">{{ $janjiTemu->nasabah->user->nama }}</p>
                        </div>
                        
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Email</p>
                                <p class="font-medium text-gray-900">{{ $janjiTemu->nasabah->user->email }}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500 mb-1">Nomor Telepon</p>
                                <p class="font-medium text-gray-900">{{ $janjiTemu->nasabah->user->nomor_hp ?? '-' }}</p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Janji Temu Detail Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h2 class="text-xl font-bold text-gray-900 font-display mb-6">Detail Janji Temu</h2>

                <div class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="text-sm text-gray-500 mb-1">Tanggal & Waktu</p>
                            <p class="font-bold text-gray-900 text-lg">
                                {{ $janjiTemu->tanggal_janji_temu->format('d M Y') }}
                            </p>
                            <p class="text-sm text-[#674c1d] font-medium mt-1">
                                Pukul {{ $janjiTemu->waktu_janji_temu ? \Carbon\Carbon::parse($janjiTemu->waktu_janji_temu)->format('H:i') : '-' }} WIB
                            </p>
                        </div>
                        
                        <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                            <p class="text-sm text-gray-500 mb-1">Nominal {{ ($janjiTemu->jenis ?? 'setoran') == 'setoran' ? 'Setoran' : 'Penarikan' }}</p>
                            <p class="font-bold text-gray-900 text-lg">
                                Rp {{ number_format($janjiTemu->transTabungan?->nominal ?? $janjiTemu->nominal, 0, ',', '.') }}
                            </p>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-sm text-gray-500 mb-2">Lokasi Pertemuan</p>
                        <div class="flex items-start gap-3">
                            <div class="shrink-0 mt-1">
                                <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-medium text-gray-900">
                                    {{ is_string($janjiTemu->lokasi) ? $janjiTemu->lokasi : ($janjiTemu->lokasi->nama_lokasi ?? '-') }}
                                </p>
                                @if(isset($janjiTemu->lokasi) && is_object($janjiTemu->lokasi))
                                <p class="text-sm text-gray-500 mt-1">{{ $janjiTemu->lokasi->alamat_lengkap ?? '' }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-sm text-gray-500 mb-2">Peta Lokasi</p>
                        <div class="rounded-xl overflow-hidden border border-gray-200 w-full mt-2">
                            <iframe src="https://www.google.com/maps/embed?pb=!4v1771057242792!6m8!1m7!1sTDnmeXtVvimBtQeXmqSSCQ!2m2!1d-6.267415399913648!2d106.9806162945405!3f247.41483905689947!4f-35.52001210835799!5f0.7820865974627469" width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy" referrerpolicy="no-referrer-when-downgrade" title="Peta Lokasi Janji Temu"></iframe>
                        </div>
                    </div>

                    <div class="p-4 bg-gray-50 rounded-xl border border-gray-100">
                        <p class="text-sm text-gray-500 mb-2">Pesan / Keterangan</p>
                        <p class="text-gray-700 italic">"{{ $janjiTemu->keterangan ?? 'Tidak ada keterangan' }}"</p>
                    </div>

                    @if($janjiTemu->buktiFoto->count() > 0)
                    <div class="pt-4 border-t border-gray-100">
                        <p class="text-sm font-bold text-gray-900 mb-3 flex items-center gap-2">
                            <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                            </svg>
                            Bukti Pertemuan / Transaksi
                        </p>
                        <div class="grid grid-cols-2 sm:grid-cols-3 gap-4">
                            @foreach($janjiTemu->buktiFoto as $bukti)
                            <div class="group relative aspect-square rounded-xl overflow-hidden border border-gray-200 cursor-pointer hover:ring-2 hover:ring-[#674c1d] transition-all"
                                data-preview-src="{{ asset('storage/' . $bukti->file_path) }}"
                                data-preview-label="Bukti #{{ $loop->iteration }}"
                                onclick="showPhotoPreview(this.dataset.previewSrc, this.dataset.previewLabel)">
                                <img src="{{ asset('storage/' . $bukti->file_path) }}" class="w-full h-full object-cover transition-transform group-hover:scale-110">
                                <div class="absolute inset-0 bg-black/40 opacity-0 group-hover:opacity-100 flex items-center justify-center transition-opacity">
                                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0zM10 7v3m0 0v3m0-3h3m-3 0H7"></path>
                                    </svg>
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                    @endif

                    @if($janjiTemu->status == '2')
                    <div class="p-4 bg-green-50 rounded-xl border border-green-100">
                        <div class="flex items-center gap-3 mb-2">
                            <div class="w-8 h-8 rounded-full bg-green-100 flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                                </svg>
                            </div>
                            <p class="font-bold text-green-800">Selesai Diproses</p>
                        </div>
                        <p class="text-sm text-green-700 ml-11">{{ $janjiTemu->keterangan_admin }}</p>
                    </div>
                    @endif
                </div>
            </div>
        </div>

        <!-- Sidebar Actions (sticky) -->
        <div class="space-y-6 lg:sticky lg:top-6 lg:self-start">
            @if($janjiTemu->status == '1')
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <h3 class="font-bold text-gray-900 mb-4">Proses Janji Temu</h3>
                    @if(session('error'))
                    <div class="bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-xl flex items-center gap-3 mb-4">
                        <svg class="w-5 h-5 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        <div>
                            <p class="text-sm font-bold">{{ session('error') }}</p>
                            @if(str_contains(session('error'), 'Saldo Petty Cash'))
                            <p class="text-xs mt-1">Saldo Petty Cash Anda saat ini: <span class="font-bold">Rp {{ number_format($adminSaldo, 0, ',', '.') }}</span></p>
                            @endif
                        </div>
                    </div>
                    @endif

                    @if($janjiTemu->status == '1')
                    <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 mb-4">
                        <div class="flex items-center gap-3">
                            <div class="p-2 bg-blue-100 rounded-lg text-blue-600">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="text-sm font-bold text-blue-900 italic">Info Petty Cash</p>
                                @if(($janjiTemu->jenis ?? 'setoran') == 'setoran')
                                <p class="text-xs text-blue-700">Setoran tunai ini akan otomatis **menambah** saldo Petty Cash Admin.</p>
                                @else
                                <p class="text-xs text-blue-700">Penarikan tunai ini akan **mengurangi** saldo Petty Cash Admin Anda.</p>
                                <p class="text-[10px] text-blue-600 mt-0.5">Saldo Anda: Rp {{ number_format($adminSaldo, 0, ',', '.') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endif

                    <form action="{{ route('admin.tabungan.create-trans-from-janji-temu', $janjiTemu->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    
                    <div class="space-y-4">
                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Nominal {{ ($janjiTemu->jenis ?? 'setoran') == 'setoran' ? 'Diterima' : 'Diserahkan' }} <span class="text-red-500">*</span></label>
                            <div class="relative">
                                <span class="absolute left-4 top-3 text-gray-500 font-medium">Rp</span>
                                <input type="text" name="nominal" id="nominal" value="{{ number_format($janjiTemu->nominal, 0, ',', '.') }}" 
                                    required
                                    class="w-full pl-10 pr-4 py-2.5 bg-white border border-[#674c1d]/40 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none font-bold text-gray-900 cursor-text"
                                    placeholder="Klik untuk mengedit nominal"
                                    oninput="formatCurrency(this)"
                                    title="Dapat diedit jika nominal belum sesuai dengan yang {{ ($janjiTemu->jenis ?? 'setoran') == 'setoran' ? 'diterima' : 'diserahkan' }}.">
                            </div>
                            <p class="text-xs text-gray-500 mt-1">Default: Rp {{ number_format($janjiTemu->nominal, 0, ',', '.') }}. Edit jika nominal berbeda.</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Bukti Foto (Wajib)</label>
                            <input type="file" name="foto_penerimaan[]" multiple 
                                class="w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-[#674c1d]/10 file:text-[#674c1d] hover:file:bg-[#674c1d]/20 transition-all">
                            <p class="text-xs text-gray-500 mt-1">Bisa upload foto uang / kwitansi / bukti {{ ($janjiTemu->jenis ?? 'setoran') == 'setoran' ? 'penerimaan' : 'penyerahan' }}</p>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan Admin</label>
                            <textarea name="keterangan_admin" rows="3" 
                                class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent outline-none"
                                placeholder="Catatan tambahan..."></textarea>
                        </div>

                        @php
                            $isPenarikan = ($janjiTemu->jenis ?? 'setoran') == 'penarikan';
                            $saldoKurang = $isPenarikan && $adminSaldo < $janjiTemu->nominal;
                            $confirmMsg = !$isPenarikan
                                ? 'Apakah Anda yakin uang sudah diterima? Transaksi akan dibuat otomatis.'
                                : 'Apakah Anda yakin uang sudah diserahkan? Transaksi akan dibuat otomatis.';
                        @endphp
                        
                        @if($saldoKurang)
                        <div class="w-full py-3 bg-red-100 text-red-700 font-bold rounded-xl text-center border border-red-300">
                            ❌ Saldo Petty Cash Tidak Mencukupi
                        </div>
                        @else
                        <button type="submit" data-confirm-message="{{ $confirmMsg }}" onclick="return confirm(this.dataset.confirmMessage)" 
                            class="w-full py-3 bg-[#674c1d] hover:bg-[#543d16] text-white font-bold rounded-xl shadow-lg shadow-[#674c1d]/20 transition-all transform hover:-translate-y-1">
                            ✅ Selesaikan & Buat Transaksi
                        </button>
                        @endif
                    </div>
                </form>
            </div>
            @endif

            <div class="bg-[#674c1d] rounded-2xl shadow-lg p-6 text-white relative overflow-hidden" x-data="{ openCancel: false }">
                <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -mr-10 -mt-10"></div>
                <div class="relative z-10">
                    <h3 class="font-bold text-lg mb-2">Status Saat Ini</h3>
                    @if($janjiTemu->status == '1')
                        <span class="inline-flex px-4 py-1.5 rounded-full bg-yellow-400/20 border border-yellow-400/30 text-yellow-100 font-semibold text-sm backdrop-blur-sm">
                            Menunggu Pertemuan
                        </span>
                        <p class="mt-4 text-sm text-white/80">
                            Masih menunggu pertemuan dengan nasabah. Pastikan waktu dan lokasi sudah sesuai.
                        </p>

                        {{-- Tombol Batalkan --}}
                        <div class="mt-6 pt-4 border-t border-white/10">
                            <button @click="openCancel = true"
                                class="w-full px-4 py-2 bg-red-500/20 hover:bg-red-500/40 border border-red-500/30 text-white rounded-xl transition-all text-sm font-bold flex items-center justify-center gap-2">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                                </svg>
                                Batalkan Janji Temu
                            </button>
                        </div>

                        {{-- Modal Konfirmasi Batal --}}
                        <div x-show="openCancel"
                            class="fixed inset-0 z-50 flex items-center justify-center bg-black/50 p-4 text-gray-900"
                            x-cloak>
                            <div class="bg-white rounded-2xl shadow-xl max-w-md w-full p-6" @click.away="openCancel = false">
                                <h3 class="text-xl font-bold mb-2">Konfirmasi Pembatalan</h3>
                                <p class="text-sm text-gray-600 mb-6">Harap berikan alasan pembatalan janji temu tabungan ini. Alasan ini akan tercatat dalam sistem.</p>

                                <form action="{{ route('admin.janji-temu.cancel-tabungan', $janjiTemu->id) }}" method="POST">
                                    @csrf
                                    <div class="mb-6">
                                        <label class="block text-sm font-medium text-gray-700 mb-2">Alasan Pembatalan <span class="text-red-500">*</span></label>
                                        <textarea name="keterangan_admin" required rows="3"
                                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-red-500 focus:border-red-500 outline-none resize-none"
                                            placeholder="Contoh: Nasabah tidak datang atau meminta penjadwalan ulang..."></textarea>
                                    </div>
                                    <div class="flex gap-3">
                                        <button type="button" @click="openCancel = false"
                                            class="flex-1 px-4 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 font-medium">Kembali</button>
                                        <button type="submit"
                                            class="flex-1 px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 font-medium">Konfirmasi Batal</button>
                                    </div>
                                </form>
                            </div>
                        </div>

                    @elseif($janjiTemu->status == '2')
                        <span class="inline-flex px-4 py-1.5 rounded-full bg-green-400/20 border border-green-400/30 text-green-100 font-semibold text-sm backdrop-blur-sm">
                            Selesai
                        </span>
                        <p class="mt-4 text-sm text-white/80">
                            Pertemuan telah selesai dan dana telah diproses {{ ($janjiTemu->jenis ?? 'setoran') == 'setoran' ? 'ke' : 'dari' }} tabungan nasabah.
                        </p>
                    @else
                        <span class="inline-flex px-4 py-1.5 rounded-full bg-red-400/20 border border-red-400/30 text-red-100 font-semibold text-sm backdrop-blur-sm">
                            Dibatalkan
                        </span>
                        @if($janjiTemu->keterangan_admin)
                        <p class="mt-3 text-sm text-white/80">{{ $janjiTemu->keterangan_admin }}</p>
                        @endif
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>


<!-- Photo Preview Modal -->
<div id="photoPreviewModal" class="fixed inset-0 bg-black/80 backdrop-blur-sm z-[9999] hidden flex items-center justify-center p-4" onclick="closePhotoPreview()">
    <div class="relative max-w-4xl w-full" onclick="event.stopPropagation()">
        <button onclick="closePhotoPreview()" class="absolute -top-12 right-0 text-white hover:text-gray-300 transition-colors">
            <svg class="w-10 h-10" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
        </button>
        <div class="bg-white rounded-2xl overflow-hidden shadow-2xl">
            <div class="p-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] flex justify-between items-center">
                <h3 id="photoTitle" class="text-white font-bold text-lg"></h3>
            </div>
            <div class="p-4 bg-gray-50 max-h-[80vh] overflow-auto">
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

document.addEventListener('keydown', function(event) {
    if (event.key === 'Escape') {
        closePhotoPreview();
    }
});

// Format nominal saat diketik (titik ribuan)
function formatCurrency(input) {
    var value = input.value.replace(/[^\d]/g, '');
    if (value) {
        input.value = new Intl.NumberFormat('id-ID').format(value);
    }
}

// Saat submit, kirim nominal dalam bentuk angka (tanpa titik)
var formProses = document.querySelector('form[action*="create-trans-from-janji-temu"]');
if (formProses) {
    formProses.addEventListener('submit', function(e) {
        var nominalInput = document.getElementById('nominal');
        if (nominalInput) {
            var value = nominalInput.value.replace(/[^\d]/g, '');
            var hidden = document.createElement('input');
            hidden.type = 'hidden';
            hidden.name = 'nominal';
            hidden.value = value;
            nominalInput.removeAttribute('name');
            this.appendChild(hidden);
        }
    });
}
</script>
@endsection
