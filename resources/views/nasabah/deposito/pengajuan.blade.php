@extends('layouts.nasabah')

@section('title', 'Buka Deposito Baru')

@push('styles')
    <style>
        .step-indicator {
            transition: all 0.3s ease;
        }

        .step-active {
            background: linear-gradient(135deg, #674c1d, #d4af37);
        }

        .step-done {
            background: #22c55e;
        }

        .step-inactive {
            background: #e5e7eb;
        }

        .tenor-option {
            cursor: pointer;
            transition: all 0.2s ease;
        }

        .tenor-option.selected {
            border-color: #674c1d;
            background: linear-gradient(135deg, #fffbf0, #fef9e7);
        }

        .tenor-option.selected .rate-text {
            color: #674c1d;
        }

        .slide-in {
            animation: slideIn 0.3s ease;
        }

        @keyframes slideIn {
            from {
                opacity: 0;
                transform: translateX(20px);
            }

            to {
                opacity: 1;
                transform: translateX(0);
            }
        }

        .fade-in {
            animation: fadeIn 0.3s ease;
        }

        @keyframes fadeIn {
            from {
                opacity: 0;
            }

            to {
                opacity: 1;
            }
        }

        input[type=file]::file-selector-button {
            background: #674c1d;
            color: white;
            border: none;
            padding: 6px 14px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 12px;
            margin-right: 10px;
        }

        .paket-option.selected {
            border-color: #674c1d;
            box-shadow: 0 0 0 2px rgba(103, 76, 29, 0.2);
        }
    </style>
@endpush

@section('content')
    <div class="w-full">

        {{-- ===== HEADER ===== --}}
        <div class="bg-gradient-to-r from-[#4a3514] to-[#8b6f2f] px-4 pt-6 pb-16">
            <div class="flex items-center gap-3 mb-2">
                <a href="{{ route('nasabah.deposito.index') }}"
                    class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center text-white hover:bg-white/30 transition">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
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
                        <div id="step-dot-1"
                            class="step-indicator step-active w-8 h-8 rounded-full flex items-center justify-center text-white text-xs font-bold shadow-md">
                            1</div>
                        <p class="text-xs font-medium text-[#674c1d]">Paket</p>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-2 rounded-full relative">
                        <div id="line-1"
                            class="absolute inset-0 rounded-full bg-gradient-to-r from-[#674c1d] to-[#d4af37] transition-all duration-500"
                            style="width:0%"></div>
                    </div>
                    {{-- Step 2 --}}
                    <div class="flex flex-col items-center gap-1">
                        <div id="step-dot-2"
                            class="step-indicator step-inactive w-8 h-8 rounded-full flex items-center justify-center text-gray-400 text-xs font-bold">
                            2</div>
                        <p class="text-xs text-gray-400" id="step-label-2">Nominal</p>
                    </div>
                    <div class="flex-1 h-1 bg-gray-200 mx-2 rounded-full relative">
                        <div id="line-2"
                            class="absolute inset-0 rounded-full bg-gradient-to-r from-[#674c1d] to-[#d4af37] transition-all duration-500"
                            style="width:0%"></div>
                    </div>
                    {{-- Step 3 --}}
                    <div class="flex flex-col items-center gap-1">
                        <div id="step-dot-3"
                            class="step-indicator step-inactive w-8 h-8 rounded-full flex items-center justify-center text-gray-400 text-xs font-bold">
                            3</div>
                        <p class="text-xs text-gray-400" id="step-label-3">Konfirmasi</p>
                    </div>
                </div>
            </div>
        </div>

        <form id="form-pengajuan" method="POST" action="{{ route('nasabah.deposito.submit-pengajuan') }}"
            enctype="multipart/form-data">
            @csrf

            {{-- ============================= STEP 1: PILIH PAKET ============================= --}}
            <div id="step1" class="mx-4 mb-4 slide-in">

                {{-- jenis_deposito dihapus - tidak digunakan lagi --}}


                {{-- Pilih Tenor --}}
                <div class="mb-6">
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5">
                        <h2 class="font-bold text-[#674c1d] text-sm mb-4">Pilih Paket Deposito</h2>
                        <input type="hidden" name="paket_id" id="selected_paket_id" required>
                        <div class="space-y-3">
                            @forelse($pakets as $p)
                                <div class="paket-option border-2 border-gray-200 rounded-xl p-4 hover:border-[#d4af37] cursor-pointer transition-all duration-200"
                                    data-paket-id="{{ $p->id }}" data-min-nominal="{{ $p->minimal_nominal }}"
                                    onclick="selectPaketOption({{ $p->id }}, {{ $p->tenor_bulan }}, {{ $p->suku_bunga }}, {{ $p->minimal_nominal }}, this)">
                                    <div class="flex items-center justify-between">
                                        <div class="flex items-center gap-3">
                                            <div
                                                class="w-10 h-10 bg-amber-50 rounded-xl flex items-center justify-center font-bold text-[#674c1d] text-sm relative">
                                                {{ $p->tenor_bulan }}bln
                                                @if($p->kategori_id)
                                                    <div
                                                        class="absolute -top-1 -right-1 w-2.5 h-2.5 bg-[#d4af37] rounded-full border border-white">
                                                    </div>
                                                @endif
                                            </div>
                                            <div>
                                                <div class="flex items-center gap-2">
                                                    <p class="font-bold text-gray-800 text-sm">{{ $p->nama_paket }}</p>
                                                    @if($p->kategori)
                                                        <span
                                                            class="text-[8px] bg-[#d4af37]/10 text-[#674c1d] px-1.5 py-0.5 rounded-md font-bold border border-[#d4af37]/20">{{ $p->kategori->nama }}</span>
                                                    @endif
                                                </div>
                                                <p class="text-[10px] text-gray-500">Minimal Rp
                                                    {{ number_format($p->minimal_nominal, 0, ',', '.') }}
                                                </p>
                                            </div>
                                        </div>
                                        <div class="text-right">
                                            <span
                                                class="rate-text text-xl font-black text-gray-700 transition-colors">{{ number_format($p->suku_bunga, 2) }}%</span>
                                            <p class="text-[10px] text-gray-400 uppercase">p.a.</p>
                                        </div>
                                    </div>
                                </div>
                            @empty
                                <div class="p-4 text-center text-gray-500 text-sm bg-gray-50 rounded-xl">
                                    Belum ada paket deposito yang aktif saat ini.
                                </div>
                            @endforelse
                        </div>
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
                <button type="button" onclick="goToStep(1)" id="paket-summary"
                    class="w-full text-left bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] rounded-2xl p-4 mb-4 text-white hover:shadow-md transition-all active:scale-[0.98] border border-[#d4af37]/30">
                    <div class="flex justify-between items-center">
                        <div>
                            <p class="text-white/70 text-xs mb-0.5">Paket Dipilih</p>
                            <div class="flex items-center gap-1.5">
                                <p class="font-bold text-base" id="summary-tenor">-</p>
                                <svg class="w-4 h-4 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M19 9l-7 7-7-7" />
                                </svg>
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
                        <input type="text" name="nominal" id="nominal_input" placeholder="10.000.000"
                            class="w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl text-lg font-bold text-gray-800 focus:outline-none focus:border-[#674c1d] transition-colors"
                            oninput="formatCurrency(this); updatePreview();">
                    </div>
                    <p class="text-xs text-gray-400 mb-4" id="min-nominal-helper">Pilih paket terlebih dahulu untuk melihat
                        batas minimum</p>
                    {{-- Shortcut buttons --}}
                    <div class="grid grid-cols-2 sm:grid-cols-4 gap-2 mb-4">
                        @foreach([1000000, 5000000, 10000000, 50000000] as $amt)
                            <button type="button" onclick="setNominal({{ $amt }})"
                                class="py-2.5 rounded-lg border border-gray-200 text-xs font-semibold text-gray-600 hover:border-[#674c1d] hover:text-[#674c1d] hover:bg-amber-50 transition-all">
                                {{ number_format($amt / 1000000, 0) }}jt
                            </button>
                        @endforeach
                    </div>

                    {{-- Estimasi bunga realtime --}}
                    <div id="estimasi-container"
                        class="hidden fade-in bg-gradient-to-br from-[#fffbf0] to-[#fef9e7] border border-[#d4af37]/30 rounded-xl p-4">
                        <div class="flex justify-between items-center mb-3">
                            <p class="text-xs text-gray-500 font-semibold">Estimasi Imbal Hasil</p>
                            <button type="button"
                                onclick="document.getElementById('modal-rumus').classList.remove('hidden')"
                                class="text-[10px] text-[#674c1d] font-bold underline">Lihat Rumus</button>
                        </div>
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
                            <span>Setelah pajak {{ $pajakRate * 100 }}%</span>
                            <span id="est-bersih" class="font-semibold">Rp 0</span>
                        </div>
                    </div>
                </div>

                {{-- Modal Rumus --}}
                <div id="modal-rumus" class="hidden fixed inset-0 bg-black/60 z-[999] flex items-center justify-center p-4">
                    <div
                        class="bg-white rounded-2xl w-full max-w-sm overflow-hidden shadow-2xl animate-in zoom-in duration-200">
                        <div class="bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] p-4 text-white">
                            <h3 class="font-bold">Rumus Perhitungan</h3>
                        </div>
                        <div class="p-6 space-y-4">
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <p class="text-[10px] text-gray-500 uppercase font-bold mb-1">Bunga Kotor</p>
                                <p class="text-sm font-mono font-bold text-gray-800">(Nominal × Bunga × Tenor_Hari) / {{ $pembagiHari }}
                                </p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <p class="text-[10px] text-gray-500 uppercase font-bold mb-1">Pajak ({{ $pajakRate * 100 }}%)</p>
                                <p class="text-sm font-mono font-bold text-gray-800">Bunga Kotor × {{ $pajakRate }}</p>
                            </div>
                            <div class="p-3 bg-gray-50 rounded-lg border border-gray-100">
                                <p class="text-[10px] text-gray-500 uppercase font-bold mb-1">Total Cair</p>
                                <p class="text-sm font-mono font-bold text-gray-800">Nominal + (Bunga Kotor - Pajak)</p>
                            </div>
                            <p class="text-[10px] text-gray-400 italic">* Tenor hari dihitung 30 hari per bulan.</p>
                            <button type="button" onclick="document.getElementById('modal-rumus').classList.add('hidden')"
                                class="w-full bg-[#674c1d] text-white font-bold py-3 rounded-xl text-sm">Tutup</button>
                        </div>
                    </div>
                </div>

                {{-- Metode Setor --}}
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
                    <h2 class="font-bold text-[#674c1d] text-sm mb-4">Metode Setoran</h2>
                    <div class="space-y-3">
                        <label
                            class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-[#674c1d] has-[:checked]:border-[#674c1d] has-[:checked]:bg-amber-50/30 transition-all">
                            <input type="radio" name="metode_setor" value="transfer" class="mt-0.5 accent-[#674c1d]"
                                onchange="toggleBuktiTf(this)">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="font-bold text-gray-800 text-sm">Transfer Bank</p>
                                </div>
                                <p class="text-xs text-gray-500">Setorkan dana ke rekening Koperasi Majakara, lalu upload
                                    bukti transfer</p>
                            </div>
                        </label>

                        <label
                            class="flex items-start gap-3 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-[#674c1d] has-[:checked]:border-[#674c1d] has-[:checked]:bg-amber-50/30 transition-all">
                            <input type="radio" name="metode_setor" value="saldo_tabungan" class="mt-0.5 accent-[#674c1d]"
                                onchange="toggleBuktiTf(this)">
                            <div class="flex-1">
                                <div class="flex items-center gap-2 mb-1">
                                    <p class="font-bold text-gray-800 text-sm">Saldo Tabungan</p>
                                </div>
                                <p class="text-xs text-gray-500">Potong langsung dari saldo tabungan Anda</p>
                                <p class="text-xs text-[#674c1d] font-bold mt-1">Saldo: Rp
                                    {{ number_format($saldoTabungan, 0, ',', '.') }}
                                </p>
                            </div>
                        </label>
                    </div>

                    {{-- Upload bukti (conditional) --}}
                    <div id="bukti-tf-container" class="hidden mt-4 fade-in">
                        <div class="p-4 bg-amber-50 rounded-xl border border-[#d4af37]/30 mb-3">
                            <p class="text-xs font-bold text-[#674c1d] mb-3 flex items-center gap-2">
                                Info Rekening Koperasi Majakara
                            </p>
                            @forelse($banks as $bank)
                                <div class="mb-4 last:mb-0 pb-3 border-b border-[#d4af37]/20 last:border-0">
                                    <div class="flex items-center gap-2 mb-2">
                                        @if($bank->logo_url)
                                            <img src="{{ $bank->logo_url }}" alt="{{ $bank->bank }}" class="h-4 object-contain">
                                        @endif
                                        <p class="text-xs text-gray-800 font-bold uppercase tracking-wider">{{ $bank->bank }}
                                        </p>
                                    </div>
                                    <p class="text-xl text-[#674c1d] font-mono font-black mb-1 break-all">{{ $bank->no_rek }}</p>
                                    <div class="space-y-0.5">
                                        <p class="text-xs text-gray-600">a.n. <span
                                                class="font-bold text-gray-800">{{ $bank->pemilik }}</span></p>
                                        @if($bank->cabang || $bank->kode_bank)
                                            <p class="text-[10px] text-gray-500 italic">
                                                {{ $bank->cabang ?? '' }}
                                                {{ $bank->kode_bank ? '(Kode: ' . $bank->kode_bank . ')' : '' }}
                                            </p>
                                        @endif
                                    </div>
                                </div>
                            @empty
                                <p class="text-xs text-gray-500 italic text-center">Belum ada data rekening aktif.</p>
                            @endforelse
                        </div>
                        <label class="text-xs font-semibold text-gray-700 block mb-2">Upload Bukti Transfer *</label>
                        <input type="file" name="foto_bukti_tf" accept="image/*"
                            class="w-full text-sm text-gray-600 border border-gray-200 rounded-xl p-2">
                    </div>

                    {{-- Catatan --}}
                    <div class="mt-4">
                        <label class="text-xs font-semibold text-gray-700 block mb-2">Catatan (opsional)</label>
                        <textarea name="catatan" rows="2" placeholder="Tambahkan catatan jika perlu..."
                            class="w-full p-3 border border-gray-200 rounded-xl text-sm focus:outline-none focus:border-[#674c1d] resize-none"></textarea>
                    </div>
                </div>

                <div class="flex gap-3">
                    <button type="button" onclick="goToStep(1)"
                        class="flex-1 border-2 border-[#674c1d] text-[#674c1d] font-bold py-4 rounded-xl text-sm active:scale-95 transition-all">←
                        Kembali</button>
                    <button type="button" onclick="goToStep(3)"
                        class="flex-[2] bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white font-bold py-4 rounded-xl text-sm shadow-md active:scale-95 transition-all">Lanjut
                        →</button>
                </div>
            </div>

            {{-- ============================= STEP 3: KONFIRMASI ============================= --}}
            <div id="step3" class="mx-4 mb-4 hidden">
                <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-5 mb-4">
                    <div class="flex items-center gap-2 mb-5">
                        <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                            <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
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
                            Pengajuan akan diproses dalam <strong>1×24 jam kerja</strong>. Nomor Deposito akan dikirimkan
                            setelah disetujui.
                        </p>
                    </div>
                </div>

                <div class="flex gap-3 mb-4">
                    <button type="button" onclick="goToStep(2)"
                        class="flex-1 border-2 border-[#674c1d] text-[#674c1d] font-bold py-4 rounded-xl text-sm active:scale-95 transition-all">←
                        Kembali</button>
                    <button type="button" onclick="openPinModal()"
                        class="flex-[2] bg-gradient-to-r from-[#674c1d] to-[#d4af37] text-white font-bold py-4 rounded-xl text-sm shadow-lg active:scale-95 transition-all">
                        Ajukan Deposito
                    </button>
                </div>
            </div>

        </form>
    </div>

    <!-- PIN Modal Deposito -->
    <div id="pin-modal-deposito" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
        <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 transform transition-all border border-gray-100 relative">
            <div class="flex items-center justify-between mb-8">
                <div class="flex items-center gap-4">
                    <div class="w-14 h-14 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-2xl flex items-center justify-center shadow-lg">
                        <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900">Konfirmasi PIN</h3>
                        <p class="text-sm text-gray-500">Masukkan 6 digit PIN Anda</p>
                    </div>
                </div>
                <button type="button" onclick="closePinModal()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:text-gray-600 transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                </button>
            </div>
            
            <div class="mb-8">
                <input type="password" id="pin-input-deposito" maxlength="6" placeholder="••••••"
                    class="w-full px-4 py-5 border-2 border-gray-100 rounded-2xl focus:border-[#674c1d] focus:ring-4 focus:ring-[#674c1d]/10 outline-none text-center text-3xl font-bold tracking-[0.5em] transition-all bg-gray-50 focus:bg-white"
                    oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length === 6) verifyPinAndSubmit();">
                <p id="pin-error-deposito" class="hidden text-sm text-red-600 mt-4 text-center font-medium bg-red-50 py-2 rounded-lg italic">PIN salah, silakan coba lagi</p>
            </div>
            
            <button type="button" onclick="verifyPinAndSubmit()" class="w-full py-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-2xl font-bold text-lg shadow-xl shadow-[#674c1d]/20 hover:shadow-2xl hover:scale-[1.01] active:scale-95 transition-all">
                Verifikasi & Ajukan
            </button>
        </div>
    </div>

    @push('scripts')
        <script>
            let currentStep = 1;
            let selectedTenorBulan = 0;
            let selectedTenorRate = 0;
            let selectedPaketId = 0;
            let currentMinNominal = 1000000;

            function formatCurrency(input) {
                let value = input.value.replace(/[^0-9]/g, '');
                if (value) {
                    value = parseInt(value).toLocaleString('id-ID');
                }
                input.value = value;
            }

            function selectPaketOption(id, bulan, rate, minNominal, el) {
                selectedPaketId = id;
                selectedTenorBulan = bulan;
                selectedTenorRate = rate;
                currentMinNominal = minNominal;

                document.getElementById('selected_paket_id').value = id;

                document.querySelectorAll('.paket-option').forEach(e => e.classList.remove('selected', 'border-[#674c1d]', 'bg-gradient-to-r', 'from-[#fffbf0]', 'to-[#fef9e7]'));
                el.classList.add('selected', 'border-[#674c1d]', 'bg-gradient-to-r', 'from-[#fffbf0]', 'to-[#fef9e7]');

                // Update helper text
                document.getElementById('min-nominal-helper').textContent = 'Minimum penempatan Rp ' + Math.round(minNominal).toLocaleString('id-ID');

                // Auto-set nominal to min if current is lower or empty
                const nominalInput = document.getElementById('nominal_input');
                let rawVal = parseFloat(nominalInput.value.replace(/[^0-9]/g, '')) || 0;
                if (!nominalInput.value || rawVal < minNominal) {
                    nominalInput.value = Math.round(minNominal).toLocaleString('id-ID');
                }

                updatePreview();
            }

            document.addEventListener('DOMContentLoaded', function () {
                @if(request()->has('tenor'))
                    const targetId = "{{ request('tenor') }}";
                    const targetEl = document.querySelector(`.tenor-option[data-tenor-id="${targetId}"]`);
                    if (targetEl) {
                        // Simulasi klik untuk menyimpan parameter dan update UI
                        targetEl.click();
                        // Otomatis skip ke Step 2 (Nominal)
                        goToStep(2);
                    }
                @endif

                document.getElementById('form-pengajuan').addEventListener('submit', function() {
                    const nominalInput = document.getElementById('nominal_input');
                    if (nominalInput) {
                        nominalInput.value = nominalInput.value.replace(/[^0-9]/g, '');
                    }
                });
            });

            function goToStep(step) {
                // Validasi
                if (step === 2 && !selectedPaketId) {
                    alert('Harap pilih paket terlebih dahulu.');
                    return;
                }
                if (step === 3) {
                    const nominal = parseFloat(document.getElementById('nominal_input').value.replace(/[^0-9]/g, '')) || 0;
                    if (!nominal || nominal < currentMinNominal) {
                        alert('Minimal penempatan untuk paket ini adalah Rp ' + Math.round(currentMinNominal).toLocaleString('id-ID'));
                        return;
                    }
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

                ['step1', 'step2', 'step3'].forEach((id, i) => {
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
                document.getElementById('nominal_input').value = Math.round(val).toLocaleString('id-ID');
                updatePreview();
            }

            function updatePreview() {
                const nominal = parseFloat(document.getElementById('nominal_input').value.replace(/[^0-9]/g, '')) || 0;
                const cont = document.getElementById('estimasi-container');
                if (nominal < currentMinNominal || !selectedTenorBulan) { cont.classList.add('hidden'); return; }
                cont.classList.remove('hidden');

                // RUMUS SESUAI STANDAR PERBANKAN (SEABANK/MAJAKARA)
                const pembagi = {{ $pembagiHari }};
                const pajakRate = {{ $pajakRate }};

                const hari = selectedTenorBulan * 30; // Simulasi: 30 hari per bulan
                const kotor = Math.round(nominal * (selectedTenorRate / 100) * (hari / pembagi));
                const pajak = Math.round(kotor * pajakRate);
                const bersih = kotor - pajak;
                const total = nominal + bersih;

                const fmt = v => 'Rp ' + v.toLocaleString('id-ID');
                document.getElementById('est-bunga').textContent = fmt(kotor);
                document.getElementById('est-total').textContent = fmt(total);
                document.getElementById('est-bersih').textContent = fmt(bersih);
            }

            function fillConfirmation(nominal, metode) {
                const pembagi = {{ $pembagiHari }};
                const pajakRate = {{ $pajakRate }};

                const hari = selectedTenorBulan * 30;
                const kotor = Math.round(nominal * (selectedTenorRate / 100) * (hari / pembagi));
                const pajak = Math.round(kotor * pajakRate);
                const bersih = kotor - pajak;
                const total = nominal + bersih;

                const fmt = v => 'Rp ' + v.toLocaleString('id-ID');
                document.getElementById('conf-tenor').textContent = selectedTenorBulan + ' Bulan';
                document.getElementById('conf-rate').textContent = selectedTenorRate.toFixed(2) + '% p.a.';
                document.getElementById('conf-nominal').textContent = fmt(nominal);
                document.getElementById('conf-metode').textContent = metode === 'transfer' ? 'Transfer Bank' : 'Saldo Tabungan';
                document.getElementById('conf-bunga').textContent = fmt(bersih);
                document.getElementById('conf-total').textContent = fmt(total);
            }

            function openPinModal() {
                const modal = document.getElementById('pin-modal-deposito');
                const pinInput = document.getElementById('pin-input-deposito');
                const errorMsg = document.getElementById('pin-error-deposito');
                
                modal.classList.remove('hidden');
                modal.classList.add('flex');
                
                pinInput.value = '';
                errorMsg.classList.add('hidden');
                
                setTimeout(() => {
                    pinInput.focus();
                }, 100);
            }

            function closePinModal() {
                const modal = document.getElementById('pin-modal-deposito');
                modal.classList.add('hidden');
                modal.classList.remove('flex');
            }

            async function verifyPinAjax(pin) {
                const response = await fetch('{{ route("nasabah.deposito.verify-pin") }}', {
                    method: 'POST',
                    headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
                    body: JSON.stringify({ pin: pin })
                });
                return await response.json();
            }

            async function verifyPinAndSubmit() {
                const pinInput = document.getElementById('pin-input-deposito');
                const errorMsg = document.getElementById('pin-error-deposito');
                const submitBtn = document.querySelector('#pin-modal-deposito button[onclick="verifyPinAndSubmit()"]');
                const pin = pinInput.value;
                if (pin.length !== 6) return;

                submitBtn.disabled = true;
                const originalText = submitBtn.innerHTML;
                submitBtn.innerHTML = `<svg class="animate-spin h-5 w-5 text-white mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>`;

                try {
                    const data = await verifyPinAjax(pin);
                    if (data.success) {
                        const nominalInput = document.getElementById('nominal_input');
                        nominalInput.value = nominalInput.value.replace(/[^0-9]/g, '');

                        const form = document.getElementById('form-pengajuan');
                        const hiddenPin = document.createElement('input');
                        hiddenPin.type = 'hidden'; 
                        hiddenPin.name = 'pin'; 
                        hiddenPin.value = pin;
                        form.appendChild(hiddenPin);
                        form.submit();
                    } else {
                        errorMsg.textContent = data.message || 'PIN salah';
                        errorMsg.classList.remove('hidden');
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = originalText;
                    }
                } catch (e) {
                    errorMsg.textContent = 'Terjadi kesalahan sistem';
                    errorMsg.classList.remove('hidden');
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalText;
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                const urlParams = new URLSearchParams(window.location.search);
                const paketId = urlParams.get('paket');

                if (paketId) {
                    const optionEl = document.querySelector(`.paket-option[data-paket-id="${paketId}"]`);
                    if (optionEl) {
                        optionEl.click();
                    }
                }
            });
        </script>
    @endpush
@endsection