<!-- resources/views/nasabah/tabungan/form-setoran.blade.php -->
<div id="form-container-inner" class="space-y-6">
    <!-- Pilihan Metode -->
    <div id="metode-selection" class="mb-6">
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
    <div id="form-transfer-section" class="mb-6 hidden">
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
    <div id="form-tunai-section" class="mb-6 hidden">
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
                    @if(isset($lokasi) && $lokasi->count() > 0)
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
    <div class="mb-6">
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
