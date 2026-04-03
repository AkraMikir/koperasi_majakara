@extends('layouts.nasabah')

@section('title', 'Nabung Sekarang')

@section('content')
<div class="w-full pb-6 max-w-4xl mx-auto">
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

    @if(session('error'))
    <div class="mx-4 mb-4">
        <div class="relative overflow-hidden bg-white rounded-2xl shadow-md border border-red-100 p-4 flex items-start gap-3">
            <div class="absolute inset-0 bg-gradient-to-br from-red-50/60 to-rose-50/40 pointer-events-none"></div>
            <div class="relative flex-shrink-0 w-12 h-12 bg-gradient-to-br from-red-400 to-rose-500 rounded-xl flex items-center justify-center shadow-lg shadow-red-200/40">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <div class="relative flex-1 min-w-0">
                <p class="font-bold text-red-800 text-sm mb-0.5">Perhatian</p>
                <p class="text-gray-700 text-sm">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif

    {{-- 🔥 STEP 1: GUIDE ONBOARDING --}}
    <div id="step-guide" class="guide-step mx-4">
        <div class="text-center mb-10">
            <div class="w-20 h-20 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-3xl mx-auto mb-6 flex items-center justify-center shadow-xl shadow-[#674c1d]/20">
                <svg class="w-10 h-10 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                </svg>
            </div>
            <h1 class="text-2xl md:text-3xl font-bold text-[#674c1d] mb-3">
                Setor Tabungan <br> <span class="text-[#8b6f2f]">Mudah & Aman</span>
            </h1>
            <p class="text-gray-600 max-w-lg mx-auto">
                Ikuti 3 langkah sederhana untuk menambah saldo tabungan Anda hari ini.
            </p>
        </div>

        {{-- Method Toggle Guide --}}
        <div class="flex justify-center mb-10">
            <div class="bg-gray-100 p-1.5 rounded-2xl flex items-center shadow-inner">
                <button onclick="switchGuide('transfer')" id="tab-guide-transfer" class="px-6 py-2.5 rounded-xl font-bold text-sm transition-all bg-white shadow-sm text-[#674c1d]">
                    Metode Transfer
                </button>
                <button onclick="switchGuide('tunai')" id="tab-guide-tunai" class="px-6 py-2.5 rounded-xl font-bold text-sm transition-all text-gray-500 hover:text-gray-700">
                    Metode Tunai
                </button>
            </div>
        </div>

        {{-- 🔥 Content: TRANSFER --}}
        <div id="guide-transfer-content" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10 transition-all duration-300">
            <div class="step-card group p-6 bg-white rounded-2xl border-2 border-gray-100 hover:border-[#8b6f2f]/50 transition-all shadow-sm">
                <div class="w-12 h-12 bg-[#8b6f2f]/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-[#8b6f2f]/20 transition-colors">
                    <svg class="w-6 h-6 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">1. Transfer Uang</h3>
                <p class="text-sm text-gray-600">Transfer ke rekening resmi koperasi via Mobile Banking.</p>
            </div>
            
            <div class="step-card group p-6 bg-white rounded-2xl border-2 border-gray-100 hover:border-[#8b6f2f]/50 transition-all shadow-sm">
                <div class="w-12 h-12 bg-[#8b6f2f]/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-[#8b6f2f]/20 transition-colors">
                    <svg class="w-6 h-6 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 9a2 2 0 012-2h.93a2 2 0 001.664-.89l.812-1.22A2 2 0 0110.07 4h3.86a2 2 0 011.664.89l.812 1.22A2 2 0 0018.07 7H19a2 2 0 012 2v9a2 2 0 01-2 2H5a2 2 0 01-2-2V9z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 13a3 3 0 11-6 0 3 3 0 016 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">2. Simpan Bukti</h3>
                <p class="text-sm text-gray-600">Screenshot atau foto bukti transfer Anda.</p>
            </div>
            
            <div class="step-card group p-6 bg-white rounded-2xl border-2 border-gray-100 hover:border-[#8b6f2f]/50 transition-all shadow-sm">
                <div class="w-12 h-12 bg-[#8b6f2f]/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-[#8b6f2f]/20 transition-colors">
                    <svg class="w-6 h-6 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">3. Konfirmasi PIN</h3>
                <p class="text-sm text-gray-600">Input nominal & upload bukti, lalu masukkan PIN Anda.</p>
            </div>
        </div>

        {{-- 🔥 Content: TUNAI (Hidden by default) --}}
        <div id="guide-tunai-content" class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-10 hidden transition-all duration-300">
            <div class="step-card group p-6 bg-white rounded-2xl border-2 border-gray-100 hover:border-[#674c1d]/50 transition-all shadow-sm">
                <div class="w-12 h-12 bg-[#674c1d]/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-[#674c1d]/20 transition-colors">
                    <svg class="w-6 h-6 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">1. Pilih Kantor</h3>
                <p class="text-sm text-gray-600">Pilih kantor cabang terdekat yang ingin Anda kunjungi.</p>
            </div>
            
            <div class="step-card group p-6 bg-white rounded-2xl border-2 border-gray-100 hover:border-[#674c1d]/50 transition-all shadow-sm">
                <div class="w-12 h-12 bg-[#674c1d]/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-[#674c1d]/20 transition-colors">
                    <svg class="w-6 h-6 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2-2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">2. Buat Janji</h3>
                <p class="text-sm text-gray-600">Tentukan tanggal & janji temu dengan petugas koperasi.</p>
            </div>
            
            <div class="step-card group p-6 bg-white rounded-2xl border-2 border-gray-100 hover:border-[#674c1d]/50 transition-all shadow-sm">
                <div class="w-12 h-12 bg-[#674c1d]/10 rounded-xl flex items-center justify-center mb-4 group-hover:bg-[#674c1d]/20 transition-colors">
                    <svg class="w-6 h-6 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                </div>
                <h3 class="font-bold text-gray-900 mb-2">3. Datang & Setor</h3>
                <p class="text-sm text-gray-600">Bawa uang tunai fisik ke kantor tujuan dan verifikasi PIN.</p>
            </div>
        </div>

        {{-- 🔥 Info Section: TRANSFER (Dinamis dari Database) --}}
        <div id="guide-transfer-info" class="space-y-6 mb-10 transition-all duration-300">
            @forelse($banks as $bank)
            <div class="bg-gradient-to-br from-[#674c1d]/5 to-[#8b6f2f]/10 p-6 md:p-8 rounded-3xl border border-[#674c1d]/10 transition-all duration-300">
                <div class="flex items-center justify-between mb-6">
                    <h3 class="text-lg font-bold text-[#674c1d] flex items-center gap-3">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                        </svg>
                        Rekening Transfer {{ $bank->bank }}
                    </h3>
                    @if($bank->logo_url)
                    <img src="{{ $bank->logo_url }}" alt="{{ $bank->bank }} Logo" class="h-6 opacity-80">
                    @endif
                </div>

                <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden transition-all hover:shadow-md">
                    <div class="p-6 md:p-8 space-y-6">
                        {{-- Row 1: No Rekening --}}
                        <div>
                            <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-2">Nomor Rekening {{ $bank->bank }}</label>
                            <div class="flex items-center justify-between p-4 bg-gray-50 rounded-xl border-2 border-dashed border-gray-200">
                                <span class="font-mono text-2xl md:text-3xl font-bold text-[#674c1d]">{{ $bank->no_rek }}</span>
                                <button onclick="copyToClipboard('{{ $bank->no_rek }}')" class="px-4 py-2 bg-[#674c1d] text-white text-xs font-bold rounded-lg hover:bg-[#4a3514] transition-colors shadow-lg">SALIN</button>
                            </div>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            {{-- Row 2: Nama Penerima --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Nama Penerima / Pemilik Rekening</label>
                                <p class="text-lg font-bold text-gray-800">{{ $bank->pemilik }}</p>
                                <p class="text-xs text-gray-500 mt-1">Pastikan nama sesuai saat konfirmasi transfer</p>
                            </div>
                            
                            {{-- Row 3: Cabang --}}
                            <div>
                                <label class="block text-xs font-bold text-gray-400 uppercase tracking-widest mb-1">Kantor Cabang</label>
                                <p class="text-lg font-bold text-gray-800">{{ $bank->cabang ?? '-' }}</p>
                                <p class="text-xs text-gray-500 mt-1">Kode Bank: {{ $bank->kode_bank ?? '-' }}</p>
                            </div>
                        </div>
                    </div>

                    {{-- Alert Info --}}
                    <div class="bg-[#674c1d] p-3 px-6 text-center">
                        <p class="text-xs text-white/90 font-medium">⚠️ Mohon periksa kembali nominal dan nama penerima sebelum mengirim</p>
                    </div>
                </div>
            </div>
            @empty
            <div class="bg-amber-50 border-2 border-amber-100 p-6 rounded-2xl text-center">
                <p class="text-amber-800 font-medium italic">Belum ada data rekening transfer yang tersedia saat ini.</p>
            </div>
            @endforelse
        </div>

        {{-- 🔥 Info Section: TUNAI --}}
        <div id="guide-tunai-info" class="hidden bg-gradient-to-br from-gray-50 to-gray-100 p-6 md:p-8 rounded-3xl border border-gray-200 mb-10 transition-all duration-300">
            <h3 class="text-lg font-bold text-gray-700 mb-6 flex items-center gap-3">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                Jam Operasional Kantor
            </h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="bg-white p-5 rounded-2xl border border-gray-100">
                    <p class="text-sm font-bold text-gray-400 uppercase mb-3">Hari Kerja</p>
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-gray-700">Senin - Jumat</span>
                        <span class="text-sm font-bold text-[#674c1d]">08:00 - 16:00</span>
                    </div>
                </div>
                <div class="bg-white p-5 rounded-2xl border border-gray-100">
                    <p class="text-sm font-bold text-gray-400 uppercase mb-3">Hari Libur</p>
                    <div class="flex items-center justify-between">
                        <span class="font-bold text-gray-700">Sabtu - Minggu</span>
                        <span class="text-sm font-bold text-red-500">Tutup</span>
                    </div>
                </div>
            </div>
        </div>

        {{-- Next Button --}}
        <div class="text-center pb-10">
            <button onclick="nextStep()" 
                    class="group relative inline-flex items-center gap-3 px-10 py-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-2xl font-bold text-lg shadow-xl hover:shadow-2xl hover:scale-[1.02] transform transition-all active:scale-95">
                <span>Lanjut Setor Tabungan</span>
                <svg class="w-5 h-5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path>
                </svg>
            </button>
        </div>
    </div>

    {{-- 🔥 STEP 2: FORM SECTION --}}
    <div id="step-form" class="guide-step hidden">
        @include('nasabah.tabungan.form-setoran')
    </div>
</div>


<!-- All PIN Modals -->
@include('nasabah.tabungan.modals-pin')

@push('scripts')
<script>
    let currentStep = 1;

    function switchGuide(method) {
        const transTab = document.getElementById('tab-guide-transfer');
        const tunaiTab = document.getElementById('tab-guide-tunai');
        const transContent = document.getElementById('guide-transfer-content');
        const tunaiContent = document.getElementById('guide-tunai-content');
        const transInfo = document.getElementById('guide-transfer-info');
        const tunaiInfo = document.getElementById('guide-tunai-info');

        if (method === 'transfer') {
            // Button Styles
            transTab.classList.add('bg-white', 'shadow-sm', 'text-[#674c1d]');
            transTab.classList.remove('text-gray-500');
            tunaiTab.classList.remove('bg-white', 'shadow-sm', 'text-[#674c1d]');
            tunaiTab.classList.add('text-gray-500');

            // Content Visibility
            transContent.classList.remove('hidden');
            tunaiContent.classList.add('hidden');
            transInfo.classList.remove('hidden');
            tunaiInfo.classList.add('hidden');
        } else {
            // Button Styles
            tunaiTab.classList.add('bg-white', 'shadow-sm', 'text-[#674c1d]');
            tunaiTab.classList.remove('text-gray-500');
            transTab.classList.remove('bg-white', 'shadow-sm', 'text-[#674c1d]');
            transTab.classList.add('text-gray-500');

            // Content Visibility
            tunaiContent.classList.remove('hidden');
            transContent.classList.add('hidden');
            tunaiInfo.classList.remove('hidden');
            transInfo.classList.add('hidden');
        }
    }

    function nextStep() {
        const guide = document.getElementById('step-guide');
        const form = document.getElementById('step-form');
        
        guide.classList.add('opacity-0', '-translate-y-4');
        setTimeout(() => {
            guide.classList.add('hidden');
            form.classList.remove('hidden');
            form.classList.add('opacity-0', 'translate-y-4');
            setTimeout(() => {
                form.classList.remove('opacity-0', 'translate-y-4');
                form.classList.add('opacity-100', 'translate-y-0');
                window.scrollTo({ top: 0, behavior: 'smooth' });
            }, 50);
        }, 300);
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text);
        alert('Nomor rekening berhasil disalin!');
    }

    // Modal & Form Logic from original file
    let selectedMethod = null;
    let buktiCount = 0;

    function selectMethod(method) {
        selectedMethod = method;
        document.querySelectorAll('[id^="btn-"]').forEach(btn => {
            btn.classList.remove('border-[#674c1d]', 'border-[#8b6f2f]', 'bg-gradient-to-br', 'from-[#674c1d]/10', 'to-[#8b6f2f]/10');
            btn.classList.add('border-gray-200');
        });
        
        document.getElementById('form-transfer-section').classList.add('hidden');
        document.getElementById('form-tunai-section').classList.add('hidden');
        
        if (method === 'transfer') {
            document.getElementById('btn-transfer').classList.add('border-[#8b6f2f]', 'bg-gradient-to-br', 'from-[#8b6f2f]/10', 'to-[#d4af37]/10');
            document.getElementById('form-transfer-section').classList.remove('hidden');
            if (buktiCount === 0) addBuktiField();
        } else {
            document.getElementById('btn-tunai').classList.add('border-[#674c1d]', 'bg-gradient-to-br', 'from-[#674c1d]/10', 'to-[#8b6f2f]/10');
            document.getElementById('form-tunai-section').classList.remove('hidden');
        }
    }

    function addBuktiField() {
        buktiCount++;
        const container = document.getElementById('bukti-container');
        const div = document.createElement('div');
        div.className = 'relative animate-fade-in';
        div.innerHTML = `
            <div class="flex items-center gap-3 p-4 bg-gray-50 rounded-xl border-2 border-gray-200">
                <div class="shrink-0 w-10 h-10 bg-[#674c1d] rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                    </svg>
                </div>
                <div class="flex-1">
                    <input type="file" name="bukti_foto[]" accept="image/*" required
                        class="w-full text-sm text-gray-600 file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:text-sm file:font-semibold file:bg-[#674c1d] file:text-white hover:file:bg-[#4a3514] cursor-pointer">
                </div>
                ${buktiCount > 1 ? `
                <button type="button" onclick="this.parentElement.parentElement.remove(); buktiCount--;" 
                    class="shrink-0 w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg flex items-center justify-center transition-colors">
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
        if (value) value = parseInt(value).toLocaleString('id-ID');
        input.value = value;
    }

    async function verifyPinAjax(pin) {
        const response = await fetch('{{ route("nasabah.tabungan.verify-pin") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
            body: JSON.stringify({ pin: pin })
        });
        return await response.json();
    }

    // Combined submit functions from original script
    async function submitFormTransfer() {
        const pinInput = document.getElementById('pin-input-transfer');
        const errorMsg = document.getElementById('pin-error-transfer');
        const pin = pinInput.value;
        if (pin.length !== 6) return;

        const data = await verifyPinAjax(pin);
        if (data.success) {
            const nominalInput = document.getElementById('nominal-transfer');
            nominalInput.value = nominalInput.value.replace(/[^0-9]/g, '');
            const hiddenPin = document.createElement('input');
            hiddenPin.type = 'hidden'; hiddenPin.name = 'pin'; hiddenPin.value = pin;
            document.getElementById('form-transfer').appendChild(hiddenPin);
            document.getElementById('form-transfer').submit();
        } else {
            errorMsg.textContent = data.message || 'PIN salah';
            errorMsg.classList.remove('hidden');
        }
    }

    async function submitFormTunai() {
        const pinInput = document.getElementById('pin-input-tunai');
        const errorMsg = document.getElementById('pin-error-tunai');
        const pin = pinInput.value;
        if (pin.length !== 6) return;

        const data = await verifyPinAjax(pin);
        if (data.success) {
            const nominalInput = document.getElementById('nominal-tunai');
            nominalInput.value = nominalInput.value.replace(/[^0-9]/g, '');
            const hiddenPin = document.createElement('input');
            hiddenPin.type = 'hidden'; hiddenPin.name = 'pin'; hiddenPin.value = pin;
            document.getElementById('form-tunai').appendChild(hiddenPin);
            document.getElementById('form-tunai').submit();
        } else {
            errorMsg.textContent = data.message || 'PIN salah';
            errorMsg.classList.remove('hidden');
        }
    }

    function showPinModalTransfer() { if (document.getElementById('form-transfer').checkValidity()) { document.getElementById('pin-modal-transfer').classList.remove('hidden'); document.getElementById('pin-modal-transfer').classList.add('flex'); document.getElementById('pin-input-transfer').focus(); } else document.getElementById('form-transfer').reportValidity(); }
    function closePinModalTransfer() { document.getElementById('pin-modal-transfer').classList.add('hidden'); document.getElementById('pin-modal-transfer').classList.remove('flex'); }
    function showPinModalTunai() { if (document.getElementById('form-tunai').checkValidity()) { document.getElementById('pin-modal-tunai').classList.remove('hidden'); document.getElementById('pin-modal-tunai').classList.add('flex'); document.getElementById('pin-input-tunai').focus(); } else document.getElementById('form-tunai').reportValidity(); }
    function closePinModalTunai() { document.getElementById('pin-modal-tunai').classList.add('hidden'); document.getElementById('pin-modal-tunai').classList.remove('flex'); }

    // Hover effect for cards
    document.querySelectorAll('.step-card').forEach(card => {
        card.addEventListener('mouseenter', () => card.classList.add('-translate-y-2'));
        card.addEventListener('mouseleave', () => card.classList.remove('-translate-y-2'));
    });
</script>
@endpush

<style>
    .guide-step { transition: all 0.5s cubic-bezier(0.4, 0, 0.2, 1); }
    .animate-fade-in { animation: fadeIn 0.3s ease-out forwards; }
    @keyframes fadeIn { from { opacity: 0; transform: translateY(10px); } to { opacity: 1; transform: translateY(0); } }
</style>
@endsection
