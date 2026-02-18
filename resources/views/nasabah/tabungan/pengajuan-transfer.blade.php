@extends('layouts.nasabah')

@section('title', 'Pengajuan Setoran Transfer')

@section('content')
<div class="w-full pb-6">
    <!-- Header Section -->
    <div class="mx-4 mt-4 mb-6">
        <div class="bg-linear-to-br from-[#674c1d] via-[#8b6f2f] to-[#d4af37] rounded-3xl shadow-2xl p-6 border-2 border-[#d4af37]/30">
            <div class="flex items-center gap-4">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
                <div>
                    <h1 class="text-2xl font-bold text-white font-display mb-1">Pengajuan Setoran Transfer</h1>
                    <p class="text-white/90 text-sm">Upload bukti transfer untuk pengajuan setoran</p>
                </div>
            </div>
        </div>
    </div>

    <!-- Form Section -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            @if(session('error'))
            <div class="mb-6 p-4 bg-red-50 border border-red-200 rounded-xl">
                <p class="text-red-700 text-sm">{{ session('error') }}</p>
            </div>
            @endif

            <form id="form-transfer" method="POST" action="{{ route('nasabah.tabungan.submit-setoran') }}" enctype="multipart/form-data" class="space-y-6">
                @csrf
                <input type="hidden" name="metode" value="transfer">

                <!-- Nominal Setoran -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Setoran *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                        <input type="text" name="nominal" id="nominal" placeholder="0" required
                            value="{{ old('nominal') }}"
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-lg font-semibold"
                            oninput="formatCurrency(this)">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Minimal setoran: Rp 10.000</p>
                </div>

                <!-- Upload Bukti Transfer -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti Transfer *</label>
                    <div id="bukti-container" class="space-y-3">
                        <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-[#674c1d] transition-colors cursor-pointer" onclick="addBuktiField()">
                            <svg class="w-12 h-12 text-gray-400 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                            </svg>
                            <p class="text-sm text-gray-600 font-semibold">Klik untuk tambah bukti transfer</p>
                            <p class="text-xs text-gray-400 mt-1">Format: JPG, PNG, JPEG (Max 5MB per file)</p>
                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Minimal upload 1 bukti transfer. Anda bisa upload beberapa bukti jika melakukan transfer bertahap.</p>
                </div>

                <!-- Keterangan -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none resize-none">{{ old('keterangan') }}</textarea>
                </div>

                <!-- Submit Button -->
                <div class="pt-4">
                    <button type="button" onclick="showPinModal()" class="w-full py-4 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]">
                        Ajukan Setoran
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
            <input type="text" id="pin-input" maxlength="6" pattern="[0-9]*" inputmode="numeric"
                class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-center text-2xl font-mono tracking-widest"
                placeholder="••••••"
                oninput="this.value = this.value.replace(/[^0-9]/g, '')">
        </div>

        <div class="flex gap-3">
            <button onclick="closePinModal()" class="flex-1 px-4 py-3 border-2 border-gray-300 text-gray-700 rounded-xl font-semibold hover:bg-gray-50 transition-colors">
                Batal
            </button>
            <button onclick="verifyAndSubmit()" class="flex-1 px-4 py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-semibold hover:from-[#4a3514] hover:to-[#674c1d] transition-all">
                Verifikasi
            </button>
        </div>
    </div>
</div>

@push('scripts')
<script>
    let buktiCount = 0;

    function addBuktiField() {
        buktiCount++;
        const container = document.getElementById('bukti-container');
        const div = document.createElement('div');
        div.className = 'border-2 border-gray-200 rounded-xl p-4 space-y-3 bg-gray-50';
        div.innerHTML = `
            <div class="flex items-center justify-between">
                <label class="text-sm font-semibold text-gray-700">Bukti Transfer ${buktiCount}</label>
                <button type="button" onclick="this.closest('.border-2').remove(); buktiCount--;" 
                    class="text-red-600 hover:text-red-700 p-2 hover:bg-red-50 rounded-lg transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                    </svg>
                </button>
            </div>
            <input type="file" name="bukti_foto[]" accept="image/jpeg,image/png,image/jpg" required 
                class="w-full px-4 py-2 border border-gray-300 rounded-lg file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#674c1d] file:text-white hover:file:bg-[#4a3514] file:cursor-pointer" 
                onchange="previewBukti(this)">
            <div class="bukti-preview hidden mt-2">
                <img src="" alt="Preview" class="max-w-full max-h-48 rounded-lg border border-gray-200 shadow-sm">
            </div>
        `;
        container.appendChild(div);
    }

    function previewBukti(input) {
        if (input.files && input.files[0]) {
            const reader = new FileReader();
            const preview = input.closest('.border-2').querySelector('.bukti-preview');
            
            reader.onload = function(e) {
                preview.querySelector('img').src = e.target.result;
                preview.classList.remove('hidden');
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function formatCurrency(input) {
        let value = input.value.replace(/[^\d]/g, '');
        if (value) {
            input.value = new Intl.NumberFormat('id-ID').format(value);
        }
    }

    function showPinModal() {
        // Validate form first
        const nominal = document.getElementById('nominal').value.replace(/[^\d]/g, '');
        if (!nominal || parseInt(nominal) < 10000) {
            alert('Nominal minimal Rp 10.000');
            return;
        }

        if (buktiCount === 0) {
            alert('Minimal upload 1 bukti transfer');
            return;
        }

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

    function verifyAndSubmit() {
        const pin = document.getElementById('pin-input').value;
        
        if (pin.length !== 6) {
            showPinError('PIN harus 6 digit');
            return;
        }

        // Add PIN to form
        const form = document.getElementById('form-transfer');
        const pinInput = document.createElement('input');
        pinInput.type = 'hidden';
        pinInput.name = 'pin';
        pinInput.value = pin;
        form.appendChild(pinInput);

        // Convert formatted currency to number before submit
        const nominalInput = document.getElementById('nominal');
        if (nominalInput) {
            const value = nominalInput.value.replace(/[^\d]/g, '');
            nominalInput.value = value;
        }

        // Submit form
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
</script>
@endpush
@endsection
