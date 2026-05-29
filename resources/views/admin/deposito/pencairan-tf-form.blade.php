@extends('layouts.admin')

@section('title', 'Proses Pencairan TF - ' . $pencairan->deposito?->nomor_deposito)

@section('content')
    <div class="p-6 max-w-2xl mx-auto">

        {{-- Breadcrumb --}}
        <div class="flex items-center gap-2 text-sm text-gray-500 mb-6">
            <a href="{{ route('admin.deposito.index') }}" class="hover:text-[#674c1d]">Deposito</a>
            <span>/</span>
            <a href="{{ route('admin.deposito.pencairan-tf.index') }}" class="hover:text-[#674c1d]">Pencairan TF</a>
            <span>/</span>
            <span class="text-gray-800 font-medium">{{ $pencairan->deposito?->nomor_deposito }}</span>
        </div>

        @php
            $dep = $pencairan->deposito;
            $nasabah = $pencairan->nasabah;
            $isCancel = $pencairan->is_cancel;
            $isEarly = $dep && $dep->tgl_jatuh_tempo > now();
            $bungaKotor = ($dep && !$isEarly) ? ($dep->nominal_awal * $dep->bunga * (($dep->tenor?->tenor_hari ?? 365) / 365)) : 0;
            $pajak = $bungaKotor * 0.2;
            
            if ($isCancel) {
                $estimasiCair = $dep ? $dep->nominal_awal : 0;
            } else {
                $estimasiCair = $dep ? ($dep->nominal_awal + $bungaKotor - $pajak) : 0;
            }
        @endphp

        {{-- Simplified Destination Card --}}
        <div class="bg-gradient-to-br {{ $isCancel ? 'from-red-600 to-red-800' : 'from-[#674c1d] to-[#8b6f2f]' }} rounded-2xl p-6 text-white mb-6 shadow-lg">
            <div class="flex items-center justify-between mb-4">
                <p class="text-white/80 text-[10px] font-bold uppercase tracking-widest">{{ $isCancel ? 'Pembatalan - Tujuan Transfer' : 'Tujuan Transfer (Nasabah)' }}</p>
                @if($isCancel)
                    <span class="bg-white/20 text-white text-[10px] px-2 py-1 rounded font-bold uppercase">Tanpa Bunga</span>
                @endif
            </div>
            <div class="flex items-center justify-between">
                <div>
                    <p class="text-2xl font-black font-mono tracking-tighter">{{ $nasabah?->dataRek?->no_rekening ?? '-' }}</p>
                    <p class="text-sm font-bold opacity-90">{{ $nasabah?->dataRek?->nama_bank ?? '-' }}</p>
                    <p class="text-xs opacity-70 mt-1">a.n {{ $nasabah?->dataRek?->nama_pemilik_rekening ?? ($nasabah?->user?->nama ?? '-') }}</p>
                </div>
                <div class="text-right">
                    <p class="text-white/60 text-[10px] font-bold uppercase">Estimasi Cair</p>
                    <p class="text-xl font-black">Rp {{ number_format($estimasiCair, 0, ',', '.') }}</p>
                </div>
            </div>
        </div>

        {{-- Simplified Form --}}
        <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
            @if(session('error'))
                <div class="mb-4 bg-red-50 border border-red-200 text-red-800 rounded-xl px-4 py-3 text-sm">{{ session('error') }}</div>
            @endif

            @php
                $isDirect = $pencairan->isPending() && auth()->user()->role === 'admin_operasional';
                $isManual = $pencairan->isDiproses();
            @endphp

            @if(auth()->user()->role === 'admin_utama' && $pencairan->isPending())
                {{-- OWNER FLOW: Send Dana to Admin --}}
                <form method="POST" action="{{ route('admin.deposito.pencairan-tf.proses', $pencairan->id) }}">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Admin Penerima</label>
                        <select name="admin_id" required class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                            <option value="">-- Pilih Admin --</option>
                            @foreach($admins as $adm)
                                <option value="{{ $adm->id }}">{{ $adm->nama }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nominal Transfer</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                            <input type="number" name="nominal_akhir" value="{{ round($estimasiCair) }}"
                                class="w-full border border-gray-200 rounded-xl pl-12 pr-4 py-3 font-bold text-lg text-[#674c1d] outline-none">
                        </div>
                    </div>
                    <button type="submit" class="w-full bg-[#674c1d] text-white py-4 rounded-xl font-bold hover:bg-[#8b6f2f] transition shadow-lg">
                        ✓ Kirim Dana ke Admin
                    </button>
                </form>
            @elseif($isDirect || $isManual)
                {{-- ADMIN FLOW: Finalize Liquidation --}}
                <form method="POST" action="{{ route('admin.deposito.pencairan-tf.finish', $pencairan->id) }}" enctype="multipart/form-data">
                    @csrf

                    <!-- Petty Cash Info Card -->
                    @if(isset($adminSaldoTransfer))
                    <div class="p-4 rounded-xl border-2 border-[#674c1d] bg-[#674c1d]/5 transition-all mb-5">
                        <div class="flex justify-between items-center">
                            <div>
                                <p class="text-[10px] uppercase tracking-wider text-gray-500 font-bold mb-1">
                                    Saldo Petty Cash (Transfer) Anda</p>
                                <p class="text-xl font-black text-[#674c1d]">Rp {{ number_format($adminSaldoTransfer, 0, ',', '.') }}</p>
                            </div>
                            <div class="text-right">
                                <div id="badge-petty-cash" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold">
                                    <!-- Dynamic badge content via JS -->
                                </div>
                            </div>
                        </div>
                    </div>
                    <div id="warningPetty" class="hidden p-3 bg-red-50 border border-red-200 rounded-xl mb-5">
                        <p class="text-xs text-red-600 font-semibold flex items-center">
                            <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                            </svg>
                            Saldo Petty Cash Anda tidak mencukupi untuk pencairan nominal ini!
                        </p>
                    </div>
                    @endif

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Nominal Transfer</label>
                        <div class="relative">
                            <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                            <input type="number" name="nominal_akhir" value="{{ $isManual ? round($pencairan->nominal_akhir) : round($estimasiCair) }}"
                                class="w-full border border-gray-200 rounded-xl pl-12 pr-4 py-3 font-bold text-lg text-[#674c1d] outline-none">
                        </div>
                    </div>

                    <div class="mb-5">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Pilih Bank Koperasi (Pengirim) *</label>
                        <select name="bank_pengirim" id="bank_pengirim" required onchange="calculateBiaya()"
                            class="w-full border border-gray-200 rounded-xl px-4 py-3 text-sm focus:ring-2 focus:ring-[#674c1d]/20 outline-none bg-white">
                            <option value="">-- Pilih Bank --</option>
                            <option value="BCA" selected>BCA (Bank Utama)</option>
                            <option value="BNI">BNI</option>
                            <option value="Mandiri">Mandiri</option>
                            <option value="BRI">BRI</option>
                        </select>
                    </div>

                    <!-- Container Biaya Admin -->
                    <div id="biaya-section" class="hidden p-4 bg-amber-50 border border-amber-200 rounded-xl mb-5">
                        <div class="flex items-center justify-between mb-2">
                            <p class="text-xs font-bold text-gray-500 uppercase">Biaya Transfer (dipotong dari tabungan):</p>
                            <p class="font-bold text-amber-700 text-sm" id="biaya-display">Rp 0</p>
                        </div>
                        <p class="text-[10px] text-amber-700">Biaya admin transfer antarbank ditanggung nasabah (dipotong dari saldo tabungan).</p>
                        <div id="warningTabungan" class="hidden mt-2 p-2 bg-red-100 rounded text-xs text-red-700 font-bold">
                            Saldo tabungan nasabah tidak cukup untuk membayar biaya transfer!
                        </div>
                    </div>

                    <div class="mb-6">
                        <label class="block text-xs font-bold text-gray-500 uppercase mb-2">Upload Bukti Foto</label>
                        <div class="relative group h-32">
                            <input type="file" name="foto_bukti_tf" accept="image/*" required
                                class="absolute inset-0 w-full h-full opacity-0 cursor-pointer z-10">
                            <div class="h-full border-2 border-dashed border-gray-200 rounded-xl flex flex-col items-center justify-center group-hover:border-[#674c1d] transition bg-gray-50/50">
                                <svg class="w-6 h-6 text-gray-400 mb-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-[10px] text-gray-500 font-bold uppercase tracking-tighter">Klik untuk Upload Bukti</p>
                            </div>
                        </div>
                    </div>

                    <button type="submit" class="w-full bg-[#674c1d] text-white py-4 rounded-xl font-bold hover:bg-[#8b6f2f] transition shadow-lg">
                        ✓ Konfirmasi Cairkan
                    </button>
                </form>
            @endif
        </div>
       </div>

    </div>

    <script>
        const bankNasabah = "{{ $pencairan->nasabah?->dataRek?->nama_bank ?? '' }}";
        const saldoTabunganNasabah = {{ $saldoTabunganNasabah ?? 0 }};
        const adminSaldoTransfer = {{ $adminSaldoTransfer ?? 0 }};
        const biayaTransferData = @json($biayaTransfer ?? []);

        function calculateBiaya() {
            const dropdown = document.getElementById('bank_pengirim');
            if (!dropdown) return;
            
            const bankPengirim = dropdown.value;
            const biayaSection = document.getElementById('biaya-section');
            const biayaDisplay = document.getElementById('biaya-display');
            
            if (!bankPengirim || bankNasabah === '') {
                if (biayaSection) biayaSection.classList.add('hidden');
                updateButtonState(0);
                return;
            }
            
            let biayaAdmin = 0;
            const mapping = biayaTransferData.find(b => b.bank_pengirim === bankPengirim && b.bank_penerima === bankNasabah);
            
            if (mapping) {
                biayaAdmin = parseFloat(mapping.biaya_admin);
            } else if (bankPengirim !== bankNasabah) {
                biayaAdmin = 6500; // default antarbank
            }
            
            if (biayaAdmin > 0 && biayaSection) {
                biayaSection.classList.remove('hidden');
                biayaDisplay.innerText = 'Rp ' + biayaAdmin.toLocaleString('id-ID');
            } else if (biayaSection) {
                biayaSection.classList.add('hidden');
            }
            
            updateButtonState(biayaAdmin);
        }

        function updateButtonState(biayaAdmin = 0) {
            const warningTabungan = document.getElementById('warningTabungan');
            const warningPetty = document.getElementById('warningPetty');
            const badgePetty = document.getElementById('badge-petty-cash');
            const btnSubmit = document.querySelector('form[action$="/finish"] button[type="submit"]');
            
            const inputNominal = document.querySelector('input[name="nominal_akhir"]');
            const nominalAkhir = inputNominal ? parseFloat(inputNominal.value) || 0 : 0;

            const isInsufficientTabungan = (saldoTabunganNasabah < biayaAdmin);
            const isInsufficientPetty = (adminSaldoTransfer < nominalAkhir);

            // Update badge Petty Cash
            if (badgePetty) {
                if (isInsufficientPetty) {
                    badgePetty.className = "inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-red-100 text-red-700";
                    badgePetty.innerHTML = '<span class="w-2 h-2 rounded-full bg-red-500 mr-1.5"></span>Saldo Kurang';
                } else {
                    badgePetty.className = "inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700";
                    badgePetty.innerHTML = '<span class="w-2 h-2 rounded-full bg-green-500 mr-1.5"></span>Saldo Cukup';
                }
            }

            if (isInsufficientPetty) {
                if (warningPetty) warningPetty.classList.remove('hidden');
                if (warningTabungan) warningTabungan.classList.add('hidden');
                if (btnSubmit) {
                    btnSubmit.disabled = true;
                    btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                    btnSubmit.innerText = 'Saldo Admin Kurang';
                }
            } else if (isInsufficientTabungan) {
                if (warningPetty) warningPetty.classList.add('hidden');
                if (warningTabungan) warningTabungan.classList.remove('hidden');
                if (btnSubmit) {
                    btnSubmit.disabled = true;
                    btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                    btnSubmit.innerText = 'Saldo Nasabah Kurang';
                }
            } else {
                if (warningPetty) warningPetty.classList.add('hidden');
                if (warningTabungan) warningTabungan.classList.add('hidden');
                if (btnSubmit) {
                    btnSubmit.disabled = false;
                    btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                    btnSubmit.innerText = '✓ Konfirmasi Cairkan';
                }
            }
        }

        // Run initially
        document.addEventListener('DOMContentLoaded', function() {
            calculateBiaya();
            
            const inputNominal = document.querySelector('input[name="nominal_akhir"]');
            if (inputNominal) {
                inputNominal.addEventListener('input', calculateBiaya);
            }
        });
    </script>
@endsection