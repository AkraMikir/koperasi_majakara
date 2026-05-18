@extends('layouts.admin')

@section('title', 'Terima Gadai Baru')

@section('content')
    <div class="space-y-8 max-w-5xl mx-auto">

        {{-- ===== PAGE HEADER ===== --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 font-display">Terima Gadai Baru</h1>
                <p class="text-gray-500 mt-1">Proses penyerahan barang fisik dan pencairan dana gadai</p>
            </div>
            <a href="{{ route('admin.gadai_baru.index') }}"
                class="flex items-center gap-2 px-4 py-2 bg-white text-gray-700 border border-gray-200 font-medium rounded-xl hover:bg-gray-50 transition-colors shadow-sm">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18">
                    </path>
                </svg>
                Kembali
            </a>
        </div>

        @if(session('error'))
            <div class="p-4 bg-red-50 border-l-4 border-red-500 rounded-r-xl shadow-sm flex items-start gap-3">
                <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <div>
                    <h3 class="text-sm font-bold text-red-800">Terjadi Kesalahan</h3>
                    <p class="text-sm text-red-700 mt-1">{{ session('error') }}</p>
                </div>
            </div>
        @endif

        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
            <div class="p-8">
                <form action="{{ route('admin.gadai_baru.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-8"
                    onsubmit="document.getElementById('btnSubmit').disabled=true; document.getElementById('btnSubmit').innerHTML='Memproses...';">
                    @csrf

                    {{-- Bagian 1: Data Nasabah & Lokasi --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                            <div class="w-8 h-8 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Informasi Nasabah & Lokasi</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nasabah Pemohon <span
                                        class="text-red-500">*</span></label>
                                <select name="nasabah_id"
                                    class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d] select2"
                                    required>
                                    <option value="">Pilih Nasabah...</option>
                                    @foreach($nasabahs as $n)
                                        <option value="{{ $n->id }}">{{ $n->user->nama ?? 'Tanpa Nama' }}
                                            ({{ $n->user->nomor_hp ?? 'Tanpa HP' }})</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Cabang Penyimpanan <span
                                        class="text-red-500">*</span></label>
                                <select name="lokasi_id"
                                    class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d]"
                                    required>
                                    <option value="">Pilih Cabang...</option>
                                    @foreach($lokasiList as $l)
                                        <option value="{{ $l->id }}">{{ $l->nama_lokasi }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Bagian 2: Detail Barang Gadai --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                            <div class="w-8 h-8 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M20 7l-8-4-8 4m16 0l-8 4m8-4v10l-8 4m0-10L4 7m8 4v10M4 7v10l8 4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Detail Barang Gadai</h3>
                        </div>

                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Kategori <span
                                        class="text-red-500">*</span></label>
                                <select name="kategori_id" id="kategori_id"
                                    class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d]"
                                    required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoriList as $k)
                                        <option value="{{ $k->id }}" data-kode="{{ $k->kode_kategori }}">{{ $k->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Item Barang <span
                                        class="text-red-500">*</span></label>
                                <select name="item_id" id="item_id"
                                    class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d]"
                                    required>
                                    <option value="">Pilih Item</option>
                                    @foreach($itemList as $item)
                                        <option value="{{ $item->id }}" data-kategori="{{ $item->kategori_id }}"
                                            data-max="{{ $item->nominal_high }}" class="item-option hidden">{{ $item->head_1 }}
                                            @if($item->head_2)({{ $item->head_2 }})@endif</option>
                                    @endforeach
                                </select>
                                <p id="taksiran_info" class="text-xs text-amber-600 font-bold mt-2 hidden">Max Taksiran: Rp
                                    <span id="max_taksiran_text">0</span></p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nominal Deal (Rp) <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 font-medium">Rp</span>
                                    </div>
                                    <input type="number" name="nominal_deal" id="nominal_deal"
                                        class="w-full pl-10 border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d] font-bold text-gray-900"
                                        required placeholder="0">
                                </div>
                                <p id="error_nominal"
                                    class="text-xs text-red-500 font-bold mt-2 hidden flex items-center gap-1">
                                    <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                    </svg>
                                    Melebihi batas maksimal taksiran!
                                </p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Metode Pencairan <span
                                        class="text-red-500">*</span></label>
                                <select name="metode_pencairan"
                                    class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d]"
                                    required>
                                    <option value="cash">Tunai (Cash)</option>
                                    <option value="transfer">Transfer Bank</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Slot Storage <span
                                        class="text-red-500">*</span></label>
                                <select name="slot_kode" id="slot_kode"
                                    class="w-full border-gray-200 rounded-xl bg-gray-50 focus:ring-[#674c1d] focus:border-[#674c1d]"
                                    required>
                                    <option value="">Pilih Kategori Dulu</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    {{-- Bagian 3: Upload Bukti --}}
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                            <div class="w-8 h-8 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                    </path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Bukti Foto</h3>
                        </div>

                        <div>
                            <div class="flex items-center justify-between mb-2">
                                <label class="block text-sm font-bold text-gray-700">Upload Foto Bukti <span
                                        class="text-red-500">*</span></label>
                                <button type="button" id="add_file_btn"
                                    class="flex items-center gap-1 px-3 py-1.5 bg-emerald-100 text-emerald-700 hover:bg-emerald-200 rounded-lg text-xs font-bold transition-colors">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M12 4v16m8-8H4"></path>
                                    </svg>
                                    Tambah File
                                </button>
                            </div>

                            <div id="file_upload_container" class="space-y-3">
                                <div class="flex items-center gap-2 file-input-group">
                                    <input type="file" name="foto_bukti[]" class="block w-full text-sm text-gray-500
                                        file:mr-4 file:py-2.5 file:px-4
                                        file:rounded-xl file:border-0
                                        file:text-sm file:font-semibold
                                        file:bg-emerald-50 file:text-emerald-700
                                        hover:file:bg-emerald-100 transition-colors
                                        border border-gray-200 rounded-xl bg-gray-50 p-1" required accept="image/*">
                                    <button type="button"
                                        class="w-11 h-11 flex items-center justify-center bg-red-50 text-red-500 rounded-xl border border-red-100 hover:bg-red-100 transition-colors cursor-not-allowed opacity-50"
                                        disabled>
                                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16">
                                            </path>
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <p class="text-xs text-gray-500 mt-2">Format yang didukung: JPG, JPEG, PNG. Maksimal 2MB per
                                file. Harap fotokan barang dari berbagai sisi beserta dokumen kelengkapannya.</p>
                        </div>
                    </div>

                    {{-- Info Box --}}
                    <div class="bg-[#8b6f2f]/10 border border-[#8b6f2f]/20 rounded-xl p-5 flex items-start gap-4">
                        <div class="w-10 h-10 rounded-full bg-white flex items-center justify-center shrink-0 shadow-sm">
                            <svg class="w-6 h-6 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                        </div>
                        <div>
                            <h4 class="font-bold text-gray-900">Perhatian Sebelum Submit</h4>
                            <p class="text-sm text-gray-600 mt-1 leading-relaxed">
                                Setelah menekan tombol simpan, dana <strong>otomatis akan memotong Saldo Cash Petty
                                    Cash</strong> Anda dan dicatat sebagai pengeluaran kas. Sistem juga akan mencari
                                <strong>Slot Kosong Terbawah</strong> secara otomatis di tabel grid penyimpanan sesuai
                                kategori barang.
                            </p>
                        </div>
                    </div>

                    {{-- Submit Button --}}
                    <div class="pt-2">
                        <button type="submit" id="btnSubmit"
                            class="w-full flex items-center justify-center gap-2 py-4 px-8 bg-gradient-to-r from-emerald-600 to-emerald-500 hover:from-emerald-700 hover:to-emerald-600 text-white font-bold rounded-xl shadow-lg shadow-emerald-500/30 transition-all hover:-translate-y-0.5">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                            </svg>
                            Simpan & Cairkan Gadai
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const katSelect = document.getElementById('kategori_id');
            const itemSelect = document.getElementById('item_id');
            const nominalInput = document.getElementById('nominal_deal');
            const errorNominal = document.getElementById('error_nominal');
            const btnSubmit = document.getElementById('btnSubmit');
            const taksiranInfo = document.getElementById('taksiranInfo');
            const maxTaksiranText = document.getElementById('max_taksiran_text');
            const tInfoBox = document.getElementById('taksiran_info');

            const allItems = @json($itemList);
            const availableSlots = @json($availableSlots);
            const slotSelect = document.getElementById('slot_kode');

            katSelect.addEventListener('change', function () {
                const val = this.value;
                const selectedOpt = this.options[this.selectedIndex];
                const kode = selectedOpt.dataset.kode;
                
                itemSelect.innerHTML = '<option value="">Pilih Item</option>';
                slotSelect.innerHTML = '<option value="">Pilih Slot</option>';
                tInfoBox.classList.add('hidden');

                if (!val) return;

                const filtered = allItems.filter(item => item.kategori_id == val);
                filtered.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.head_1 + (item.head_2 ? ` (${item.head_2})` : '');
                    opt.dataset.max = item.nominal_high;
                    itemSelect.appendChild(opt);
                });

                if (kode && availableSlots[kode]) {
                    const slots = availableSlots[kode];
                    slots.forEach(slot => {
                        const opt = document.createElement('option');
                        opt.value = slot.kode_slot;
                        opt.textContent = `${slot.kode_slot} (Baris ${slot.baris}, Kolom ${slot.kolom})`;
                        slotSelect.appendChild(opt);
                    });
                }
            });

            itemSelect.addEventListener('change', function () {
                if (!this.value) {
                    tInfoBox.classList.add('hidden');
                    return;
                }
                const selectedOpt = this.options[this.selectedIndex];
                const max = parseFloat(selectedOpt.dataset.max);

                // Format to IDR
                maxTaksiranText.textContent = new Intl.NumberFormat('id-ID').format(max);
                tInfoBox.classList.remove('hidden');

                // Trigger nominal validation if already filled
                if (nominalInput.value) {
                    nominalInput.dispatchEvent(new Event('keyup'));
                }
            });

            nominalInput.addEventListener('keyup', function () {
                if (!itemSelect.value) return;
                const selectedOpt = itemSelect.options[itemSelect.selectedIndex];
                const max = parseFloat(selectedOpt.dataset.max);
                const inputVal = parseFloat(this.value);

                if (inputVal > max) {
                    errorNominal.classList.remove('hidden');
                    nominalInput.classList.add('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                    btnSubmit.disabled = true;
                    btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    errorNominal.classList.add('hidden');
                    nominalInput.classList.remove('border-red-500', 'focus:ring-red-500', 'focus:border-red-500');
                    btnSubmit.disabled = false;
                    btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                }
            });

            // Dynamic File Upload Logic
            const addFileBtn = document.getElementById('add_file_btn');
            const container = document.getElementById('file_upload_container');

            addFileBtn.addEventListener('click', function () {
                const row = document.createElement('div');
                row.className = 'flex items-center gap-2 file-input-group';
                row.innerHTML = `
                <input type="file" name="foto_bukti[]" class="block w-full text-sm text-gray-500
                    file:mr-4 file:py-2.5 file:px-4
                    file:rounded-xl file:border-0
                    file:text-sm file:font-semibold
                    file:bg-emerald-50 file:text-emerald-700
                    hover:file:bg-emerald-100 transition-colors
                    border border-gray-200 rounded-xl bg-gray-50 p-1" required accept="image/*">
                <button type="button" class="w-11 h-11 flex items-center justify-center bg-red-50 text-red-500 rounded-xl border border-red-100 hover:bg-red-100 transition-colors remove-file-btn">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            `;
                container.appendChild(row);

                // Attach event to remove button
                row.querySelector('.remove-file-btn').addEventListener('click', function () {
                    row.remove();
                });
            });
        });
    </script>
@endsection