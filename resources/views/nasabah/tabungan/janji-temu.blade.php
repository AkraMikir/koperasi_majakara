@extends('layouts.nasabah')

@section('title', 'Janji Temu')

@section('content')
<div class="w-full pb-6">
    <!-- Header Section -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-linear-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-3xl shadow-2xl p-6 border-2 border-[#d4af37]/30">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white font-display mb-1">Buat Janji Temu</h1>
                    <p class="text-white/90 text-sm">Atur waktu untuk setoran tunai di kantor</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Janji Temu -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-red-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-red-700 text-sm">{{ session('error') }}</p>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <div class="flex items-start gap-3">
                    <svg class="w-5 h-5 text-red-600 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <div class="flex-1">
                        <p class="text-red-700 font-semibold mb-2">Terjadi kesalahan:</p>
                        <ul class="list-disc list-inside space-y-1 text-red-700 text-sm">
                            @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                </div>
            </div>
            @endif

            @if(session('success'))
            <div class="mb-6 p-4 bg-green-50 border border-green-200 rounded-xl">
                <div class="flex items-center gap-3">
                    <svg class="w-5 h-5 text-green-600 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    <p class="text-green-700 text-sm">{{ session('success') }}</p>
                </div>
            </div>
            @endif

            <form method="POST" action="{{ route('nasabah.tabungan.submit-janji-temu') }}" class="space-y-6" id="form-janji-temu">
                @csrf

                <!-- Nominal Setoran -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Setoran *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                        <input type="text" name="nominal" id="nominal" value="{{ old('nominal', request('nominal')) }}" placeholder="0" required
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-lg font-semibold"
                            oninput="formatCurrency(this)">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Minimal setoran: Rp 10.000</p>
                    @error('nominal')
                    <p class="text-xs text-red-600 mt-1">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Pilih Lokasi -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Lokasi Kantor *</label>
                    <select name="lokasi_temu" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($lokasi ?? [] as $loc)
                        <option value="{{ $loc->id }}" {{ old('lokasi_temu') == $loc->id ? 'selected' : '' }}>{{ $loc->nama_lokasi }} - {{ $loc->kota }}, {{ $loc->provinsi }}</option>
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
                    <input type="date" name="tanggal_janji_temu" value="{{ old('tanggal_janji_temu') }}" required min="{{ date('Y-m-d', strtotime('+1 day')) }}" 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                    <p class="text-xs text-gray-500 mt-2">Pilih tanggal minimal besok</p>
                </div>

                <!-- Waktu Janji Temu -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Janji Temu *</label>
                    <input type="time" name="waktu_janji_temu" value="{{ old('waktu_janji_temu') }}" required 
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                    <p class="text-xs text-gray-500 mt-2">Jam operasional: 08:00 - 16:00</p>
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan Tambahan</label>
                    <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none resize-none">{{ old('keterangan', request('keterangan')) }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="button" onclick="showPinModal()" class="w-full py-4 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]">
                        Buat Janji Temu
                    </button>
                    <a href="{{ route('nasabah.tabungan.nabung-sekarang') }}" class="block w-full mt-3 py-3 text-center text-gray-600 hover:text-gray-800 transition-colors">
                        Kembali
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- PIN Modal -->
<div id="pin-modal" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-50 hidden items-center justify-center p-4">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6 transform transition-all">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
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
            <input type="password" id="pin-input" maxlength="6" pattern="[0-9]*" inputmode="numeric"
                class="w-full px-4 py-5 border-2 border-gray-100 rounded-2xl focus:border-[#674c1d] focus:ring-4 focus:ring-[#674c1d]/10 outline-none text-center text-3xl font-bold tracking-[0.5em] transition-all bg-gray-50 focus:bg-white"
                placeholder="••••••"
                oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length === 6) verifyAndSubmit();">
        </div>

        <div class="flex gap-3">
            <button onclick="closePinModal()" class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button onclick="verifyAndSubmit()" id="btn-verify-submit" class="flex-1 px-4 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all">
                Verifikasi
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    function formatCurrency(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value) {
            input.value = new Intl.NumberFormat('id-ID').format(value);
        } else {
            input.value = '';
        }
    }

    function showPinModal() {
        // Validate form first
        const form = document.getElementById('form-janji-temu');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Check if nominal exists and valid
        const nominalInput = form.querySelector('input[name="nominal"]');
        const nominalRaw = nominalInput.value.replace(/[^\d]/g, '');
        const nominal = parseFloat(nominalRaw);
        
        if (!nominal || isNaN(nominal) || nominal < 10000) {
            alert('Nominal minimal Rp 10.000');
            nominalInput.focus();
            return;
        }

        // Convert formatted currency to number before submit (update hidden value if needed)
        // The actual conversion will be done in verifyAndSubmit
        
        // Show modal
        document.getElementById('pin-modal').classList.remove('hidden');
        document.getElementById('pin-modal').classList.add('flex');
        document.getElementById('pin-input').focus();
    }

    function closePinModal() {
        document.getElementById('pin-modal').classList.add('hidden');
        document.getElementById('pin-modal').classList.remove('flex');
        document.getElementById('pin-input').value = '';
        document.getElementById('pin-error').classList.add('hidden');
    }

    let isSubmitting = false;
    function verifyAndSubmit() {
        if (isSubmitting) return;
        
        const pin = document.getElementById('pin-input').value;
        
        if (pin.length !== 6) {
            return;
        }

        isSubmitting = true;
        // Get form
        const form = document.getElementById('form-janji-temu');
        
        // Convert formatted currency to number before submit
        const nominalInput = form.querySelector('input[name="nominal"]');
        const nominalRaw = nominalInput.value.replace(/[^\d]/g, '');
        nominalInput.value = nominalRaw; // Set as raw number for server processing

        // Add PIN to form
        const pinInputHidden = document.createElement('input');
        pinInputHidden.type = 'hidden';
        pinInputHidden.name = 'pin';
        pinInputHidden.value = pin;
        form.appendChild(pinInputHidden);

        // Submit form
        const submitBtn = document.getElementById('btn-verify-submit');
        submitBtn.disabled = true;
        submitBtn.innerHTML = `
            <svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
        `;
        
        form.submit();
    }

    function showPinError(message) {
        const errorDiv = document.getElementById('pin-error');
        errorDiv.querySelector('p').textContent = message;
        errorDiv.classList.remove('hidden');
    }

    // Close modal on outside click
    document.getElementById('pin-modal').addEventListener('click', function(e) {
        if (e.target === this) {
            closePinModal();
        }
    });

    // Auto focus on PIN input when modal opens
    document.getElementById('pin-modal').addEventListener('transitionend', function() {
        if (!this.classList.contains('hidden')) {
            document.getElementById('pin-input').focus();
        }
    });

    // Submit on Enter key
    document.getElementById('pin-input').addEventListener('keypress', function(e) {
        if (e.key === 'Enter') {
            verifyAndSubmit();
        }
    });
</script>
@endpush
@endsection
