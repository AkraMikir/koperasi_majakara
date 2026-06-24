@extends('layouts.admin')

@section('title', 'Catat Pembayaran Pajak - Koperasi Majakara')

@section('content')
<div class="space-y-6">

    {{-- Header --}}
    <div class="flex items-center space-x-4 bg-white p-6 rounded-2xl border border-gray-100 shadow-xs">
        <a href="{{ route('admin.bunga.pajak.index') }}" class="p-2 text-gray-400 hover:text-gray-600 hover:bg-gray-50 rounded-xl transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"/>
            </svg>
        </a>
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Catat Pembayaran Pajak</h1>
            <p class="text-sm text-gray-500 mt-1">Tambah catatan PPh baru dari petty cash ke negara</p>
        </div>
    </div>

    {{-- Info AJAX --}}
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start gap-3">
        <svg class="w-5 h-5 text-amber-500 mt-0.5 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        <div>
            <p class="text-sm font-semibold text-amber-700">Hitung Otomatis</p>
            <p class="text-xs text-amber-600 mt-0.5">Pilih jenis pajak dan periode, lalu klik <strong>Hitung Otomatis</strong> untuk mengisi nilai berdasarkan data realisasi aktual.</p>
        </div>
    </div>

    {{-- Form --}}
    <div class="bg-white rounded-2xl border border-gray-100 shadow-xs overflow-hidden">
        <form method="POST" action="{{ route('admin.bunga.pajak.store') }}" enctype="multipart/form-data" id="formPajak"
              class="p-6 md:p-8 space-y-6">
            @csrf

            {{-- Baris 1: Jenis Pajak + Periode --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="md:col-span-1">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Jenis Pajak <span class="text-red-500">*</span></label>
                    <select name="jenis_pajak" id="jenisPajak" required
                        class="w-full rounded-xl border-gray-300 focus:border-[#8b6f2f] focus:ring focus:ring-[#8b6f2f]/20 transition-all @error('jenis_pajak') border-red-300 @enderror">
                        <option value="">-- Pilih Jenis --</option>
                        <option value="pph_pinjaman" {{ old('jenis_pajak') === 'pph_pinjaman' ? 'selected' : '' }}>PPh Pinjaman (15%)</option>
                        <option value="pph_gadai"    {{ old('jenis_pajak') === 'pph_gadai'    ? 'selected' : '' }}>PPh Gadai (15%)</option>
                        <option value="pph_deposito" {{ old('jenis_pajak') === 'pph_deposito' ? 'selected' : '' }}>PPh Deposito (20%)</option>
                    </select>
                    @error('jenis_pajak')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Bulan <span class="text-red-500">*</span></label>
                    <select name="periode_bulan" id="periodeBulan" required
                        class="w-full rounded-xl border-gray-300 focus:border-[#8b6f2f] focus:ring focus:ring-[#8b6f2f]/20 transition-all">
                        @for($m = 1; $m <= 12; $m++)
                        <option value="{{ $m }}" {{ (old('periode_bulan', $bulan) == $m) ? 'selected' : '' }}>
                            {{ \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}
                        </option>
                        @endfor
                    </select>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Tahun <span class="text-red-500">*</span></label>
                    <select name="periode_tahun" id="periodeTahun" required
                        class="w-full rounded-xl border-gray-300 focus:border-[#8b6f2f] focus:ring focus:ring-[#8b6f2f]/20 transition-all">
                        @for($y = now()->year; $y >= 2023; $y--)
                        <option value="{{ $y }}" {{ old('periode_tahun', $tahun) == $y ? 'selected' : '' }}>{{ $y }}</option>
                        @endfor
                    </select>
                </div>
            </div>

            {{-- Tombol Hitung --}}
            <div class="flex items-center gap-4">
                <button type="button" id="btnHitung"
                    class="inline-flex items-center gap-2 px-5 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white text-sm font-medium rounded-xl hover:shadow-lg hover:shadow-[#674c1d]/20 transition-all duration-300">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 7h6m0 10v-3m-3 3h.01M9 17h.01M9 11h.01M12 11h.01M15 11h.01M4 19h16a2 2 0 002-2V7a2 2 0 00-2-2H4a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span id="btnHitungText">Hitung Otomatis</span>
                </button>
                <p id="hitungInfo" class="text-xs text-green-600 font-medium hidden"></p>
            </div>

            <div class="border-t border-gray-100"></div>

            {{-- Baris 2: Nilai Perhitungan --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Nilai Perhitungan</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                    <div class="md:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            Jumlah Kotor / Basis (Rp) <span class="text-red-500">*</span>
                        </label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-500 font-medium text-sm">Rp</div>
                            <input type="text" id="jumlahKotorDisplay"
                                class="w-full rounded-xl border-gray-300 focus:border-[#8b6f2f] focus:ring focus:ring-[#8b6f2f]/20 transition-all pl-12"
                                required>
                            <input type="hidden" name="jumlah_kotor" id="jumlahKotor" value="{{ old('jumlah_kotor', 0) }}">
                        </div>
                        @error('jumlah_kotor')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tarif PPh (%)</label>
                        <div class="relative">
                            <input type="number" name="tarif_persen" id="tarifPersen" step="0.01"
                                value="{{ old('tarif_persen', 0) }}"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 focus:ring-0 transition-all pr-10"
                                readonly>
                            <div class="absolute inset-y-0 right-0 flex items-center pr-4 pointer-events-none text-gray-500 font-medium text-sm">%</div>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Jumlah Pajak (Rp)</label>
                        <div class="relative">
                            <div class="absolute inset-y-0 left-0 flex items-center pl-4 pointer-events-none text-gray-500 font-medium text-sm">Rp</div>
                            <input type="text" id="jumlahPajakDisplay"
                                class="w-full rounded-xl border-gray-300 bg-gray-50 focus:ring-0 pl-12 font-semibold text-red-600"
                                readonly>
                            <input type="hidden" name="jumlah_pajak" id="jumlahPajak" value="{{ old('jumlah_pajak', 0) }}">
                        </div>
                    </div>
                </div>
                {{-- Jumlah Bersih --}}
                <div class="mt-4 p-4 bg-green-50 border border-green-200 rounded-xl flex items-center justify-between">
                    <div>
                        <p class="text-xs font-semibold text-green-700 uppercase tracking-wider">Jumlah Bersih (Kotor − Pajak)</p>
                        <p class="text-xs text-green-600 mt-0.5">Pendapatan bersih yang menjadi hak koperasi</p>
                    </div>
                    <div class="text-right">
                        <p class="text-2xl font-bold text-green-700" id="jumlahBersihDisplay">Rp 0</p>
                        <input type="hidden" name="jumlah_bersih" id="jumlahBersih" value="{{ old('jumlah_bersih', 0) }}">
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100"></div>

            {{-- Baris 3: Status, Tanggal --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Informasi Pembayaran</h3>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Status <span class="text-red-500">*</span></label>
                        <select name="status" required
                            class="w-full rounded-xl border-gray-300 focus:border-[#8b6f2f] focus:ring focus:ring-[#8b6f2f]/20 transition-all">
                            <option value="belum_bayar" {{ old('status', 'belum_bayar') === 'belum_bayar' ? 'selected' : '' }}>Belum Dibayar</option>
                            <option value="sudah_bayar" {{ old('status') === 'sudah_bayar' ? 'selected' : '' }}>Sudah Dibayar</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tanggal Bayar</label>
                        <input type="date" name="tanggal_bayar"
                            value="{{ old('tanggal_bayar') }}"
                            class="w-full rounded-xl border-gray-300 focus:border-[#8b6f2f] focus:ring focus:ring-[#8b6f2f]/20 transition-all">
                    </div>
                    <div class="md:col-span-2">
                        <label class="block text-sm font-medium text-gray-700 mb-2">Keterangan</label>
                        <textarea name="keterangan" rows="3"
                            placeholder="Catatan tambahan tentang pembayaran ini..."
                            class="w-full rounded-xl border-gray-300 focus:border-[#8b6f2f] focus:ring focus:ring-[#8b6f2f]/20 transition-all placeholder-gray-400 resize-none">{{ old('keterangan') }}</textarea>
                    </div>
                </div>
            </div>

            <div class="border-t border-gray-100"></div>

            {{-- Bukti Bayar --}}
            <div>
                <h3 class="text-sm font-semibold text-gray-700 mb-4">Bukti Pembayaran</h3>
                <div class="border-2 border-dashed border-gray-200 rounded-xl p-8 text-center hover:border-[#8b6f2f]/50 transition-colors cursor-pointer"
                     onclick="document.getElementById('buktiBayar').click()">
                    <svg class="w-10 h-10 text-gray-300 mx-auto mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                    </svg>
                    <p class="text-sm font-medium text-gray-500" id="fileLabel">Klik untuk upload foto atau PDF bukti setor</p>
                    <p class="text-xs text-gray-400 mt-1">JPG, PNG, PDF — Maks 5MB</p>
                </div>
                <input type="file" name="bukti_bayar" id="buktiBayar" accept=".jpg,.jpeg,.png,.pdf" class="hidden"
                    onchange="document.getElementById('fileLabel').textContent = this.files[0]?.name ?? 'Klik untuk upload'">
                @error('bukti_bayar')<p class="mt-1.5 text-sm text-red-600">{{ $message }}</p>@enderror
            </div>

            {{-- Actions --}}
            <div class="pt-2 border-t border-gray-100 flex justify-end gap-3">
                <a href="{{ route('admin.bunga.pajak.index') }}"
                   class="px-5 py-2.5 bg-gray-50 text-gray-700 font-medium rounded-xl border border-gray-200 hover:bg-gray-100 transition-colors">
                    Batal
                </a>
                <button type="submit"
                    class="px-5 py-2.5 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white font-medium rounded-xl hover:shadow-lg hover:shadow-[#674c1d]/20 transition-all duration-300">
                    Simpan Catatan
                </button>
            </div>
        </form>
    </div>

</div>
@endsection

@push('scripts')
<script>
const TARIF_MAP = { pph_pinjaman: 15, pph_gadai: 15, pph_deposito: 20 };

function parseFormattedNumber(val) {
    if (!val) return 0;
    val = val.trim();
    if (val.includes('.') && val.includes(',')) {
        if (val.lastIndexOf(',') > val.lastIndexOf('.')) {
            val = val.replace(/\./g, '').replace(/,/g, '.');
        } else {
            val = val.replace(/,/g, '');
        }
    } else if (val.includes(',')) {
        val = val.replace(/,/g, '.');
    } else if (val.includes('.')) {
        const parts = val.split('.');
        if (parts.length === 2 && parts[1].length === 3) {
            val = val.replace(/\./g, '');
        } else if (parts.length > 2) {
            val = val.replace(/\./g, '');
        }
    }
    val = val.replace(/[^0-9.-]/g, '');
    const num = parseFloat(val);
    return isNaN(num) ? 0 : num;
}

function formatRupiahNoPrefix(num) {
    if (num === '' || num === undefined || num === null) return '';
    num = parseFloat(num);
    if (isNaN(num)) return '';
    const hasDecimal = num % 1 !== 0;
    return num.toLocaleString('id-ID', {
        minimumFractionDigits: hasDecimal ? 2 : 0,
        maximumFractionDigits: 2
    });
}

function updateDisplaysAndCalculate() {
    const kotor = parseFloat(document.getElementById('jumlahKotor').value) || 0;
    const tarif = parseFloat(document.getElementById('tarifPersen').value) || 0;
    const pajak = kotor * tarif / 100;
    const bersih = kotor - pajak;

    document.getElementById('jumlahPajak').value = pajak.toFixed(2);
    document.getElementById('jumlahPajakDisplay').value = formatRupiahNoPrefix(pajak);

    document.getElementById('jumlahBersih').value = bersih.toFixed(2);
    document.getElementById('jumlahBersihDisplay').textContent = 'Rp ' + formatRupiahNoPrefix(bersih);
}

// Event listeners for kotor display
const kotorDisplayInput = document.getElementById('jumlahKotorDisplay');
kotorDisplayInput.addEventListener('input', function() {
    const val = parseFormattedNumber(this.value);
    document.getElementById('jumlahKotor').value = val;
    updateDisplaysAndCalculate();
});

kotorDisplayInput.addEventListener('blur', function() {
    const val = parseFloat(document.getElementById('jumlahKotor').value) || 0;
    this.value = formatRupiahNoPrefix(val);
});

document.getElementById('jenisPajak').addEventListener('change', function () {
    const tarif = TARIF_MAP[this.value] || 0;
    document.getElementById('tarifPersen').value = tarif;
    updateDisplaysAndCalculate();
});

document.getElementById('btnHitung').addEventListener('click', async function () {
    const jenis = document.getElementById('jenisPajak').value;
    const bulan = document.getElementById('periodeBulan').value;
    const tahun = document.getElementById('periodeTahun').value;

    if (!jenis) { alert('Pilih jenis pajak terlebih dahulu.'); return; }

    const btn  = this;
    const text = document.getElementById('btnHitungText');
    btn.disabled = true;
    text.textContent = 'Menghitung...';

    try {
        const url = `{{ route('admin.bunga.pajak.hitung') }}?jenis=${jenis}&bulan=${bulan}&tahun=${tahun}`;
        const res  = await fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } });
        const data = await res.json();

        document.getElementById('jumlahKotor').value = data.jumlah_kotor;
        document.getElementById('jumlahKotorDisplay').value = formatRupiahNoPrefix(data.jumlah_kotor);

        document.getElementById('tarifPersen').value = data.tarif_persen;

        document.getElementById('jumlahPajak').value = data.jumlah_pajak;
        document.getElementById('jumlahPajakDisplay').value = formatRupiahNoPrefix(data.jumlah_pajak);

        document.getElementById('jumlahBersih').value = data.jumlah_bersih;
        document.getElementById('jumlahBersihDisplay').textContent = 'Rp ' + formatRupiahNoPrefix(data.jumlah_bersih);

        const info = document.getElementById('hitungInfo');
        info.textContent = `Berhasil dihitung dari realisasi ${bulan}/${tahun}. Kotor: Rp ${formatRupiahNoPrefix(data.jumlah_kotor)}`;
        info.classList.remove('hidden');
    } catch (e) {
        alert('Gagal menghitung otomatis. Silakan isi manual.');
    } finally {
        btn.disabled = false;
        text.textContent = 'Hitung Otomatis';
    }
});

// Init display on load
(function init() {
    const initialKotor = parseFloat(document.getElementById('jumlahKotor').value) || 0;
    kotorDisplayInput.value = formatRupiahNoPrefix(initialKotor);
    updateDisplaysAndCalculate();
})();
</script>
@endpush
