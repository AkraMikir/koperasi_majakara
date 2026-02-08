@extends('layouts.nasabah')

@section('title', 'Penarikan Tabungan')

@section('content')
<div class="w-full pb-6">
    <!-- Info Saldo -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-gradient-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-3xl shadow-2xl p-6 border-2 border-[#d4af37]/30">
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-white/80 text-sm mb-2">Saldo Tersedia</p>
                    <p class="text-4xl font-bold text-white font-display">Rp {{ number_format($tabunganInfo->saldo ?? 0, 0, ',', '.') }}</p>
                </div>
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
        </div>
    </div>
    
    <div id="saldo-data" data-saldo="{{ $tabunganInfo->saldo ?? 0 }}" style="display: none;"></div>

    <!-- Pilihan Metode -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 font-display mb-4">Pilih Metode Penarikan</h2>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Metode Tunai -->
                <button onclick="selectMethod('tunai')" id="btn-tunai" class="group p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-gray-200 hover:border-[#8b6f2f] transition-all text-left">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Tunai</h3>
                            <p class="text-sm text-gray-600">Ambil langsung di kantor</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Datang ke kantor terdekat</span>
                    </div>
                </button>

                <!-- Metode Transfer -->
                <button onclick="selectMethod('transfer')" id="btn-transfer" class="group p-6 bg-gradient-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-gray-200 hover:border-[#674c1d] transition-all text-left">
                    <div class="flex items-center gap-4 mb-3">
                        <div class="w-14 h-14 bg-gradient-to-br from-[#674c1d] to-[#4a3514] rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Transfer</h3>
                            <p class="text-sm text-gray-600">Transfer ke rekening Anda</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        <span>Masukkan data rekening</span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div id="form-section" class="mx-4 mb-6 hidden">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 font-display mb-6">Formulir Penarikan</h2>
                
            <form id="form-penarikan" method="POST" action="{{ route('nasabah.tabungan.submit-penarikan') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="metode" id="metode-input" value="{{ old('metode') }}">

                <!-- Nominal Penarikan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Penarikan *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                        <input type="text" name="nominal" id="nominal" value="{{ old('nominal', request('nominal')) }}" placeholder="0" required
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-lg font-semibold"
                            oninput="formatCurrency(this)" onblur="checkSaldo()">
                    </div>
                    <div id="saldo-warning" class="hidden mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <div class="flex items-center gap-2">
                            <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                            </svg>
                            <p class="text-sm text-red-600 font-medium">Saldo tidak mencukupi!</p>
                        </div>
                    </div>
                    @if(session('error'))
                    <div class="mt-2 p-3 bg-red-50 border border-red-200 rounded-lg">
                        <p class="text-sm text-red-600 font-medium">{{ session('error') }}</p>
                    </div>
                    @endif
                    <p class="text-xs text-gray-500 mt-2">Minimal: Rp 10.000 | Saldo tersedia: Rp {{ number_format($tabunganInfo->saldo ?? 0, 0, ',', '.') }}</p>
                </div>

                <!-- Tunai Details (for Tunai) -->
                <div id="tunai-section" class="hidden space-y-4">
                    <div class="bg-blue-50 border border-blue-200 rounded-xl p-4 mb-4">
                        <div class="flex items-start gap-3">
                            <svg class="w-5 h-5 text-blue-600 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <p class="text-blue-700 text-sm">Silakan buat janji temu untuk melakukan penarikan tunai di kantor kami.</p>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Lokasi Kantor *</label>
                        <select name="lokasi_temu" id="lokasi_temu" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                            <option value="">-- Pilih Lokasi --</option>
                            @foreach($lokasi ?? [] as $loc)
                            <option value="{{ $loc->id }}" {{ old('lokasi_temu') == $loc->id ? 'selected' : '' }}>{{ $loc->nama_lokasi }} - {{ $loc->kota }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal *</label>
                            <input type="date" name="tanggal_janji_temu" id="tanggal_janji_temu" min="{{ date('Y-m-d') }}" value="{{ old('tanggal_janji_temu') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                        </div>
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu *</label>
                            <input type="time" name="waktu_janji_temu" id="waktu_janji_temu" value="{{ old('waktu_janji_temu') }}"
                                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                            <p class="text-xs text-gray-500 mt-1">Jam operasional: 08:00 - 16:00</p>
                        </div>
                    </div>
                </div>

                <!-- Bank Details (for Transfer) -->
                <div id="bank-section" class="hidden space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nama Bank *</label>
                        <select name="nama_bank" id="nama_bank" class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                            <option value="">Pilih Bank</option>
                            @foreach(['BCA', 'BNI', 'Mandiri', 'BRI', 'CIMB Niaga', 'Permata', 'Bank Lainnya'] as $bank)
                                <option value="{{ $bank }}" {{ old('nama_bank') == $bank ? 'selected' : '' }}>{{ $bank }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Nomor Rekening *</label>
                        <input type="text" name="no_rekening" id="no_rekening" placeholder="Masukkan nomor rekening tujuan" value="{{ old('no_rekening') }}"
                            class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                        <p class="text-xs text-gray-500 mt-2">Pastikan nomor rekening sudah benar</p>
                    </div>
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none resize-none">{{ old('keterangan') }}</textarea>
                </div>
                
                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="button" onclick="showPinModal()" id="submit-btn" class="w-full py-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02] disabled:opacity-50 disabled:cursor-not-allowed">
                        Ajukan Penarikan
                    </button>
                    <a href="{{ route('nasabah.tabungan.index') }}" class="block w-full mt-3 py-3 text-center text-gray-600 hover:text-gray-800 transition-colors">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>

    <!-- Riwayat Penarikan -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-gray-900 font-display">Riwayat Penarikan</h2>
            </div>
            
            <div class="space-y-3">
                @forelse($riwayatPenarikan ?? [] as $riwayat)
                <a href="{{ route('nasabah.tabungan.detail-transaksi', $riwayat->id) }}" class="block p-4 bg-gray-50 rounded-xl border border-gray-200 hover:border-red-300 hover:shadow-md transition-all">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-red-100 rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path>
                                </svg>
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900">Penarikan</p>
                                <p class="text-sm text-gray-500">{{ $riwayat->tgl_transaksi->format('d M Y') }} • {{ $riwayat->via ? ucfirst($riwayat->via) : '-' }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <p class="font-bold text-red-600">-Rp {{ number_format(abs((float) $riwayat->nominal), 0, ',', '.') }}</p>
                            <p class="text-xs text-gray-500 font-mono">{{ $riwayat->id_transaksi ?? str_pad($riwayat->id, 5, '0', STR_PAD_LEFT) }}</p>
                        </div>
                    </div>
                    @if($riwayat->keterangan)
                    <p class="text-sm text-gray-600 mt-2 pl-13">{{ $riwayat->keterangan }}</p>
                    @endif
                </a>
                @empty
                <div class="text-center py-8">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                    <p class="text-gray-500">Belum ada riwayat penarikan</p>
                </div>
                @endforelse
            </div>
        </div>
    </div>
</div>

<!-- PIN Modal -->
<div id="pin-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
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
                    <p class="text-sm text-gray-600">Masukkan PIN Anda untuk melanjutkan</p>
                </div>
            </div>
            <button onclick="closePinModal()" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>

        <div id="pin-error" class="hidden mb-4 p-3 bg-red-50 border border-red-200 rounded-lg">
            <p class="text-red-700 text-sm"></p>
        </div>

        <div class="mb-6">
            <label class="block text-sm font-semibold text-gray-700 mb-2">PIN (6 digit)</label>
            <input type="text" id="pin-input" maxlength="6" pattern="[0-9]*" inputmode="numeric"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-center text-2xl font-mono tracking-widest"
                placeholder="••••••"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
        </div>

        <div class="flex gap-3">
            <button onclick="closePinModal()" class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button onclick="verifyAndSubmit()" class="flex-1 px-4 py-3 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all">
                Verifikasi
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Init Nominal Format (if has old value)
        const nominalInput = document.getElementById('nominal');
        if(nominalInput.value) {
            formatCurrency(nominalInput);
        }
        
        // Init Metode based on Old Value
        const oldMetode = document.getElementById('metode-input').value;
        if (oldMetode) {
            selectMethod(oldMetode);
        }
    });

    function selectMethod(metode) {
        document.getElementById('metode-input').value = metode;
        document.getElementById('form-section').classList.remove('hidden');
        
        // Update button styles
        const btnTunai = document.getElementById('btn-tunai');
        const btnTransfer = document.getElementById('btn-transfer');
        
        // Reset all
        btnTunai.classList.remove('border-[#8b6f2f]', 'bg-gradient-to-br', 'from-[#8b6f2f]/10', 'to-[#d4af37]/10');
        btnTransfer.classList.remove('border-[#674c1d]', 'bg-gradient-to-br', 'from-[#674c1d]/10', 'to-[#4a3514]/10');
        
        if (metode === 'tunai') {
            btnTunai.classList.add('border-[#8b6f2f]', 'bg-gradient-to-br', 'from-[#8b6f2f]/10', 'to-[#d4af37]/10');
            
            // Show Tunai Section & Validation
            document.getElementById('tunai-section').classList.remove('hidden');
            document.getElementById('lokasi_temu').setAttribute('required', 'required');
            document.getElementById('tanggal_janji_temu').setAttribute('required', 'required');
            document.getElementById('waktu_janji_temu').setAttribute('required', 'required');
            
            // Hide Bank Section & Remove Validation
            document.getElementById('bank-section').classList.add('hidden');
            document.getElementById('nama_bank').removeAttribute('required');
            document.getElementById('no_rekening').removeAttribute('required');
        } else {
            btnTransfer.classList.add('border-[#674c1d]', 'bg-gradient-to-br', 'from-[#674c1d]/10', 'to-[#4a3514]/10');
            
            // Hide Tunai Section & Remove Validation
            document.getElementById('tunai-section').classList.add('hidden');
            document.getElementById('lokasi_temu').removeAttribute('required');
            document.getElementById('tanggal_janji_temu').removeAttribute('required');
            document.getElementById('waktu_janji_temu').removeAttribute('required');
            
            // Show Bank Section & Validation
            document.getElementById('bank-section').classList.remove('hidden');
            document.getElementById('nama_bank').setAttribute('required', 'required');
            document.getElementById('no_rekening').setAttribute('required', 'required');
        }
        
        // Scroll to form if not page load (or maybe always)
        // document.getElementById('form-section').scrollIntoView({ behavior: 'smooth', block: 'start' });
        // NOTE: Commented scroll to prevent jump on reload
    }

    function formatCurrency(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value) {
            input.value = new Intl.NumberFormat('id-ID').format(value);
        }
    }

    function checkSaldo() {
        const nominalInput = document.getElementById('nominal');
        const nominal = parseInt(nominalInput.value.replace(/[^\d]/g, '')) || 0;
        const saldoData = document.getElementById('saldo-data');
        const saldo = parseInt(saldoData ? saldoData.dataset.saldo : 0) || 0;
        const warning = document.getElementById('saldo-warning');
        const submitBtn = document.getElementById('submit-btn');

        if (nominal > saldo) {
            warning.classList.remove('hidden');
            submitBtn.disabled = true;
        } else {
            warning.classList.add('hidden');
            submitBtn.disabled = false;
        }
    }

    // Modal Functions
    function showPinModal() {
        const form = document.getElementById('form-penarikan');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Check nominal validity (already handled by checkSaldo but good to double check)
        const nominalInput = document.getElementById('nominal');
        const nominalRaw = nominalInput.value.replace(/[^\d]/g, '');
        const nominal = parseFloat(nominalRaw);
        
        if (!nominal || nominal < 10000) {
             alert('Nominal minimal Rp 10.000');
             nominalInput.focus();
             return;
        }
        
        // Show modal
        document.getElementById('pin-modal').classList.remove('hidden');
        document.getElementById('pin-modal').classList.add('flex');
        document.getElementById('pin-input').value = '';
        document.getElementById('pin-input').focus();
    }

    function closePinModal() {
        document.getElementById('pin-modal').classList.add('hidden');
        document.getElementById('pin-modal').classList.remove('flex');
        document.getElementById('pin-input').value = '';
        document.getElementById('pin-error').classList.add('hidden');
    }

    function verifyAndSubmit() {
        console.log('=== VERIFY AND SUBMIT CALLED ===');
        const pin = document.getElementById('pin-input').value;
        console.log('PIN length:', pin.length);
        
        if (pin.length !== 6) {
            showPinError('PIN harus 6 digit');
            return;
        }

        const form = document.getElementById('form-penarikan');
        const nominalInput = document.getElementById('nominal');
        
        console.log('Form found:', !!form);
        console.log('Nominal before unformat:', nominalInput.value);
        
        // Unformat nominal before submit
        nominalInput.value = nominalInput.value.replace(/[^\d]/g, '');
        console.log('Nominal after unformat:', nominalInput.value);

        // Create hidden input for PIN
        // (Make sure to remove old one if exists or just append)
        let pinInput = form.querySelector('input[name="pin"]');
        if (!pinInput) {
            pinInput = document.createElement('input');
            pinInput.type = 'hidden';
            pinInput.name = 'pin';
            form.appendChild(pinInput);
            console.log('Created new PIN input');
        }
        pinInput.value = pin;
        console.log('PIN input value set:', pinInput.value);

        console.log('Form data before submit:');
        const formData = new FormData(form);
        for (let [key, value] of formData.entries()) {
            console.log(key + ':', value);
        }

        console.log('Submitting form...');
        form.submit();
    }
    
    function showPinError(message) {
        const errorDiv = document.getElementById('pin-error');
        errorDiv.querySelector('p').textContent = message;
        errorDiv.classList.remove('hidden');
    }

    // Modal Events
    document.getElementById('pin-modal').addEventListener('click', function(e) {
        if (e.target === this) closePinModal();
    });
    
    document.getElementById('pin-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') verifyAndSubmit();
    });
</script>
@endpush
@endsection
