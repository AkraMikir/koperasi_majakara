@extends('layouts.admin')

@section('title', 'Edit Master Item Gadai')

@section('content')
    <div class="max-w-4xl mx-auto">
        {{-- Header Section --}}
        <div class="flex items-center justify-between gap-4 mb-8">
            <div>
                <h2 class="text-3xl font-black text-gray-900 tracking-tight font-display">Edit Item Gadai</h2>
                <p class="text-sm text-gray-500 mt-1">Perbarui informasi untuk: <span
                        class="text-[#674c1d] font-bold">{{ $data->nama_item }}</span></p>
            </div>
            <a href="{{ route('admin.master-data.item-gadai.index') }}"
                class="px-5 py-2.5 bg-white border border-gray-200 text-gray-600 font-bold rounded-xl hover:bg-gray-50 transition-all flex items-center gap-2 shadow-sm">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali
            </a>
        </div>

        @if($errors->any())
            <div class="mb-6 p-4 bg-red-50 border border-red-100 rounded-2xl text-red-800 animate-fade-in">
                <ul class="list-disc list-inside text-sm font-bold">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <div class="bg-white rounded-3xl shadow-xl shadow-gray-200/50 border border-gray-100 overflow-hidden">
            <form action="{{ route('admin.master-data.item-gadai.update', $data->id) }}" method="POST"
                enctype="multipart/form-data" class="p-8">
                @csrf
                @method('PUT')

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
                    <div class="md:col-span-2">
                        <label for="kategori_id"
                            class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Kategori Barang
                            <span class="text-red-500">*</span></label>
                        <select name="kategori_id" id="kategori_id"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all font-bold text-gray-700"
                            required>
                            <option value="">-- Pilih Kategori --</option>
                            @foreach($kategoris as $kat)
                                <option value="{{ $kat->id }}" data-kode="{{ $kat->kode_kategori }}"
                                    data-jasa="{{ $kat->rate_jasa }}" data-denda="{{ $kat->rate_denda }}"
                                    data-inap-persen="{{ $kat->rate_inap_persen }}" {{ (old('kategori_id') ?? $data->kategori_id) == $kat->id ? 'selected' : '' }}>{{ $kat->nama_kategori }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="md:col-span-2 bg-[#674c1d]/5 p-5 rounded-2xl border border-[#674c1d]/10">
                        <div class="flex items-center gap-2 mb-3">
                            <svg class="w-4 h-4 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            <h4 class="text-xs font-black text-[#674c1d] uppercase tracking-widest">Rujukan Aturan Kategori
                                (Otomatis dari Master)</h4>
                        </div>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-4 text-xs font-bold text-gray-700">
                            <div class="bg-white px-4 py-3 rounded-xl border border-gray-100 shadow-sm">
                                <span class="block text-[9px] uppercase tracking-wider text-gray-400 mb-1">Bunga Jasa /
                                    Admin</span>
                                <span id="ref_jasa" class="text-sm font-black text-blue-600">-</span>
                            </div>
                            <div class="bg-white px-4 py-3 rounded-xl border border-gray-100 shadow-sm">
                                <span class="block text-[9px] uppercase tracking-wider text-gray-400 mb-1">Bunga Denda
                                    Telat</span>
                                <span id="ref_denda" class="text-sm font-black text-red-600">-</span>
                            </div>
                            <div class="bg-white px-4 py-3 rounded-xl border border-gray-100 shadow-sm">
                                <span class="block text-[9px] uppercase tracking-wider text-gray-400 mb-1">Kebijakan Biaya
                                    Inap</span>
                                <span id="ref_inap" class="text-sm font-black text-amber-600">-</span>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="head_1"
                            class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Deskripsi
                            Utama<span class="text-red-500">*</span></label>
                        <input type="text" name="head_1" id="head_1"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all font-bold text-gray-700"
                            value="{{ old('head_1') ?? $data->head_1 }}" required>
                    </div>

                    <div>
                        <label for="head_2"
                            class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Deskripsi
                            Sekunder</label>
                        <input type="text" name="head_2" id="head_2"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all font-bold text-gray-700"
                            value="{{ old('head_2') ?? $data->head_2 }}">
                    </div>

                    <div>
                        <label for="nominal_real"
                            class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Harga Real
                            (OLX/Pasar) <span class="text-red-500">*</span></label>
                        <input type="number" name="nominal_real" id="nominal_real"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all font-bold text-gray-700"
                            value="{{ old('nominal_real') ?? $data->nominal_real }}" min="0" required>
                    </div>

                    <div>
                        <label for="is_active"
                            class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Status <span
                                class="text-red-500">*</span></label>
                        <select name="is_active" id="is_active"
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] focus:border-transparent transition-all font-bold text-gray-700"
                            required>
                            <option value="1" {{ (old('is_active', $data->is_active)) == 1 ? 'selected' : '' }}>Aktif</option>
                            <option value="0" {{ (old('is_active', $data->is_active)) == 0 ? 'selected' : '' }}>Non-Aktif
                            </option>
                        </select>
                    </div>

                    <div
                        class="bg-emerald-50/50 p-4 rounded-2xl border border-emerald-100 md:col-span-2 grid grid-cols-2 gap-4">
                        <div class="col-span-2 text-[10px] font-black text-emerald-800 uppercase tracking-widest mb-2">
                            Konfigurasi Taksiran & Jasa</div>

                        <div>
                            <label for="nominal_low"
                                class="block text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-1">Nominal
                                Taksir Min (Auto % Min)</label>
                            <input type="number" name="nominal_low" id="nominal_low"
                                class="w-full px-3 py-2 bg-gray-100 border border-emerald-100 rounded-lg focus:ring-2 focus:ring-emerald-500 transition-all font-bold text-gray-500 text-sm"
                                value="{{ old('nominal_low') ?? $data->nominal_low }}" readonly>
                        </div>
                        <div>
                            <label for="bunga_low"
                                class="block text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-1">Bunga/Jasa
                                Min (%) <span class="text-red-500">*</span></label>
                            <input type="number" name="bunga_low" id="bunga_low" step="0.01"
                                class="w-full px-3 py-2 bg-white border border-emerald-100 rounded-lg focus:ring-2 focus:ring-emerald-500 transition-all font-bold text-gray-700 text-sm"
                                value="{{ old('bunga_low') ?? $data->bunga_low }}" min="0" required
                                placeholder="Contoh: 40">
                        </div>

                        <div>
                            <label for="nominal_high"
                                class="block text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-1">Nominal
                                Taksir Max (Auto % Max)</label>
                            <input type="number" name="nominal_high" id="nominal_high"
                                class="w-full px-3 py-2 bg-gray-100 border border-emerald-100 rounded-lg focus:ring-2 focus:ring-emerald-500 transition-all font-bold text-gray-500 text-sm"
                                value="{{ old('nominal_high') ?? $data->nominal_high }}" readonly>
                        </div>
                        <div>
                            <label for="bunga_high"
                                class="block text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-1">Bunga/Jasa
                                Max (%) <span class="text-red-500">*</span></label>
                            <input type="number" name="bunga_high" id="bunga_high" step="0.01"
                                class="w-full px-3 py-2 bg-white border border-emerald-100 rounded-lg focus:ring-2 focus:ring-emerald-500 transition-all font-bold text-gray-700 text-sm"
                                value="{{ old('bunga_high') ?? $data->bunga_high }}" min="0" required
                                placeholder="Contoh: 60">
                        </div>

                        <div class="col-span-2 pt-2 border-t border-emerald-100 mt-2">
                            <div id="inap_preset_container" class="hidden mb-4">
                                <label for="inap_preset"
                                    class="block text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-1">Preset
                                    Biaya Inap Kendaraan</label>
                                <select id="inap_preset"
                                    class="w-full px-3 py-2 bg-white border border-emerald-100 rounded-lg focus:ring-2 focus:ring-emerald-500 transition-all font-bold text-gray-700 text-sm">
                                    <option value="">-- Ambil dari Master Data Inap Kendaraan --</option>
                                    @foreach($inapKendaraans as $preset)
                                        <option value="{{ (int) $preset->nominal_inap }}"
                                            data-keterangan="{{ is_array($preset->keterangan) ? json_encode($preset->keterangan) : $preset->keterangan }}" {{ (int) $data->nominal_inap == (int) $preset->nominal_inap ? 'selected' : '' }}>Golongan
                                            {{ $preset->golongan }} - {{ $preset->jenis_kendaraan }} (Rp
                                            {{ number_format($preset->nominal_inap, 0, ',', '.') }})
                                        </option>
                                    @endforeach
                                </select>
                                <p id="inap_preset_desc"
                                    class="text-[9px] text-amber-600 mt-1 italic {{ $data->nominal_inap > 0 ? '' : 'hidden' }}">
                                </p>
                            </div>

                            <label for="nominal_inap"
                                class="block text-[9px] font-black text-emerald-600 uppercase tracking-widest mb-1">Biaya
                                Inap (Nominal Rupiah - Khusus Kendaraan)</label>
                            <div class="relative">
                                <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                    <span class="text-emerald-500 font-bold text-xs">Rp</span>
                                </div>
                                <input type="number" name="nominal_inap" id="nominal_inap"
                                    class="w-full pl-8 px-3 py-2 bg-white border border-emerald-100 rounded-lg focus:ring-2 focus:ring-emerald-500 transition-all font-bold text-gray-700 text-sm"
                                    value="{{ old('nominal_inap', $data->nominal_inap) }}" min="0">
                            </div>
                            <p class="text-[9px] text-emerald-400 mt-1 italic">Isi jika kategori adalah Kendaraan
                                (Motor/Mobil). Untuk Emas/Elektronik biasanya menggunakan % dari Kategori.</p>
                        </div>
                    </div>

                    <div class="md:col-span-2">
                        <label for="file_pic"
                            class="block text-xs font-black text-gray-400 uppercase tracking-widest mb-2">Foto Item
                            Master</label>
                        <div class="flex items-center gap-4 mb-3">
                            @if($data->file_pic)
                                <div class="w-20 h-20 rounded-xl overflow-hidden border border-gray-200 shadow-sm">
                                    <img src="{{ asset('storage/' . $data->file_pic) }}" class="w-full h-full object-cover">
                                </div>
                                <div>
                                    <p class="text-[10px] font-black text-gray-400 uppercase">Foto Saat Ini</p>
                                    <p class="text-[9px] text-gray-400">Unggah baru untuk mengganti</p>
                                </div>
                            @endif
                        </div>
                        <input type="file" name="file_pic" id="file_pic"
                            class="w-full px-4 py-2.5 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d] transition-all text-sm file:mr-4 file:py-1 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-black file:bg-amber-100 file:text-amber-700 hover:file:bg-amber-200">
                    </div>
                </div>

                <div class="flex justify-end items-center gap-3 pt-6 border-t border-gray-100">
                    <button type="submit"
                        class="px-8 py-3 bg-[#674c1d] text-white font-black rounded-2xl hover:bg-[#8b6f2f] transition-all shadow-lg shadow-amber-900/20">
                        Update Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const nominalRealInput = document.getElementById('nominal_real');
            const bungaLowInput = document.getElementById('bunga_low');
            const bungaHighInput = document.getElementById('bunga_high');
            const nominalLowInput = document.getElementById('nominal_low');
            const nominalHighInput = document.getElementById('nominal_high');

            function calculateNominal() {
                const real = parseFloat(nominalRealInput.value) || 0;
                const bLow = parseFloat(bungaLowInput.value) || 0;
                const bHigh = parseFloat(bungaHighInput.value) || 0;

                nominalLowInput.value = Math.round(real * (bLow / 100));
                nominalHighInput.value = Math.round(real * (bHigh / 100));
            }

            nominalRealInput.addEventListener('input', calculateNominal);
            bungaLowInput.addEventListener('input', calculateNominal);
            bungaHighInput.addEventListener('input', calculateNominal);

            // Initial calculation
            calculateNominal();

            // Inap Preset logic
            const kategoriSelect = document.getElementById('kategori_id');
            const inapPresetContainer = document.getElementById('inap_preset_container');
            const inapPresetSelect = document.getElementById('inap_preset');
            const inapPresetDesc = document.getElementById('inap_preset_desc');
            const nominalInapInput = document.getElementById('nominal_inap');

            const refJasa = document.getElementById('ref_jasa');
            const refDenda = document.getElementById('ref_denda');
            const refInap = document.getElementById('ref_inap');

            function updateCategoryReferences() {
                const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
                if (!selectedOption || selectedOption.value === "") {
                    refJasa.textContent = "-";
                    refDenda.textContent = "-";
                    refInap.textContent = "-";
                    return;
                }

                const kode = selectedOption.getAttribute('data-kode');
                const jasa = selectedOption.getAttribute('data-jasa');
                const denda = selectedOption.getAttribute('data-denda');
                const inapPersen = selectedOption.getAttribute('data-inap-persen');

                refJasa.textContent = parseFloat(jasa).toFixed(2) + "%";
                refDenda.textContent = parseFloat(denda).toFixed(2) + "%";

                if (kode === 'vehicle') {
                    const selectedPreset = inapPresetSelect.options[inapPresetSelect.selectedIndex];
                    if (selectedPreset && selectedPreset.value !== "") {
                        const price = parseFloat(selectedPreset.value);
                        refInap.textContent = "Rp " + new Intl.NumberFormat('id-ID').format(price);
                    } else {
                        const nominalVal = parseFloat(nominalInapInput.value) || 0;
                        refInap.textContent = "Rp " + new Intl.NumberFormat('id-ID').format(nominalVal);
                    }
                } else {
                    refInap.textContent = parseFloat(inapPersen).toFixed(2) + "% dari Taksiran";
                }
            }

            function toggleInapPreset() {
                const selectedOption = kategoriSelect.options[kategoriSelect.selectedIndex];
                const kode = selectedOption ? selectedOption.getAttribute('data-kode') : '';

                if (kode === 'vehicle') {
                    inapPresetContainer.classList.remove('hidden');
                } else {
                    inapPresetContainer.classList.add('hidden');
                }
            }

            kategoriSelect.addEventListener('change', function () {
                toggleInapPreset();
                updateCategoryReferences();
            });

            inapPresetSelect.addEventListener('change', function () {
                const selectedOpt = inapPresetSelect.options[inapPresetSelect.selectedIndex];
                if (selectedOpt && selectedOpt.value !== "") {
                    nominalInapInput.value = selectedOpt.value;
                    const desc = selectedOpt.getAttribute('data-keterangan');
                    if (desc) {
                        inapPresetDesc.textContent = "Keterangan preset: " + desc;
                        inapPresetDesc.classList.remove('hidden');
                    } else {
                        inapPresetDesc.classList.add('hidden');
                    }
                } else {
                    inapPresetDesc.classList.add('hidden');
                }
                updateCategoryReferences();
            });

            nominalInapInput.addEventListener('input', updateCategoryReferences);

            // Run initial check
            toggleInapPreset();
            updateCategoryReferences();

            // Trigger desc check if preset is preselected
            const selectedOpt = inapPresetSelect.options[inapPresetSelect.selectedIndex];
            if (selectedOpt && selectedOpt.value !== "") {
                const desc = selectedOpt.getAttribute('data-keterangan');
                if (desc) {
                    inapPresetDesc.textContent = "Keterangan preset: " + desc;
                    inapPresetDesc.classList.remove('hidden');
                }
            }
        });
    </script>
@endsection