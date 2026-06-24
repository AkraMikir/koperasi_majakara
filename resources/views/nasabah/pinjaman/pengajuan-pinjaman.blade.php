@extends('layouts.nasabah')

@section('title', 'Pengajuan Pinjaman')

@section('content')
<div class="w-full pb-6">
    <!-- Back Button -->
    <div class="mx-4 mt-4 mb-4">
        <a href="{{ route('nasabah.pinjaman.index') }}"
            class="inline-flex items-center gap-2 px-4 py-2 bg-white rounded-xl shadow hover:shadow-md transition-all text-gray-700 hover:text-[#674c1d]">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
            </svg>
            Kembali
        </a>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="mx-4 mb-4">
        <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 rounded-lg shadow-md">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                </svg>
                <p class="font-semibold">{{ session('success') }}</p>
            </div>
        </div>
    </div>
    @endif
    @if(session('error'))
    <div class="mx-4 mb-4">
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md">
            <div class="flex items-center">
                <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <p class="font-semibold">{{ session('error') }}</p>
            </div>
        </div>
    </div>
    @endif
    @if($errors->any())
    <div class="mx-4 mb-4">
        <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 rounded-lg shadow-md">
            <div class="flex items-start">
                <svg class="w-5 h-5 mr-2 mt-0.5" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"></path>
                </svg>
                <div>
                    <p class="font-semibold mb-2">Terjadi kesalahan:</p>
                    <ul class="list-disc list-inside">
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>
        </div>
    </div>
    @endif

    <!-- Limit Pinjaman Info -->
    <div class="mx-4 mb-6 grid grid-cols-1 md:grid-cols-2 gap-4">
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-linear-to-br from-green-500 to-emerald-600 rounded-xl flex items-center justify-center text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Limit Pinjaman Anda</p>
                <p class="text-lg font-bold text-gray-900">Rp {{ number_format($sisaLimit, 0, ',', '.') }}</p>
            </div>
        </div>
        <div class="bg-white p-5 rounded-2xl shadow-sm border border-gray-100 flex items-center gap-4">
            <div class="w-12 h-12 bg-linear-to-br from-red-500 to-rose-600 rounded-xl flex items-center justify-center text-white">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
            </div>
            <div>
                <p class="text-xs text-gray-500 font-medium">Limit Terpakai</p>
                <p class="text-lg font-bold text-gray-900">Rp {{ number_format($nominalTerpakai, 0, ',', '.') }}</p>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="mx-4 mb-6">
        <div class="bg-gradient-to-br from-[#8b6f2f] via-[#a0824d] to-[#d4af37] rounded-3xl shadow-2xl p-4 sm:p-6 md:p-8 border-2 border-[#d4af37]/30 relative overflow-hidden">
            <div class="relative z-10">
                <h1 class="text-3xl font-bold text-white mb-2 font-display">Ajukan Pinjaman</h1>
                <p class="text-white/90 text-sm">Pilih metode pencairan pinjaman Anda</p>
            </div>
        </div>
    </div>

    <!-- Pilihan Metode -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-linear-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-[#8b6f2f] font-display">Pilih Metode Pencairan</h2>
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Metode Transfer -->
                <button type="button" onclick="selectMethod('transfer')" id="btn-transfer" class="group p-6 bg-linear-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-gray-200 hover:border-[#8b6f2f] transition-all text-left">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 bg-linear-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Transfer</h3>
                            <p class="text-sm text-gray-600">Via rekening bank</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path>
                        </svg>
                        <span>Pencairan via transfer bank</span>
                    </div>
                </button>

                <!-- Metode Tunai -->
                <button type="button" onclick="selectMethod('tunai')" id="btn-tunai" class="group p-6 bg-linear-to-br from-gray-50 to-gray-100 rounded-2xl border-2 border-gray-200 hover:border-[#674c1d] transition-all text-left">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="w-14 h-14 bg-linear-to-br from-[#674c1d] to-[#4a3514] rounded-xl flex items-center justify-center shadow-md group-hover:scale-110 transition-transform">
                            <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 mb-1">Tunai</h3>
                            <p class="text-sm text-gray-600">Janji temu di lokasi</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2 text-sm text-gray-500">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        </svg>
                        <span>Pencairan tunai di kantor</span>
                    </div>
                </button>
            </div>
        </div>
    </div>

    <!-- Form Transfer (muncul di bawah, inline) -->
    <div id="form-transfer-section" class="mx-4 mb-6 hidden">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 font-display mb-6">Formulir Pengajuan Pinjaman Transfer</h2>

            <form id="form-transfer" method="POST" action="{{ route('nasabah.pinjaman.submit-pengajuan-transfer') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="jenis_pencairan" value="transfer">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Pinjaman *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                        <input type="text" name="nominal" id="nominal-transfer" placeholder="0" required
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-lg font-semibold"
                            oninput="formatCurrencyTransfer(this)" value="{{ old('nominal') }}">
                        <input type="hidden" name="nominal_raw" id="nominal_raw_transfer" value="{{ old('nominal_raw') }}">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Minimum: Rp 100.000</p>
                    @error('nominal')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Durasi Pinjaman (Bulan) *</label>
                    <select name="durasi" id="durasi-transfer" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                        <option value="">Pilih durasi</option>
                        @foreach($durasiList as $val)
                            <option value="{{ $val }}" {{ old('durasi') == $val ? 'selected' : '' }}>{{ $val }} bulan</option>
                        @endforeach
                    </select>
                    <p class="text-xs text-gray-500 mt-2">Pilih jangka waktu pinjaman</p>
                    @error('durasi')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tujuan Pinjaman *</label>
                    <select name="id_tujuan" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                        <option value="">Pilih tujuan pinjaman</option>
                        @foreach($tujuanList as $tujuan)
                            <option value="{{ $tujuan->id }}" {{ old('id_tujuan') == $tujuan->id ? 'selected' : '' }}>{{ $tujuan->tujuan }}</option>
                        @endforeach
                    </select>
                    @error('id_tujuan')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>


                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none resize-none">{{ old('keterangan') }}</textarea>
                </div>


                <!-- Estimasi & Simulasi -->
                <div class="p-6 bg-linear-to-br from-[#8b6f2f]/10 to-[#d4af37]/10 rounded-xl border border-[#8b6f2f]/20">
                    <h3 class="text-sm font-semibold text-[#8b6f2f] mb-4">Estimasi Pinjaman</h3>
                    <div class="space-y-3" id="estimasiSectionTransfer">
                        <div class="flex justify-between items-center flex-wrap gap-1">
                            <span class="text-sm text-gray-600">Nominal Pinjaman:</span>
                            <span class="font-semibold text-gray-900" id="estimasiNominalTransfer">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600">Bunga:</span>
                            <div class="text-right">
                                <span class="font-semibold text-gray-900 block">flat 0,2% per hari</span>
                                <span class="text-sm text-gray-500 block" id="estimasiBungaTransfer">-</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center flex-wrap gap-1">
                            <span class="text-sm text-gray-600">Total yang Harus Dibayar:</span>
                            <span class="text-lg font-bold text-[#8b6f2f]" id="estimasiTotalTransfer">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center flex-wrap gap-1">
                            <span class="text-sm text-gray-600">Angsuran per bulan (contoh):</span>
                            <span class="font-semibold text-gray-900" id="estimasiAngsuranTransfer">Rp 0</span>
                        </div>
                    </div>
                </div>
                <div class="mb-6" id="simulasiTableSectionTransfer" style="display: none;">
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="bg-linear-to-r from-[#8b6f2f] to-[#a0824d] text-white p-4">
                            <h3 class="text-lg font-bold">Simulasi Angsuran Per Bulan</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Bulan</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Jatuh Tempo</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="simulasiTableBodyTransfer"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="button" onclick="showPinModalTransfer()" class="w-full py-4 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]">
                        Ajukan Pinjaman
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Form Tunai / Janji Temu (muncul di bawah, inline) -->
    <div id="form-tunai-section" class="mx-4 mb-6 hidden">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-gray-900 font-display mb-6">Formulir Janji Temu (Pencairan Tunai)</h2>

            <form id="form-tunai" method="POST" action="{{ route('nasabah.pinjaman.submit-janji-temu') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="jenis_pencairan" value="tunai">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Nominal Pinjaman *</label>
                    <div class="relative">
                        <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500 font-medium">Rp</span>
                        <input type="text" name="nominal" id="nominal-tunai" placeholder="0" required
                            class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none text-lg font-semibold"
                            oninput="formatCurrencyTunai(this)" value="{{ old('nominal', request('nominal')) }}">
                        <input type="hidden" name="nominal_raw" id="nominal_raw_tunai" value="{{ old('nominal_raw') }}">
                    </div>
                    <p class="text-xs text-gray-500 mt-2">Minimum: Rp 100.000</p>
                    @error('nominal')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Durasi Pinjaman (Bulan) *</label>
                    <select name="durasi" id="durasi-tunai" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none"
                        onchange="updateEstimasiTunai()">
                        <option value="">Pilih durasi</option>
                        @foreach($durasiList as $val)
                            <option value="{{ $val }}" {{ old('durasi') == $val ? 'selected' : '' }}>{{ $val }} bulan</option>
                        @endforeach
                    </select>
                    @error('durasi')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tujuan Pinjaman *</label>
                    <select name="id_tujuan" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                        <option value="">Pilih tujuan pinjaman</option>
                        @foreach($tujuanList as $tujuan)
                            <option value="{{ $tujuan->id }}" {{ old('id_tujuan') == $tujuan->id ? 'selected' : '' }}>{{ $tujuan->tujuan }}</option>
                        @endforeach
                    </select>
                    @error('id_tujuan')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Pilih Lokasi Kantor *</label>
                    <select name="lokasi_temu" required class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none">
                        <option value="">-- Pilih Lokasi --</option>
                        @foreach($lokasi ?? [] as $loc)
                        <option value="{{ $loc->id }}" {{ old('lokasi_temu') == $loc->id ? 'selected' : '' }}>{{ $loc->nama_lokasi }} - {{ $loc->kota }}, {{ $loc->provinsi }}</option>
                        @endforeach
                    </select>
                    @error('lokasi_temu')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Tanggal Janji Temu *</label>
                    <input type="date" name="tanggal_janji_temu" required min="{{ date('Y-m-d', strtotime('+1 day')) }}"
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none" value="{{ old('tanggal_janji_temu') }}">
                    <p class="text-xs text-gray-500 mt-2">Pilih tanggal minimal besok</p>
                    @error('tanggal_janji_temu')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Waktu Janji Temu *</label>
                    <input type="time" name="waktu_janji_temu" required
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none" value="{{ old('waktu_janji_temu') }}">
                    <p class="text-xs text-gray-500 mt-2">Jam operasional: 08:00 - 16:00</p>
                    @error('waktu_janji_temu')<p class="text-xs text-red-600 mt-1">{{ $message }}</p>@enderror
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="3" placeholder="Tambahkan keterangan jika diperlukan..."
                        class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-[#674c1d] focus:ring-2 focus:ring-[#674c1d]/20 outline-none resize-none">{{ old('keterangan', request('keterangan')) }}</textarea>
                </div>

                <!-- Estimasi & Simulasi (sama seperti form transfer) -->
                <div class="p-6 bg-linear-to-br from-[#8b6f2f]/10 to-[#d4af37]/10 rounded-xl border border-[#8b6f2f]/20">
                    <h3 class="text-sm font-semibold text-[#8b6f2f] mb-4">Estimasi Pinjaman</h3>
                    <div class="space-y-3" id="estimasiSectionTunai">
                        <div class="flex justify-between items-center flex-wrap gap-1">
                            <span class="text-sm text-gray-600">Nominal Pinjaman:</span>
                            <span class="font-semibold text-gray-900" id="estimasiNominalTunai">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-start">
                            <span class="text-sm text-gray-600">Bunga:</span>
                            <div class="text-right">
                                <span class="font-semibold text-gray-900 block">flat 0,2% per hari</span>
                                <span class="text-sm text-gray-500 block" id="estimasiBungaTunai">-</span>
                            </div>
                        </div>
                        <div class="flex justify-between items-center flex-wrap gap-1">
                            <span class="text-sm text-gray-600">Total yang Harus Dibayar:</span>
                            <span class="text-lg font-bold text-[#8b6f2f]" id="estimasiTotalTunai">Rp 0</span>
                        </div>
                        <div class="flex justify-between items-center flex-wrap gap-1">
                            <span class="text-sm text-gray-600">Angsuran per bulan (contoh):</span>
                            <span class="font-semibold text-gray-900" id="estimasiAngsuranTunai">Rp 0</span>
                        </div>
                    </div>
                </div>
                <div class="mb-6" id="simulasiTableSectionTunai" style="display: none;">
                    <div class="bg-white rounded-xl border border-gray-200 overflow-hidden">
                        <div class="bg-linear-to-r from-[#8b6f2f] to-[#a0824d] text-white p-4">
                            <h3 class="text-lg font-bold">Simulasi Angsuran Per Bulan</h3>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Bulan</th>
                                        <th class="px-4 py-3 text-left text-xs font-semibold text-gray-700 uppercase">Jatuh Tempo</th>
                                        <th class="px-4 py-3 text-right text-xs font-semibold text-gray-700 uppercase">Total</th>
                                    </tr>
                                </thead>
                                <tbody id="simulasiTableBodyTunai"></tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="pt-4">
                    <button type="button" onclick="showPinModalTunai()" class="w-full py-4 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold text-lg shadow-lg hover:shadow-xl transition-all transform hover:scale-[1.02]">
                        Buat Janji Temu
                    </button>
                </div>
            </form>
        </div>
    </div>

    <!-- Riwayat Pengajuan -->
    <div class="mx-4 mb-6">
        <div class="bg-white rounded-2xl shadow-lg p-6 border border-gray-100">
            <h2 class="text-lg font-bold text-[#8b6f2f] mb-6 font-display">Riwayat Pengajuan</h2>
            <div class="overflow-x-auto">
                <table class="w-full">
                    <thead>
                        <tr class="border-b-2 border-[#8b6f2f]/20">
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Tanggal</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Nominal</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Jenis</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Durasi</th>
                            <th class="px-4 py-3 text-left text-xs font-bold text-[#8b6f2f] uppercase">Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse(($riwayatPengajuan ?? []) as $pengajuan)
                        <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors cursor-pointer" role="button" tabindex="0" data-href="{{ route('nasabah.pinjaman.detail-pengajuan', $pengajuan->id) }}" onclick="window.location.href=this.dataset.href">
                            <td class="px-4 py-3 text-sm">
                                <p class="font-medium text-gray-900">{{ $pengajuan->tgl_pengajuan->format('d M Y') }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <p class="font-semibold text-gray-900">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</p>
                            </td>
                            <td class="px-4 py-3">
                                <span class="px-3 py-1 bg-blue-100 text-blue-700 rounded-full text-xs font-semibold">{{ ucfirst($pengajuan->jenis ?? 'bulanan') }}</span>
                            </td>
                            <td class="px-4 py-3 text-sm text-gray-600">{{ $pengajuan->durasi }} bulan</td>
                            <td class="px-4 py-3">
                                @php
                                    $statusLabel = 'Pending';
                                    if (($pengajuan->status ?? '1') == '2') $statusLabel = 'Ditolak';
                                    elseif (($pengajuan->status ?? '1') == '3') $statusLabel = 'Disetujui';
                                    elseif (($pengajuan->status ?? '1') == '4') $statusLabel = 'Terlaksana';
                                @endphp
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if(($pengajuan->status ?? '1') == '4') bg-green-100 text-green-700
                                    @elseif(($pengajuan->status ?? '1') == '2') bg-red-100 text-red-700
                                    @elseif(($pengajuan->status ?? '1') == '3') bg-blue-100 text-blue-700
                                    @else bg-yellow-100 text-yellow-700 @endif">{{ $statusLabel }}</span>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-4 py-12 text-center text-gray-500">Belum ada riwayat pengajuan</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- PIN Modal Transfer -->
<div id="pin-modal-transfer" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[100] hidden items-center justify-center p-4" style="z-index: 100;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Verifikasi PIN</h3>
                    <p class="text-sm text-gray-600">Masukkan PIN Anda</p>
                </div>
            </div>
            <button type="button" onclick="closePinModalTransfer()" class="p-2 -mr-2 text-gray-400 hover:text-gray-600">
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
            <p id="pin-error-transfer" class="hidden text-sm text-red-600 mt-2"></p>
        </div>
        <button type="button" onclick="submitFormTransfer()" class="w-full py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold hover:shadow-lg transition-all">Konfirmasi</button>
    </div>
</div>

<!-- PIN Modal Tunai -->
<div id="pin-modal-tunai" class="fixed inset-0 bg-black/50 backdrop-blur-sm z-[100] hidden items-center justify-center p-4" style="z-index: 100;">
    <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-6">
        <div class="flex items-center justify-between mb-6">
            <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-lg font-bold text-gray-900">Verifikasi PIN</h3>
                    <p class="text-sm text-gray-600">Masukkan PIN Anda</p>
                </div>
            </div>
            <button type="button" onclick="closePinModalTunai()" class="p-2 -mr-2 text-gray-400 hover:text-gray-600">
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
            <p id="pin-error-tunai" class="hidden text-sm text-red-600 mt-2"></p>
        </div>
        <button type="button" onclick="submitFormTunai()" class="w-full py-3 bg-linear-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-xl font-bold hover:shadow-lg transition-all">Konfirmasi</button>
    </div>
</div>

@if(isset($hasAgreed) && !$hasAgreed)
<!-- Syarat & Ketentuan Modal sebelum PIN -->
<div id="apply-terms-modal" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-[100] hidden items-center justify-center p-4" style="z-index: 100;">
    <div class="bg-white rounded-3xl shadow-2xl max-w-xl w-full p-6 md:p-8 flex flex-col max-h-[90vh] border border-gray-100 animate-fade-in">
        <div class="flex items-center gap-3 pb-4 border-b border-gray-100 mb-5">
            <div class="w-12 h-12 bg-gradient-to-br from-[#8b6f2f] to-[#d4af37] rounded-xl flex items-center justify-center shadow-md">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-900 font-display">Syarat & Ketentuan Pinjaman</h3>
                <p class="text-xs text-gray-500">Koperasi Majakara</p>
            </div>
        </div>

        <!-- Scrollable T&C Content Box -->
        <div id="apply-terms-content-box" class="flex-1 overflow-y-auto max-h-[45vh] sm:max-h-[300px] border border-gray-200 rounded-2xl p-4 bg-gray-50 text-sm text-gray-700 leading-relaxed space-y-4 mb-5 font-sans">
            {!! $syaratPinjaman !!}
        </div>

        <!-- Bottom Actions -->
        <div class="pt-4 border-t border-gray-100 space-y-4">
            <!-- Checkbox -->
            <label class="flex items-start gap-3 cursor-pointer group">
                <input type="checkbox" id="apply-terms-checkbox" disabled
                    class="mt-1 w-4 h-4 text-[#8b6f2f] border-gray-300 rounded focus:ring-[#8b6f2f] focus:ring-2 disabled:opacity-50 disabled:cursor-not-allowed">
                <span id="apply-terms-checkbox-label" class="text-xs text-gray-500 select-none group-hover:text-gray-700 font-medium">
                    Harap gulir syarat dan ketentuan sampai ke bagian paling bawah untuk menyetujui.
                </span>
            </label>

            <!-- Buttons -->
            <div class="flex gap-3">
                <button type="button" onclick="closeApplyTermsModal()" class="flex-1 py-3 px-4 bg-gray-100 hover:bg-gray-200 text-gray-700 rounded-xl font-semibold transition-colors">
                    Batal
                </button>
                <button type="button" id="btn-apply-agree-terms" disabled
                    class="flex-1 py-3 px-4 bg-gray-300 text-gray-500 rounded-xl font-bold transition-all text-center cursor-not-allowed shadow-md">
                    Setuju & Lanjutkan
                </button>
            </div>
        </div>
    </div>
</div>
@endif

@push('scripts')
<script>
let debounceSimulasi = null;

function selectMethod(method) {
    document.querySelectorAll('[id^="btn-"]').forEach(btn => {
        btn.classList.remove('border-[#674c1d]', 'border-[#8b6f2f]', 'bg-linear-to-br', 'from-[#674c1d]/10', 'to-[#8b6f2f]/10');
        btn.classList.add('border-gray-200');
    });
    document.getElementById('form-transfer-section').classList.add('hidden');
    document.getElementById('form-tunai-section').classList.add('hidden');

    if (method === 'transfer') {
        document.getElementById('btn-transfer').classList.add('border-[#8b6f2f]', 'bg-linear-to-br', 'from-[#8b6f2f]/10', 'to-[#d4af37]/10');
        document.getElementById('form-transfer-section').classList.remove('hidden');
        updateEstimasiTransfer();
    } else {
        document.getElementById('btn-tunai').classList.add('border-[#674c1d]', 'bg-linear-to-br', 'from-[#674c1d]/10', 'to-[#8b6f2f]/10');
        document.getElementById('form-tunai-section').classList.remove('hidden');
        updateEstimasiTunai();
    }
    setTimeout(() => {
        const el = method === 'transfer' ? document.getElementById('form-transfer-section') : document.getElementById('form-tunai-section');
        if (el) el.scrollIntoView({ behavior: 'smooth', block: 'nearest' });
    }, 100);
}


function formatCurrencyTransfer(input) {
    let v = input.value.replace(/[^0-9]/g, '');
    if (v) v = parseInt(v).toLocaleString('id-ID');
    input.value = v;
    document.getElementById('nominal_raw_transfer').value = input.value.replace(/\./g, '');
    updateEstimasiTransfer();
}

function formatCurrencyTunai(input) {
    let v = input.value.replace(/[^0-9]/g, '');
    if (v) v = parseInt(v).toLocaleString('id-ID');
    input.value = v;
    document.getElementById('nominal_raw_tunai').value = input.value.replace(/\./g, '');
    updateEstimasiTunai();
}

function updateEstimasiTransfer() {
    const raw = document.getElementById('nominal_raw_transfer').value.replace(/\./g, '');
    const nominal = parseFloat(raw) || 0;
    const durasi = parseInt(document.getElementById('durasi-transfer').value) || 0;
    if (nominal < 100000 || durasi < 1) {
        document.getElementById('estimasiNominalTransfer').textContent = 'Rp 0';
        document.getElementById('estimasiBungaTransfer').textContent = '-';
        document.getElementById('estimasiTotalTransfer').textContent = 'Rp 0';
        document.getElementById('estimasiAngsuranTransfer').textContent = 'Rp 0';
        document.getElementById('simulasiTableSectionTransfer').style.display = 'none';
        return;
    }
    clearTimeout(debounceSimulasi);
    debounceSimulasi = setTimeout(() => {
        fetch('{{ route("nasabah.pinjaman.simulasi-angsuran") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ nominal: nominal, durasi: durasi })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            const d = data.data;
            document.getElementById('estimasiNominalTransfer').textContent = 'Rp ' + d.nominal.toLocaleString('id-ID');
            document.getElementById('estimasiBungaTransfer').textContent = d.bunga_persen + '% (Rp ' + d.bunga_total.toLocaleString('id-ID') + ')';
            document.getElementById('estimasiTotalTransfer').textContent = 'Rp ' + d.total_yang_harus_dibayar.toLocaleString('id-ID');
            document.getElementById('estimasiAngsuranTransfer').textContent = 'Rp ' + d.angsuran_per_bulan.toLocaleString('id-ID');
            const tbody = document.getElementById('simulasiTableBodyTransfer');
            tbody.innerHTML = '';
            (d.simulasi || []).forEach(item => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50';
                tr.innerHTML = '<td class="px-4 py-3 text-sm text-gray-900">' + item.bulan + '</td><td class="px-4 py-3 text-sm text-gray-700">' + item.tanggal + '</td><td class="px-4 py-3 text-sm font-semibold text-[#8b6f2f] text-right">Rp ' + item.total.toLocaleString('id-ID') + '</td>';
                tbody.appendChild(tr);
            });
            document.getElementById('simulasiTableSectionTransfer').style.display = 'block';
        })
        .catch(() => {});
    }, 400);
}

function updateEstimasiTunai() {
    const raw = document.getElementById('nominal_raw_tunai').value.replace(/\./g, '');
    const nominal = parseFloat(raw) || 0;
    const durasi = parseInt(document.getElementById('durasi-tunai').value) || 0;
    if (nominal < 100000 || durasi < 1) {
        document.getElementById('estimasiNominalTunai').textContent = 'Rp 0';
        document.getElementById('estimasiBungaTunai').textContent = '-';
        document.getElementById('estimasiTotalTunai').textContent = 'Rp 0';
        document.getElementById('estimasiAngsuranTunai').textContent = 'Rp 0';
        document.getElementById('simulasiTableSectionTunai').style.display = 'none';
        return;
    }
    clearTimeout(debounceSimulasi);
    debounceSimulasi = setTimeout(() => {
        fetch('{{ route("nasabah.pinjaman.simulasi-angsuran") }}', {
            method: 'POST',
            headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}', 'Accept': 'application/json' },
            body: JSON.stringify({ nominal: nominal, durasi: durasi })
        })
        .then(r => r.json())
        .then(data => {
            if (!data.success) return;
            const d = data.data;
            document.getElementById('estimasiNominalTunai').textContent = 'Rp ' + d.nominal.toLocaleString('id-ID');
            document.getElementById('estimasiBungaTunai').textContent = d.bunga_persen + '% (Rp ' + d.bunga_total.toLocaleString('id-ID') + ')';
            document.getElementById('estimasiTotalTunai').textContent = 'Rp ' + d.total_yang_harus_dibayar.toLocaleString('id-ID');
            document.getElementById('estimasiAngsuranTunai').textContent = 'Rp ' + d.angsuran_per_bulan.toLocaleString('id-ID');
            const tbody = document.getElementById('simulasiTableBodyTunai');
            tbody.innerHTML = '';
            (d.simulasi || []).forEach(item => {
                const tr = document.createElement('tr');
                tr.className = 'hover:bg-gray-50';
                tr.innerHTML = '<td class="px-4 py-3 text-sm text-gray-900">' + item.bulan + '</td><td class="px-4 py-3 text-sm text-gray-700">' + item.tanggal + '</td><td class="px-4 py-3 text-sm font-semibold text-[#8b6f2f] text-right">Rp ' + item.total.toLocaleString('id-ID') + '</td>';
                tbody.appendChild(tr);
            });
            document.getElementById('simulasiTableSectionTunai').style.display = 'block';
        })
        .catch(() => {});
    }, 400);
}

function showPinModalTransfer() {
    const form = document.getElementById('form-transfer');
    if (!form.checkValidity()) { form.reportValidity(); return; }
    
    @if(isset($hasAgreed) && !$hasAgreed)
        if (window.hasAgreedToTerms) {
            document.getElementById('pin-modal-transfer').classList.remove('hidden');
            document.getElementById('pin-modal-transfer').classList.add('flex');
            document.getElementById('pin-input-transfer').focus();
        } else {
            showApplyTermsModal('transfer');
        }
    @else
        document.getElementById('pin-modal-transfer').classList.remove('hidden');
        document.getElementById('pin-modal-transfer').classList.add('flex');
        document.getElementById('pin-input-transfer').focus();
    @endif
}

function closePinModalTransfer() {
    document.getElementById('pin-modal-transfer').classList.add('hidden');
    document.getElementById('pin-modal-transfer').classList.remove('flex');
    document.getElementById('pin-input-transfer').value = '';
    document.getElementById('pin-error-transfer').classList.add('hidden');
}

function submitFormTransfer() {
    const pin = document.getElementById('pin-input-transfer').value;
    if (pin.length !== 6) {
        document.getElementById('pin-error-transfer').textContent = 'PIN harus 6 digit';
        document.getElementById('pin-error-transfer').classList.remove('hidden');
        return;
    }
    fetch('{{ route("nasabah.pinjaman.verify-pin") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ pin: pin })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const nominalInput = document.getElementById('nominal-transfer');
            const raw = document.getElementById('nominal_raw_transfer');
            raw.value = nominalInput.value.replace(/[^0-9]/g, '');
            nominalInput.value = raw.value;
            const pinInput = document.createElement('input');
            pinInput.type = 'hidden';
            pinInput.name = 'pin';
            pinInput.value = pin;
            document.getElementById('form-transfer').appendChild(pinInput);
            document.getElementById('form-transfer').submit();
        } else {
            document.getElementById('pin-error-transfer').textContent = data.message || 'PIN salah';
            document.getElementById('pin-error-transfer').classList.remove('hidden');
        }
    })
    .catch(() => {
        document.getElementById('pin-error-transfer').textContent = 'Terjadi kesalahan, coba lagi';
        document.getElementById('pin-error-transfer').classList.remove('hidden');
    });
}

function showPinModalTunai() {
    const form = document.getElementById('form-tunai');
    if (!form.checkValidity()) { form.reportValidity(); return; }
    
    @if(isset($hasAgreed) && !$hasAgreed)
        if (window.hasAgreedToTerms) {
            document.getElementById('pin-modal-tunai').classList.remove('hidden');
            document.getElementById('pin-modal-tunai').classList.add('flex');
            document.getElementById('pin-input-tunai').focus();
        } else {
            showApplyTermsModal('tunai');
        }
    @else
        document.getElementById('pin-modal-tunai').classList.remove('hidden');
        document.getElementById('pin-modal-tunai').classList.add('flex');
        document.getElementById('pin-input-tunai').focus();
    @endif
}

function closePinModalTunai() {
    document.getElementById('pin-modal-tunai').classList.add('hidden');
    document.getElementById('pin-modal-tunai').classList.remove('flex');
    document.getElementById('pin-input-tunai').value = '';
    document.getElementById('pin-error-tunai').classList.add('hidden');
}

function submitFormTunai() {
    const pin = document.getElementById('pin-input-tunai').value;
    if (pin.length !== 6) {
        document.getElementById('pin-error-tunai').textContent = 'PIN harus 6 digit';
        document.getElementById('pin-error-tunai').classList.remove('hidden');
        return;
    }
    fetch('{{ route("nasabah.pinjaman.verify-pin") }}', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': '{{ csrf_token() }}' },
        body: JSON.stringify({ pin: pin })
    })
    .then(r => r.json())
    .then(data => {
        if (data.success) {
            const nominalInput = document.getElementById('nominal-tunai');
            const raw = document.getElementById('nominal_raw_tunai');
            raw.value = nominalInput.value.replace(/[^0-9]/g, '');
            nominalInput.value = raw.value;
            const pinInput = document.createElement('input');
            pinInput.type = 'hidden';
            pinInput.name = 'pin';
            pinInput.value = pin;
            document.getElementById('form-tunai').appendChild(pinInput);
            document.getElementById('form-tunai').submit();
        } else {
            document.getElementById('pin-error-tunai').textContent = data.message || 'PIN salah';
            document.getElementById('pin-error-tunai').classList.remove('hidden');
        }
    })
    .catch(() => {
        document.getElementById('pin-error-tunai').textContent = 'Terjadi kesalahan, coba lagi';
        document.getElementById('pin-error-tunai').classList.remove('hidden');
    });
}

document.getElementById('durasi-transfer').addEventListener('change', updateEstimasiTransfer);
document.getElementById('durasi-tunai').addEventListener('change', updateEstimasiTunai);

const openMetode = '{{ $openMetode ?? "" }}';
if (openMetode === 'transfer') selectMethod('transfer');
else if (openMetode === 'tunai') selectMethod('tunai');

// T&C helper variables & functions
window.hasAgreedToTerms = false;
let pendingLoanMethod = null;

function showApplyTermsModal(method) {
    pendingLoanMethod = method;
    const modal = document.getElementById('apply-terms-modal');
    if (!modal) return;
    
    modal.classList.remove('hidden');
    modal.classList.add('flex');

    const termsBox = document.getElementById('apply-terms-content-box');
    const checkbox = document.getElementById('apply-terms-checkbox');
    const label = document.getElementById('apply-terms-checkbox-label');
    const btnAgree = document.getElementById('btn-apply-agree-terms');

    if (!termsBox || !checkbox || !btnAgree) return;

    // Detect scroll to bottom
    if (termsBox.scrollHeight <= termsBox.clientHeight) {
        enableApplyCheckbox();
    } else {
        termsBox.addEventListener('scroll', function scrollHandler() {
            if (termsBox.scrollHeight - termsBox.scrollTop - termsBox.clientHeight < 15) {
                enableApplyCheckbox();
                termsBox.removeEventListener('scroll', scrollHandler);
            }
        });
    }

    function enableApplyCheckbox() {
        checkbox.removeAttribute('disabled');
        if (label) {
            label.textContent = "Saya telah membaca, memahami, dan menyetujui seluruh Syarat & Ketentuan di atas.";
            label.classList.remove('text-gray-500');
            label.classList.add('text-gray-800');
        }
    }

    checkbox.addEventListener('change', function() {
        if (checkbox.checked) {
            btnAgree.removeAttribute('disabled');
            btnAgree.classList.remove('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            btnAgree.classList.add('bg-gradient-to-r', 'from-[#674c1d]', 'to-[#8b6f2f]', 'text-white', 'hover:shadow-lg');
            btnAgree.style.cursor = 'pointer';
        } else {
            btnAgree.setAttribute('disabled', 'true');
            btnAgree.classList.add('bg-gray-300', 'text-gray-500', 'cursor-not-allowed');
            btnAgree.classList.remove('bg-gradient-to-r', 'from-[#674c1d]', 'to-[#8b6f2f]', 'text-white', 'hover:shadow-lg');
            btnAgree.style.cursor = 'not-allowed';
        }
    });

    btnAgree.onclick = function() {
        if (btnAgree.getAttribute('disabled') === 'true' || !checkbox.checked) return;

        btnAgree.setAttribute('disabled', 'true');
        btnAgree.textContent = 'Menyimpan...';

        fetch('{{ route("nasabah.pinjaman.agree-terms") }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({})
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                closeApplyTermsModal();
                window.hasAgreedToTerms = true;

                if (pendingLoanMethod === 'transfer') {
                    document.getElementById('pin-modal-transfer').classList.remove('hidden');
                    document.getElementById('pin-modal-transfer').classList.add('flex');
                    document.getElementById('pin-input-transfer').focus();
                } else if (pendingLoanMethod === 'tunai') {
                    document.getElementById('pin-modal-tunai').classList.remove('hidden');
                    document.getElementById('pin-modal-tunai').classList.add('flex');
                    document.getElementById('pin-input-tunai').focus();
                }
            } else {
                Swal.fire({
                    icon: 'error',
                    title: 'Gagal',
                    text: data.message || 'Gagal menyimpan persetujuan.',
                    confirmButtonText: 'Coba Lagi'
                });
                btnAgree.removeAttribute('disabled');
                btnAgree.textContent = 'Setuju & Lanjutkan';
            }
        })
        .catch(err => {
            Swal.fire({
                icon: 'error',
                title: 'Error',
                text: 'Terjadi kesalahan jaringan.',
                confirmButtonText: 'Coba Lagi'
            });
            btnAgree.removeAttribute('disabled');
            btnAgree.textContent = 'Setuju & Lanjutkan';
        });
    };
}

function closeApplyTermsModal() {
    const modal = document.getElementById('apply-terms-modal');
    if (modal) {
        modal.classList.add('hidden');
        modal.classList.remove('flex');
    }
}
</script>
@endpush
@endsection
