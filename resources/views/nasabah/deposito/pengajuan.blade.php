@extends('layouts.nasabah')

@section('title', 'Buka Deposito Baru')

@push('styles')
<style>
    .step-indicator { transition: all 0.3s ease; }
    .step-active { background: linear-gradient(135deg, #674c1d, #d4af37); }
    .step-done { background: #22c55e; }
    .step-inactive { background: #e5e7eb; }
    .tenor-option { cursor: pointer; transition: all 0.2s ease; }
    .tenor-option.selected { border-color: #674c1d; background: linear-gradient(135deg, #fffbf0, #fef9e7); }
    .tenor-option.selected .rate-text { color: #674c1d; }
    .slide-in { animation: slideIn 0.3s ease; }
    @keyframes slideIn { from { opacity: 0; transform: translateX(20px); } to { opacity: 1; transform: translateX(0); } }
    .fade-in { animation: fadeIn 0.3s ease; }
    @keyframes fadeIn { from { opacity: 0; } to { opacity: 1; } }
    input[type=file]::file-selector-button {
        background: #674c1d; color: white; border: none; padding: 6px 14px;
        border-radius: 8px; cursor: pointer; font-size: 12px; margin-right: 10px;
    }
</style>
@endpush

@section('content')
<div class="w-full">

    {{-- ===== HEADER ===== --}}
    <div class="bg-gradient-to-r from-[#4a3514] to-[#8b6f2f] px-4 pt-6 pb-16">
        <div class="flex items-center gap-3 mb-2">
            <a href="{{ route('nasabah.deposito.index') }}" class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center text-white hover:bg-white/30 transition">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
                </svg>
            </a>
            <div>
                <h1 class="text-white font-bold text-lg">Buka Deposito</h1>
                <p class="text-white/70 text-xs">Koperasi Majakara</p>
            </div>
        </div>
    </div>

    {{-- ===== STEP INDICATOR (floating) ===== --}}
    <div class="mx-4 -mt-10 mb-4 relative z-10">
        <div class="bg-white rounded-2xl shadow-md p-4">
            <div class="flex items-center justify-between">
                {{-- Step 1 --}}
                <div class="flex flex-col items-center gap-1 step-wrapper" data-step="1">
                    <div id="step-dot-1" class="step-indicator step-active w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-md">1</div>
                    <p class="text-xs font-medium text-[#674c1d]">Paket</p>
                </div>
                <div class="flex-1 h-1 bg-gray-200 mx-2 rounded-full relative">
                    <div id="line-1" class="absolute inset-0 rounded-full bg-gradient-to-r from-[#674c1d] to-[#d4af37] transition-all duration-500" style="width:0%"></div>
                </div>
                {{-- Step 2 --}}
                <div class="flex flex-col items-center gap-1">
                    <div id="step-dot-2" class="step-indicator step-inactive w-8 h-8 rounded-full flex items-center justify-center text-gray-400 text-xs font-bold">2</div>
                    <p class="text-xs text-gray-400" id="step-label-2">Nominal</p>
                </div>
                <div class="flex-1 h-1 bg-gray-200 mx-2 rounded-full relative">
                    <div id="line-2" class="absolute inset-0 rounded-full bg-gradient-to-r from-[#674c1d] to-[#d4af37] transition-all duration-500" style="width:0%"></div>
                </div>
                {{-- Step 3 --}}
                <div class="flex flex-col items-center gap-1">
                    <div id="step-dot-3" class="step-indicator step-inactive w-8 h-8 rounded-full flex items-center justify-center text-gray-400 text-xs font-bold">3</div>
                    <p class="text-xs text-gray-400" id="step-label-3">Konfirmasi</p>
                </div>
            </div>
        </div>
    </div>

    <form id="form-pengajuan" method="POST" action="{{ route('nasabah.deposito.submit-pengajuan') }}" enctype="multipart/form-data">
    @csrf

    {{-- ============================= STEP 1: PILIH PAKET ============================= --}}
    <div id="step1" class="mx-4 mb-4 slide-in">

        {{-- jenis_deposito dihapus - tidak digunakan lagi --}}


        {{-- Pilih Tenor --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
            <h2 class="font-bold text-[#674c1d] text-sm mb-3">Pilih Tenor & Suku Bunga</h2>
            <input type="hidden" name="tenor_id" id="selected_tenor_id" required>
            <div class="space-y-3">
                @php
                    $tenorFallback = [
                        ['id' => 1, 'bulan' => 1, 'rate' => 6.0, 'desc' => 'Cocok untuk dana darurat jangka pendek'],
                        ['id' => 3, 'bulan' => 3, 'rate' => 7.5, 'desc' => 'Fleksibel, cocok untuk perencanaan kuartalan'],
                        ['id' => 6, 'bulan' => 6, 'rate' => 9.0, 'desc' => 'Bunga menarik untuk simpanan semester'],
                        ['id' => 12, 'bulan' => 12, 'rate' => 12.0, 'desc' => 'Return tertinggi untuk investasi tahunan'],
                    ];
                    $tenorList = $tenors->isEmpty() ? collect($tenorFallback) : $tenors;
                @endphp
                @foreach($tenorList as $t)
                @php
                    if ($tenors->isEmpty()) {
                        $id = $t['id']; $bulan = $t['bulan']; $rate = $t['rate']; $desc = $t['desc'];
                        $isRecommended = $bulan == 12;
                    } else {
                        $id = $t->id; $bulan = $t->tenor_bulan;
                        $rateDefault = [1 => 3.75, 3 => 4.50, 6 => 5.25, 12 => 6.00];
                        $rate = $t->sukuBunga->first()
                            ? (float)($t->sukuBunga->first()->bunga * 100)
                            : ($rateDefault[$bulan] ?? 6.00);
                        $desc = 'Pilih tenor ' . $bulan . ' bulan';
                        $isRecommended = $bulan == 12;
                    }
                @endphp
                <div class="tenor-option border-2 border-gray-200 rounded-xl p-4 hover:border-[#d4af37]"
                     data-tenor-id="{{ $id }}"
                     onclick="selectTenorOption({{ $id }}, {{ $bulan }}, {{ $rate }}, this)">
                    <div class="flex items-center justify-between">
                        <div class="flex items-center gap-3">
                            <div class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center font-bold text-[#674c1d] text-sm">
                                {{ $bulan }}bln
                            </div>
                            <div>
                                <div class="flex items-center gap-2">
                                    <p class="font-bold text-gray-800 text-sm">Deposito {{ $bulan }} Bulan</p>
                                    @if($isRecommended)
                                    <span class="text-xs bg-[#d4af37] text-[#3a2800] px-2 py-0.5 rounded-full font-bold">Terbaik</span>
                                    @endif
                                </div>
                                <p class="text-xs text-gray-500">{{ $desc }}</p>
                            </div>
                        </div>
                        <div class="text-right">
                            <span class="rate-text text-2xl font-black text-gray-700 transition-colors">{{ number_format($rate, 2) }}%</span>
                            <p class="text-xs text-gray-400">p.a.</p>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>
        </div>

        <button type="button" onclick="goToStep(2)"
            class="w-full bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white font-bold py-4 rounded-xl text-sm shadow-md active:scale-95 transition-all">
            Lanjut →
        </button>
    </div>

    {{-- ============================= STEP 2: NOMINAL & METODE ============================= --}}
    <div id="step2" class="mx-4 mb-4 hidden">
        {{-- Summary Paket Terpilih (Clickable Dropdown-style) --}}
        <button type="button" onclick="goToStep(1)" id="paket-summary" class="w-full text-left bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] rounded-2xl p-4 mb-4 text-white hover:shadow-md transition-all active:scale-[0.98] border border-[#d4af37]/30">
            <div class="flex justify-between items-center">
                <div>
                    <p class="text-white/70 text-xs mb-0.5">Paket Dipilih</p>
                    <div class="flex items-center gap-1.5">
                        <p class="font-bold text-base" id="summary-tenor">-</p>
                        <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/></svg>
                    </div>
                </div>
                <div class="text-right">
                    <p class="text-white/70 text-xs mb-0.5">Suku Bunga</p>
                    <p class="text-[#f0d060] font-black text-xl leading-none" id="summary-rate">-</p>
                </div>
            </div>
        </button>

        {{-- Input Nominal --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
            <h2 class="font-bold text-[#674c1d] text-sm mb-4">Jumlah Penempatan</h2>
            <div class="relative mb-2">
                <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-bold">Rp</span>
                <input type="number" name="nominal" id="nominal_input" placeholder="10.000.000" min="1000000" step="500000"
                    class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl text-lg font-bold text-gray-800 focus:outline-none focus:border-[#674c1d] transition-colors"
                    oninput="updatePreview()">
            </div>
            <p class="text-xs text-gray-400 mb-4">Minimum Rp 1.000.000</p>
            {{-- Shortcut buttons --}}
            <div class="grid grid-cols-4 gap-2 mb-4">
                @foreach([1000000, 5000000, 10000000, 50000000] as $amt)
                <button type="button" onclick="setNominal({{ $amt }})"
                    class="py-2 rounded-lg border border-gray-200 text-xs font-semibold text-gray-600 hover:border-[#674c1d] hover:text-[#674c1d] hover:bg-amber-50 transition-all">
                    {{ number_format($amt / 1000000, 0) }}jt
                </button>
                @endforeach
            </div>

            {{-- Estimasi bunga realtime --}}
            <div id="estimasi-container" class="hidden fade-in bg-gradient-to-br from-[#fffbf0] to-[#fef9e7] border border-[#d4af37]/30 rounded-xl p-4">
                <p class="text-xs text-gray-500 font-semibold mb-2">Estimasi Imbal Hasil</p>
                <div class="grid grid-cols-2 gap-3">
                    <div class="text-center">
                        <p class="text-xs text-gray-400 mb-1">Bunga Kotor</p>
                        <p id="est-bunga" class="font-bold text-[#674c1d]">Rp 0</p>
                    </div>
                    <div class="text-center">
                        <p class="text-xs text-gray-400 mb-1">Total Cair</p>
                        <p id="est-total" class="font-bold text-green-700">Rp 0</p>
                    </div>
                </div>
                <div class="flex justify-between text-xs text-gray-500 mt-3 pt-3 border-t border-[#d4af37]/20">
                    <span>Setelah pajak 20%</span>
                    <span id="est-bersih" class="font-semibold">Rp 0</span>
                </div>
            </div>
        </div>

        {{-- Metode Setor --}}
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
            <h2 class="font-bold text-[#674c1d] text-sm mb-4">Metode Setoran</h2>
            <div class="space-y-3">
                <label class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-[#674c1d] has-[:checked]:border-[#674c1d] has-[:checked]:bg-amber-50/30 transition-all">
                    <input type="radio" name="metode_setor" value="transfer" class="mt-0.5 accent-[#674c1d]" onchange="toggleBuktiTf(this)">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg">🏦</span>
                            <p class="font-bold text-gray-800 text-sm">Transfer Bank</p>
                        </div>
                        <p class="text-xs text-gray-500">Setorkan dana ke rekening Koperasi Majakara, lalu upload bukti transfer</p>
                    </div>
                </label>

                <label class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-[#674c1d] has-[:checked]:border-[#674c1d] has-[:checked]:bg-amber-50/30 transition-all">
                    <input type="radio" name="metode_setor" value="saldo_tabungan" class="mt-0.5 accent-[#674c1d]" onchange="toggleBuktiTf(this)">
                    <div class="flex-1">
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-lg">💳</span>
                            <p class="font-bold text-gray-800 text-sm">Saldo Tabungan</p>
                        </div>
                        <p class="text-xs text-gray-500">Potong langsung dari saldo tabungan Anda</p>
                        <p class="text-xs text-[#674c1d] font-bold mt-1">Saldo: Rp {{ number_format($saldoTabungan, 0, ',', '.') }}</p>
                    </div>
                </label>
            </div>

            {{-- Upload bukti (conditional) --}}
            <div id="bukti-tf-container" class="hidden mt-4 fade-in">
                <div class="p-4 bg-amber-50 rounded-xl border border-[#d4af37]/30 mb-3">
                    <p class="text-xs font-bold text-[#674c1d] mb-1">📋 Info Rekening Koperasi Majakara</p>
                    <p class="text-xs text-gray-600">Bank BRI – 1234-01-012345-56-7</p>
                    <p class="text-xs text-gray-600">a.n. Koperasi Simpan Pinjam Majakara</p>
                </div>
                <label class="text-xs font-semibold text-gray-700 block mb-2">Upload Bukti Transfer *</label>
                <input type="file" name="foto_bukti_tf" accept="image/*" class="w-full text-sm text-gray-600 border border-gray-200 rounded-xl p-2">
            </div>

            {{-- Catatan --}}
            <div class="mt-4">
                <label class="text-xs font-semibold text-gray-700 block mb-2">Catatan (opsional)</label>
                <textarea name="catatan" rows="2" placeholder="Tambahkan catatan jika perlu..." class="w-full p-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#674c1d] resize-none"></textarea>
            </div>
        </div>

        <div class="flex gap-3">
            <button type="button" onclick="goToStep(1)" class="flex-1 border-2 border-[#674c1d] text-[#674c1d] font-bold py-4 rounded-xl text-sm active:scale-95 transition-all">← Kembali</button>
            <button type="button" onclick="goToStep(3)" class="flex-[2] bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white font-bold py-4 rounded-xl text-sm shadow-md active:scale-95 transition-all">Lanjut →</button>
        </div>
    </div>

    {{-- ============================= STEP 3: KONFIRMASI ============================= --}}
    <div id="step3" class="mx-4 mb-4 hidden">
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
            <div class="flex items-center gap-2 mb-5">
                <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                    <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                <h2 class="font-bold text-[#674c1d] text-sm">Konfirmasi Detail Deposito</h2>
            </div>

            <div class="space-y-3">
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-sm text-gray-500">Tenor</span>
                    <span class="font-bold text-gray-800 text-sm" id="conf-tenor">-</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-sm text-gray-500">Suku Bunga</span>
                    <span class="font-bold text-[#674c1d] text-sm" id="conf-rate">-</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-sm text-gray-500">Jumlah Penempatan</span>
                    <span class="font-bold text-gray-800 text-sm" id="conf-nominal">-</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-sm text-gray-500">Metode Setor</span>
                    <span class="font-bold text-gray-800 text-sm" id="conf-metode">-</span>
                </div>
                <div class="flex justify-between items-center py-3 border-b border-gray-100">
                    <span class="text-sm text-gray-500">Estimasi Bunga Bersih</span>
                    <span class="font-bold text-green-600 text-sm" id="conf-bunga">-</span>
                </div>
                <div class="flex justify-between items-center py-3">
                    <span class="text-sm font-bold text-gray-700">Estimasi Total Cair</span>
                    <span class="font-black text-[#674c1d] text-base" id="conf-total">-</span>
                </div>
            </div>

            <div class="mt-5 p-4 bg-amber-50 rounded-xl border border-[#d4af37]/30">
                <p class="text-xs text-gray-500">
                    🔔 Pengajuan akan diproses dalam <strong>1×24 jam kerja</strong>. Nomor Deposito akan dikirimkan setelah disetujui.
                </p>
            </div>
        </div>

        <div class="flex gap-3 mb-4">
            <button type="button" onclick="goToStep(2)" class="flex-1 border-2 border-[#674c1d] text-[#674c1d] font-bold py-4 rounded-xl text-sm active:scale-95 transition-all">← Kembali</button>
            <button type="submit" class="flex-[2] bg-gradient-to-r from-[#674c1d] to-[#d4af37] text-white font-bold py-4 rounded-xl text-sm shadow-lg active:scale-95 transition-all">
                ✅ Ajukan Deposito
            </button>
        </div>
    </div>

    </form>
</div>

@push('scripts')
<script>
let currentStep = 1;
let selectedTenorBulan = 0;
let selectedTenorRate = 0;
let selectedTenorId = 0;

function selectTenorOption(id, bulan, rate, el) {
    selectedTenorId = id;
    selectedTenorBulan = bulan;
    selectedTenorRate = rate;
    document.getElementById('selected_tenor_id').value = id;
    document.querySelectorAll('.tenor-option').forEach(e => e.classList.remove('selected', 'border-[#674c1d]', 'bg-gradient-to-r', 'from-[#fffbf0]', 'to-[#fef9e7]'));
    el.classList.add('selected', 'border-[#674c1d]', 'bg-gradient-to-r', 'from-[#fffbf0]', 'to-[#fef9e7]');
    updatePreview();
}

document.addEventListener('DOMContentLoaded', function() {
    @if(request()->has('tenor'))
        const targetId = "{{ request('tenor') }}";
        const targetEl = document.querySelector(`.tenor-option[data-tenor-id="${targetId}"]`);
        if(targetEl) {
            // Simulasi klik untuk menyimpan parameter dan update UI
            targetEl.click();
            // Otomatis skip ke Step 2 (Nominal)
            goToStep(2);
        }
    @endif
});

function goToStep(step) {
    // Validasi
    if (step === 2 && !selectedTenorId) {
        alert('Harap pilih tenor terlebih dahulu.');
        return;
    }
    if (step === 3) {
        const nominal = parseFloat(document.getElementById('nominal_input').value);
        if (!nominal || nominal < 1000000) { alert('Minimal penempatan Rp 1.000.000'); return; }
        const metode = document.querySelector('input[name="metode_setor"]:checked');
        if (!metode) { alert('Pilih metode setoran terlebih dahulu.'); return; }
        
        if (metode.value === 'saldo_tabungan') {
            const saldoTabungan = {{ max(0, $saldoTabungan) }};
            if (nominal > saldoTabungan) {
                alert('Peringatan: Saldo tabungan Anda (Rp ' + Math.round(saldoTabungan).toLocaleString('id-ID') + ') tidak mencukupi untuk nominal penempatan ini.');
                return;
            }
        }
        
        fillConfirmation(nominal, metode.value);
    }

    ['step1','step2','step3'].forEach((id, i) => {
        const el = document.getElementById(id);
        el.classList.add('hidden');
        el.classList.remove('slide-in');
    });
    document.getElementById('step' + step).classList.remove('hidden');
    document.getElementById('step' + step).classList.add('slide-in');
    currentStep = step;
    updateStepIndicator(step);
    window.scrollTo({ top: 0, behavior: 'smooth' });
}

function updateStepIndicator(step) {
    for (let i = 1; i <= 3; i++) {
        const dot = document.getElementById('step-dot-' + i);
        if (i < step) { dot.className = 'step-indicator step-done w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold'; dot.textContent = '✓'; }
        else if (i === step) { dot.className = 'step-indicator step-active w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-md'; dot.textContent = i; }
        else { dot.className = 'step-indicator step-inactive w-8 h-8 rounded-full flex items-center justify-center text-gray-400 text-xs font-bold'; dot.textContent = i; }
    }
    if (step >= 2) document.getElementById('line-1').style.width = '100%'; else document.getElementById('line-1').style.width = '0%';
    if (step >= 3) document.getElementById('line-2').style.width = '100%'; else document.getElementById('line-2').style.width = '0%';

    // Update summary
    document.getElementById('summary-tenor').textContent = selectedTenorBulan + ' Bulan';
    document.getElementById('summary-rate').textContent = selectedTenorRate.toFixed(2) + '% p.a.';
}

function toggleBuktiTf(el) {
    const c = document.getElementById('bukti-tf-container');
    if (el.value === 'transfer') { c.classList.remove('hidden'); }
    else { c.classList.add('hidden'); }
}

function setNominal(val) {
    document.getElementById('nominal_input').value = val;
    updatePreview();
}

function updatePreview() {
    const nominal = parseFloat(document.getElementById('nominal_input').value) || 0;
    const cont = document.getElementById('estimasi-container');
    if (nominal < 1000000 || !selectedTenorBulan) { cont.classList.add('hidden'); return; }
    cont.classList.remove('hidden');
    const hari = selectedTenorBulan * 30;
    const kotor = nominal * (selectedTenorRate / 100) * (hari / 365);
    const pajak = kotor * 0.2;
    const bersih = kotor - pajak;
    const total = nominal + bersih;
    const fmt = v => 'Rp ' + Math.round(v).toLocaleString('id-ID');
    document.getElementById('est-bunga').textContent = fmt(kotor);
    document.getElementById('est-total').textContent = fmt(total);
    document.getElementById('est-bersih').textContent = fmt(bersih);
}

function fillConfirmation(nominal, metode) {
    const hari = selectedTenorBulan * 30;
    const kotor = nominal * (selectedTenorRate / 100) * (hari / 365);
    const bersih = kotor * 0.8;
    const total = nominal + bersih;
    const fmt = v => 'Rp ' + Math.round(v).toLocaleString('id-ID');
    document.getElementById('conf-tenor').textContent = selectedTenorBulan + ' Bulan';
    document.getElementById('conf-rate').textContent = selectedTenorRate.toFixed(2) + '% p.a.';
    document.getElementById('conf-nominal').textContent = fmt(nominal);
    document.getElementById('conf-metode').textContent = metode === 'transfer' ? 'Transfer Bank' : 'Saldo Tabungan';
    document.getElementById('conf-bunga').textContent = fmt(bersih);
    document.getElementById('conf-total').textContent = fmt(total);
}
</script>
@endpush
@endsection
