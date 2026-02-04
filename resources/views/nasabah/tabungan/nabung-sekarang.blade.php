@extends('layouts.nasabah')

@section('title', 'Nabung Sekarang')

@section('content')
<div class="w-full pb-6">
    <!-- Back Button -->
    <div class="mx-4 mt-4 mb-4">
        <a href="{{ route('nasabah.tabungan.index') }}" 
            class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-xl shadow hover:shadow-md transition-all text-gray-700 hover:text-[#674c1d]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Pilihan Metode -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-[#674c1d] font-display">Pilih Metode Setoran</h2>
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Metode Transfer -->
                <button onclick="selectMethod('transfer')" id="btn-transfer" class="group p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-gray-200 hover:border-[#8b6f2f] transition-all text-left">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Transfer</h3>
                            <p class="text-sm text-gray-600">Transfer via bank/mobile banking</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                        </svg>
                        <span>Upload bukti transfer</span>
                    </div>
                </button>

                <!-- Metode Tunai -->
                <button onclick="selectMethod('tunai')" id="btn-tunai" class="group p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-gray-200 hover:border-[#674c1d] transition-all text-left">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#674c1d] to-[#4a3514] rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Tunai</h3>
                            <p class="text-sm text-gray-600">Setor langsung di kantor</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Buat janji temu</span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Form Transfer -->
    <div id="form-transfer-section" class="mx-4 mb-6 hidden">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 font-display mb-6">Formulir Setoran Transfer</h2>
            
            <form id="form-transfer" method="POST" action="{{ route('nasabah.tabungan.submit-setoran') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="metode" value="transfer">

                <!-- Nominal Setoran -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Setoran *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                        <input type="text" name="nominal" id="nominal-transfer" placeholder="0" required
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-lg font-semibold"
                            oninput="formatCurrency(this)">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Minimal setoran: Rp 10.000</p>
                </div>

                <!-- Upload Bukti Transfer -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-3">Upload Bukti Transfer *</label>
                    <div id="bukti-container" class="space-y-3"></div>
                    <button type="button" onclick="addBuktiField()" 
                        class="mt-3 inline-flex items-center gap-2 px-4 py-2 bg-[#674c1d] hover:bg-[#4a3514] text-white text-sm font-semibold rounded-lg transition-colors">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                        </svg>
                        Tambah Bukti Transfer
                    </button>
                    <p class="text-xs text-gray-500 mt-2">Format: JPG, PNG, JPEG (Max 5MB per file). Upload minimal 1 bukti transfer.</p>
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none resize-none"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="button" onclick="showPinModalTransfer()" class="w-full py-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]">
                        Ajukan Setoran
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Form Tunai (Janji Temu) -->
    <div id="form-tunai-section" class="mx-4 mb-6 hidden">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 font-display mb-6">Formulir Janji Temu (Setoran Tunai)</h2>
            
            <form id="form-tunai" method="POST" action="{{ route('nasabah.tabungan.submit-janji-temu') }}" class="space-y-6">
                @csrf

                <!-- Nominal Setoran -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Setoran *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                        <input type="text" name="nominal" id="nominal-tunai" placeholder="0" required
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-lg font-semibold"
                            oninput="formatCurrency(this)">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Minimal setoran: Rp 10.000</p>
                </div>

                <!-- Pilih Lokasi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Lokasi Kantor *</label>
                    <select name="lokasi_temu" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($lokasi ?? [] as $loc)
                        <option value="{{ $loc->id }}">{{ $loc->nama_lokasi }} - {{ $loc->kota }}, {{ $loc->provinsi }}</option>
                        @endforeach
                    </select>
                    @if($lokasi && $lokasi->count() > 0)
                    <div class="mt-3 space-y-2">
                        @foreach($lokasi as $loc)
                        <div class="p-3 bg-gray-50 rounded-lg border border-gray-200">
                            <p class="font-semibold text-gray-900">{{ $loc->nama_lokasi }}</p>
                            <p class="text-sm text-gray-600">{{ $loc->alamat_lengkap }}</p>
                            <p class="text-xs text-gray-500">{{ $loc->kota }}, {{ $loc->provinsi }}</p>
                        </div>
                        @endforeach
                    </div>
                    @endif
                </div>

                <!-- Tanggal Janji Temu -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Janji Temu *</label>
                    <input type="date" name="tanggal_janji_temu" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                    <p class="text-xs text-gray-500 mt-2">Pilih tanggal minimal besok</p>
                </div>

                <!-- Waktu Janji Temu -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Janji Temu *</label>
                    <input type="time" name="waktu_janji_temu" required 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                    <p class="text-xs text-gray-500 mt-2">Jam operasional: 08:00 - 16:00</p>
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none resize-none"></textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="button" onclick="showPinModalTunai()" class="w-full py-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]">
                        Buat Janji Temu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Riwayat Setoran -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center justify-between mb-6">
                <div class="flex items-center gap-3">
                    <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                        <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <h2 class="text-lg font-bold text-[#674c1d] font-display">Riwayat Setoran</h2>
                </div>
            </div>
            
            <div class="space-y-3">
                @forelse($riwayatTabungan ?? [] as $riwayat)
                <a href="{{ route('nasabah.tabungan.detail-transaksi', $riwayat->id) }}" class="block p-4 bg-gray-50 rounded-xl border border-gray-200 hover:border-[#674c1d]/30 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Setoran</p>
                                <p class="text-sm text-gray-500">{{ $riwayat->tgl_transaksi->format('d M Y') }}</p>
                            </div>
                        </div>
                        <div class="text-right flex items-center gap-2">
                            <div>
                                <p class="font-bold text-green-600">+Rp {{ number_format(abs((float) $riwayat->nominal), 0, ',', '.') }}</p>
                                <p class="text-xs text-gray-500 font-mono">{{ $riwayat->id }}</p>
                            </div>
                            <svg class="w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </div>
                    </div>
                </a>
                @empty
                <div class="text-center py-12">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-gray-500">Belum ada riwayat setoran</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- PIN Modal Transfer -->
<div id="pin-modal-transfer" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Verifikasi PIN</h3>
                    <p class="text-sm text-gray-600">Masukkan PIN Anda</p>
                </div>
            </div>
            <button onclick="closePinModalTransfer()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">PIN (6 Digit)</label>
            <input type="password" id="pin-input-transfer" maxlength="6" placeholder="••••••"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-center text-2xl font-bold tracking-widest"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            <p id="pin-error-transfer" class="hidden text-sm text-red-600 mt-2">PIN salah, silakan coba lagi</p>
        </div>
        
        <button onclick="submitFormTransfer()" class="w-full py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold hover:shadow-lg transition-all">
            Konfirmasi
        </button>
    </div>
</div>

<!-- PIN Modal Tunai -->
<div id="pin-modal-tunai" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Verifikasi PIN</h3>
                    <p class="text-sm text-gray-600">Masukkan PIN Anda</p>
                </div>
            </div>
            <button onclick="closePinModalTunai()" class="text-gray-400 hover:text-gray-600">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">PIN (6 Digit)</label>
            <input type="password" id="pin-input-tunai" maxlength="6" placeholder="••••••"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-center text-2xl font-bold tracking-widest"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
            <p id="pin-error-tunai" class="hidden text-sm text-red-600 mt-2">PIN salah, silakan coba lagi</p>
        </div>
        
        <button onclick="submitFormTunai()" class="w-full py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold hover:shadow-lg transition-all">
            Konfirmasi
        </button>
    </div>
</div>

@push('scripts')
<script>
    let selectedMethod = null;
    let buktiCount = 0;

    function selectMethod(method) {
        selectedMethod = method;
        
        // Update button styles
        document.querySelectorAll('[id^="btn-"]').forEach(btn => {
            btn.classList.remove('border-[#674c1d]', 'border-[#8b6f2f]', 'bg-gradient-to-br', 'from-[#674c1d]/10', 'to-[#8b6f2f]/10');
            btn.classList.add('border-gray-200');
        });
        
        // Hide all forms
        document.getElementById('form-transfer-section').classList.add('hidden');
        document.getElementById('form-tunai-section').classList.add('hidden');
        
        if (method === 'transfer') {
            document.getElementById('btn-transfer').classList.add('border-[#8b6f2f]', 'bg-gradient-to-br', 'from-[#8b6f2f]/10', 'to-[#d4af37]/10');
            document.getElementById('form-transfer-section').classList.remove('hidden');
            
            // Add initial bukti field if not exists
            if (buktiCount === 0) {
                addBuktiField();
            }
        } else {
            document.getElementById('btn-tunai').classList.add('border-[#674c1d]', 'bg-gradient-to-br', 'from-[#674c1d]/10', 'to-[#8b6f2f]/10');
            document.getElementById('form-tunai-section').classList.remove('hidden');
        }
        
        // Scroll to form
        setTimeout(() => {
            const formSection = method === 'transfer' ? 'form-transfer-section' : 'form-tunai-section';
            document.getElementById(formSection).scrollIntoView({ behavior: 'smooth', block: 'nearest' });
        }, 100);
    }

    function addBuktiField() {
        buktiCount++;
        const container = document.getElementById('bukti-container');
        const div = document.createElement('div');
        div.className = 'relative';
        div.innerHTML = `
            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border-2 border-gray-200">
                <div class="flex-shrink-0 w-10 h-10 bg-[#674c1d] rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <input type="file" name="bukti_foto[]" accept="image/*" required
                        class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#674c1d] file:text-white hover:file:bg-[#4a3514] cursor-pointer">
                    <p class="text-xs text-gray-500 mt-1">Max 5MB (JPG, PNG, JPEG)</p>
                </div>
                ${buktiCount > 1 ? `
                <button type="button" onclick="this.parentElement.parentElement.remove(); buktiCount--;" 
                    class="flex-shrink-0 w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg flex items-center justify-center transition-colors">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
                ` : ''}
            </div>
        `;
        container.appendChild(div);
    }

    function formatCurrency(input) {
        let value = input.value.replace(/[^0-9]/g, '');
        if (value) {
            value = parseInt(value).toLocaleString('id-ID');
        }
        input.value = value;
    }

    function showPinModalTransfer() {
        const form = document.getElementById('form-transfer');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        document.getElementById('pin-modal-transfer').classList.remove('hidden');
        document.getElementById('pin-modal-transfer').classList.add('flex');
        document.getElementById('pin-input-transfer').focus();
    }

    function closePinModalTransfer() {
        document.getElementById('pin-modal-transfer').classList.add('hidden');
        document.getElementById('pin-modal-transfer').classList.remove('flex');
        document.getElementById('pin-input-transfer').value = '';
        document.getElementById('pin-error-transfer').classList.add('hidden');
    }

    async function submitFormTransfer() {
        const pin = document.getElementById('pin-input-transfer').value;
        
        if (pin.length !== 6) {
            document.getElementById('pin-error-transfer').textContent = 'PIN harus 6 digit';
            document.getElementById('pin-error-transfer').classList.remove('hidden');
            return;
        }

        try {
            const response = await fetch('{{ route("nasabah.tabungan.verify-pin") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ pin: pin })
            });

            const data = await response.json();

            if (data.success) {
                // Clean nominal (remove separators) before submit
                const nominalInput = document.getElementById('nominal-transfer');
                const cleanNominal = nominalInput.value.replace(/[^0-9]/g, '');
                nominalInput.value = cleanNominal;
                
                // Add PIN to form
                const pinInput = document.createElement('input');
                pinInput.type = 'hidden';
                pinInput.name = 'pin';
                pinInput.value = pin;
                document.getElementById('form-transfer').appendChild(pinInput);
                
                // Submit form
                document.getElementById('form-transfer').submit();
            } else {
                document.getElementById('pin-error-transfer').textContent = data.message || 'PIN salah';
                document.getElementById('pin-error-transfer').classList.remove('hidden');
            }
        } catch (error) {
            document.getElementById('pin-error-transfer').textContent = 'Terjadi kesalahan, coba lagi';
            document.getElementById('pin-error-transfer').classList.remove('hidden');
        }
    }

    function showPinModalTunai() {
        const form = document.getElementById('form-tunai');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }
        
        document.getElementById('pin-modal-tunai').classList.remove('hidden');
        document.getElementById('pin-modal-tunai').classList.add('flex');
        document.getElementById('pin-input-tunai').focus();
    }

    function closePinModalTunai() {
        document.getElementById('pin-modal-tunai').classList.add('hidden');
        document.getElementById('pin-modal-tunai').classList.remove('flex');
        document.getElementById('pin-input-tunai').value = '';
        document.getElementById('pin-error-tunai').classList.add('hidden');
    }

    async function submitFormTunai() {
        const pin = document.getElementById('pin-input-tunai').value;
        
        if (pin.length !== 6) {
            document.getElementById('pin-error-tunai').textContent = 'PIN harus 6 digit';
            document.getElementById('pin-error-tunai').classList.remove('hidden');
            return;
        }

        try {
            const response = await fetch('{{ route("nasabah.tabungan.verify-pin") }}', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': '{{ csrf_token() }}'
                },
                body: JSON.stringify({ pin: pin })
            });

            const data = await response.json();

            if (data.success) {
                // Clean nominal (remove separators) before submit
                const nominalInput = document.getElementById('nominal-tunai');
                const cleanNominal = nominalInput.value.replace(/[^0-9]/g, '');
                nominalInput.value = cleanNominal;
                
                // Add PIN to form
                const pinInput = document.createElement('input');
                pinInput.type = 'hidden';
                pinInput.name = 'pin';
                pinInput.value = pin;
                document.getElementById('form-tunai').appendChild(pinInput);
                
                // Submit form
                document.getElementById('form-tunai').submit();
            } else {
                document.getElementById('pin-error-tunai').textContent = data.message || 'PIN salah';
                document.getElementById('pin-error-tunai').classList.remove('hidden');
            }
        } catch (error) {
            document.getElementById('pin-error-tunai').textContent = 'Terjadi kesalahan, coba lagi';
            document.getElementById('pin-error-tunai').classList.remove('hidden');
        }
    }

    // Enter key handler
    document.addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            const transferModal = document.getElementById('pin-modal-transfer');
            const tunaiModal = document.getElementById('pin-modal-tunai');
            
            if (transferModal.classList.contains('flex')) {
                submitFormTransfer();
            } else if (tunaiModal.classList.contains('flex')) {
                submitFormTunai();
            }
        }
    });
</script>
@endpush
@endsection
