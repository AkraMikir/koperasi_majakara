@extends('layouts.admin')

@section('title', 'Terima Gadai Baru')

@push('styles')
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <style>
        /* Custom Select2 Styling to match Koperasi Majakara aesthetics */
        .select2-container--default .select2-selection--single {
            background-color: rgba(255, 255, 255, 0.5) !important;
            border: 1px solid rgba(255, 255, 255, 0.6) !important;
            border-radius: 0.75rem !important;
            height: 3rem !important;
            display: flex !important;
            align-items: center !important;
            box-shadow: 0 1px 2px 0 rgba(0, 0, 0, 0.05) !important;
            backdrop-filter: blur(4px) !important;
            -webkit-backdrop-filter: blur(4px) !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #111827 !important;
            font-size: 0.875rem !important;
            font-weight: 500 !important;
            padding-left: 1rem !important;
            padding-right: 2.5rem !important;
        }
        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 3rem !important;
            right: 0.75rem !important;
            display: flex !important;
            align-items: center !important;
        }
        .select2-container--default .select2-selection--single:focus,
        .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #674c1d !important;
            background-color: #ffffff !important;
            box-shadow: 0 0 0 1px #674c1d !important;
        }
        .select2-dropdown {
            border-color: rgba(0, 0, 0, 0.08) !important;
            border-radius: 0.75rem !important;
            overflow: hidden !important;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05) !important;
            background-color: #ffffff !important;
            z-index: 9999 !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field {
            border: 1px solid #e5e7eb !important;
            border-radius: 0.5rem !important;
            padding: 0.5rem 0.75rem !important;
            outline: none !important;
        }
        .select2-container--default .select2-search--dropdown .select2-search__field:focus {
            border-color: #674c1d !important;
            box-shadow: 0 0 0 1px #674c1d !important;
        }
        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background-color: #674c1d !important;
            color: #ffffff !important;
        }
        .select2-container--default .select2-results__option {
            padding: 0.625rem 1rem !important;
            font-size: 0.875rem !important;
        }
        /* Custom inputs styling to match Select2 height and border */
        .grid input[type="text"]:not([name="foto_barang[]"]):not([name="foto_transaksi[]"]):not([name="foto_administrasi[]"]),
        .grid input[type="number"] {
            height: 3rem !important;
            padding-left: 1rem !important;
            padding-right: 1rem !important;
            border-radius: 0.75rem !important;
            font-size: 0.875rem !important;
        }
        .grid input#nominal_deal {
            padding-left: 2.5rem !important; /* Keep room for Rp icon */
        }
    </style>
@endpush

@push('scripts')
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
@endpush

@section('content')
    <div class="space-y-8 max-w-5xl mx-auto">

        {{-- ===== PAGE HEADER ===== --}}
        <div class="flex items-center justify-between">
            <div>
                <h1 class="text-3xl font-bold text-gray-900 font-display">Terima Gadai Baru</h1>
                <p class="text-gray-500 mt-1">Proses penyerahan barang fisik dan pencairan dana gadai</p>
            </div>
            <a href="{{ route('admin.gadai_baru.index') }}"
                class="flex items-center gap-2 px-4 py-2 bg-white/80 backdrop-blur-md text-gray-700 border border-white/60 font-medium rounded-xl hover:bg-white transition-colors shadow-sm">
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

        <div class="bg-white/80 backdrop-blur-xl border border-white/60 rounded-3xl shadow-xl overflow-hidden">
            <div class="p-8">
                <form action="{{ route('admin.gadai_baru.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-8"
                    onsubmit="let input = document.getElementById('nominal_deal'); if(input) { input.value = input.value.replace(/[^0-9]/g, ''); } document.getElementById('btnSubmit').disabled=true; document.getElementById('btnSubmit').innerHTML='Memproses...';">
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
                                <select name="nasabah_id" id="nasabah_id"
                                    class="w-full border-white/60 shadow-sm rounded-xl bg-white/50 backdrop-blur-sm focus:bg-white focus:ring-[#674c1d] focus:border-[#674c1d] select2"
                                    required>
                                    <option value="">Pilih Nasabah...</option>
                                    @foreach($nasabahs as $n)
                                        <option value="{{ $n->id }}" 
                                                data-bank="{{ $n->dataRek?->nama_bank ?? '' }}" 
                                                data-saldo="{{ $n->saldo_tabungan ?? 0 }}"
                                                data-nik="{{ $n->dataKtp?->nik ?? '-' }}"
                                                data-rekening="{{ $n->dataRek?->no_rekening ?? '-' }}">
                                            {{ $n->user->nama ?? 'Tanpa Nama' }} ({{ $n->user->nomor_hp ?? 'Tanpa HP' }})
                                        </option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Cabang Penyimpanan <span
                                        class="text-red-500">*</span></label>
                                <select name="lokasi_id" id="lokasi_id"
                                    class="w-full border-white/60 shadow-sm rounded-xl bg-white/50 backdrop-blur-sm focus:bg-white focus:ring-[#674c1d] focus:border-[#674c1d] select2"
                                    required>
                                    <option value="">Pilih Cabang...</option>
                                    @foreach($lokasiList as $l)
                                        <option value="{{ $l->id }}">{{ $l->nama_lokasi }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">NIK Nasabah</label>
                                <input type="text" id="nasabah_nik" 
                                    class="w-full border-white/60 shadow-sm rounded-xl bg-gray-50 backdrop-blur-sm text-gray-500 font-medium focus:ring-0 focus:border-white/60 cursor-not-allowed" 
                                    readonly value="-" placeholder="-">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">No. Rekening Nasabah</label>
                                <input type="text" id="nasabah_rekening" 
                                    class="w-full border-white/60 shadow-sm rounded-xl bg-gray-50 backdrop-blur-sm text-gray-500 font-medium focus:ring-0 focus:border-white/60 cursor-not-allowed" 
                                    readonly value="-" placeholder="-">
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
                                    class="w-full border-white/60 shadow-sm rounded-xl bg-white/50 backdrop-blur-sm focus:bg-white focus:ring-[#674c1d] focus:border-[#674c1d] select2"
                                    required>
                                    <option value="">Pilih Kategori</option>
                                    @foreach($kategoriList as $k)
                                        <option value="{{ $k->id }}" data-kode="{{ $k->kode_kategori }}" data-jasa="{{ $k->rate_jasa }}" data-inap="{{ $k->rate_inap_persen }}">{{ $k->nama_kategori }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Item Barang <span
                                        class="text-red-500">*</span></label>
                                <select name="item_id" id="item_id"
                                    class="w-full border-white/60 shadow-sm rounded-xl bg-white/50 backdrop-blur-sm focus:bg-white focus:ring-[#674c1d] focus:border-[#674c1d] select2"
                                    required>
                                    <option value="">Pilih Item</option>
                                    @foreach($itemList as $item)
                                        <option value="{{ $item->id }}" data-kategori="{{ $item->kategori_id }}"
                                            data-min="{{ $item->nominal_low }}"
                                            data-max="{{ $item->nominal_high }}" class="item-option hidden">{{ $item->head_1 }}
                                            @if($item->head_2)({{ $item->head_2 }})@endif</option>
                                    @endforeach
                                </select>
                                <p id="taksiran_info" class="text-xs text-amber-600 font-bold mt-2 hidden">Taksiran: Rp
                                    <span id="min_taksiran_text">0</span> - Rp <span id="max_taksiran_text">0</span></p>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Merk/Type</label>
                                <input type="text" name="nama_barang_manual" id="nama_barang_manual"
                                    class="w-full border-white/60 shadow-sm rounded-xl bg-white/50 backdrop-blur-sm focus:bg-white focus:ring-[#674c1d] focus:border-[#674c1d] font-bold text-gray-900 placeholder-gray-400"
                                    placeholder="Nama...">
                            </div> 

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Nominal Deal (Rp) <span
                                        class="text-red-500">*</span></label>
                                <div class="relative">
                                    <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                        <span class="text-gray-500 font-medium">Rp</span>
                                    </div>
                                    <input type="text" name="nominal_deal" id="nominal_deal"
                                        class="w-full pl-10 border-white/60 shadow-sm rounded-xl bg-white/50 backdrop-blur-sm focus:bg-white focus:ring-[#674c1d] focus:border-[#674c1d] font-bold text-gray-900"
                                        required placeholder="0" oninput="formatCurrency(this)">
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
                                <select name="metode_pencairan" id="metode_pencairan"
                                    class="w-full border-white/60 shadow-sm rounded-xl bg-white/50 backdrop-blur-sm focus:bg-white focus:ring-[#674c1d] focus:border-[#674c1d] select2"
                                    required>
                                    <option value="cash">Tunai (Cash)</option>
                                    <option value="transfer">Transfer Bank</option>
                                </select>
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Bunga Jasa (%) <span
                                        class="text-red-500">*</span></label>
                                <input type="number" step="0.01" name="rate_jasa" id="rate_jasa"
                                    class="w-full border-white/60 shadow-sm rounded-xl bg-white/50 backdrop-blur-sm focus:bg-white focus:ring-[#674c1d] focus:border-[#674c1d] font-bold text-gray-900"
                                    required placeholder="0.00">
                            </div>

                            <div>
                                <label id="label_rate_inap" class="block text-sm font-bold text-gray-700 mb-2">Bunga Inap (%) <span
                                        class="text-red-500">*</span></label>
                                <input type="number" step="0.01" name="rate_inap_persen" id="rate_inap_persen"
                                    class="w-full border-white/60 shadow-sm rounded-xl bg-white/50 backdrop-blur-sm focus:bg-white focus:ring-[#674c1d] focus:border-[#674c1d] font-bold text-gray-900"
                                    required placeholder="0.00">
                            </div>

                            <div>
                                <label class="block text-sm font-bold text-gray-700 mb-2">Slot Storage <span
                                        class="text-red-500">*</span></label>
                                <select name="slot_kode" id="slot_kode"
                                    class="w-full border-white/60 shadow-sm rounded-xl bg-white/50 backdrop-blur-sm focus:bg-white focus:ring-[#674c1d] focus:border-[#674c1d] select2"
                                    required>
                                    <option value="">Pilih Kategori Dulu</option>
                                </select>
                            </div>

                            <div id="col_no_mesin_rangka" class="hidden">
                                <label class="block text-sm font-bold text-gray-700 mb-2">No Mesin/Rangka</label>
                                <input type="text" name="no_mesin_rangka" id="no_mesin_rangka"
                                    class="w-full border-white/60 shadow-sm rounded-xl bg-white/50 backdrop-blur-sm focus:bg-white focus:ring-[#674c1d] focus:border-[#674c1d] text-gray-900"
                                    placeholder="Contoh: 1km2jh9nmd/awsdasd">
                            </div>

                            <div id="col_no_imei_sn" class="hidden">
                                <label class="block text-sm font-bold text-gray-700 mb-2">No IMEI/SN</label>
                                <input type="text" name="no_imei_sn" id="no_imei_sn"
                                    class="w-full border-white/60 shadow-sm rounded-xl bg-white/50 backdrop-blur-sm focus:bg-white focus:ring-[#674c1d] focus:border-[#674c1d] text-gray-900"
                                    placeholder="Contoh: 1km2jh9nmd">
                            </div>

                            <div id="col_kelengkapan" class="hidden">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Kelengkapan</label>
                                <input type="text" name="kelengkapan" id="kelengkapan"
                                    class="w-full border-white/60 shadow-sm rounded-xl bg-white/50 backdrop-blur-sm focus:bg-white focus:ring-[#674c1d] focus:border-[#674c1d] text-gray-900"
                                    placeholder="Contoh: Fullset, Charger, Dus">
                            </div>

                            <div id="col_catatan" class="hidden">
                                <label class="block text-sm font-bold text-gray-700 mb-2">Catatan</label>
                                <input type="text" name="catatan" id="catatan"
                                    class="w-full border-white/60 shadow-sm rounded-xl bg-white/50 backdrop-blur-sm focus:bg-white focus:ring-[#674c1d] focus:border-[#674c1d] text-gray-900"
                                    placeholder="Catatan tambahan barang gadai">
                            </div>
                        </div>
                    </div>

                    {{-- Bagian 3: Upload Bukti --}}
                    <div class="space-y-6">
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

                        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                            {{-- Foto Barang --}}
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-bold text-gray-700">Foto Barang / Kendaraan <span class="text-red-500">*</span></label>
                                    <button type="button" id="add_foto_barang_btn"
                                        class="flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-700 hover:bg-emerald-200 rounded-lg text-xs font-bold transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Tambah
                                    </button>
                                </div>
                                <div id="foto_barang_container" class="space-y-3">
                                    <div class="flex items-center gap-2 file-input-group">
                                        <input type="file" name="foto_barang[]" class="block w-full text-sm text-gray-500
                                            file:mr-2 file:py-1.5 file:px-3
                                            file:rounded-xl file:border-0
                                            file:text-xs file:font-semibold
                                            file:bg-emerald-50 file:text-emerald-700
                                            hover:file:bg-emerald-100 transition-colors
                                            border border-white/60 shadow-sm rounded-xl bg-white/50 p-1" required accept="image/*">
                                    </div>
                                </div>
                            </div>

                            {{-- Foto Transaksi --}}
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-bold text-gray-700">Foto Transaksi <span class="text-red-500">*</span></label>
                                    <button type="button" id="add_foto_transaksi_btn"
                                        class="flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-700 hover:bg-emerald-200 rounded-lg text-xs font-bold transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Tambah
                                    </button>
                                </div>
                                <div id="foto_transaksi_container" class="space-y-3">
                                    <div class="flex items-center gap-2 file-input-group">
                                        <input type="file" name="foto_transaksi[]" class="block w-full text-sm text-gray-500
                                            file:mr-2 file:py-1.5 file:px-3
                                            file:rounded-xl file:border-0
                                            file:text-xs file:font-semibold
                                            file:bg-emerald-50 file:text-emerald-700
                                            hover:file:bg-emerald-100 transition-colors
                                            border border-white/60 shadow-sm rounded-xl bg-white/50 p-1" required accept="image/*">
                                    </div>
                                </div>
                            </div>

                            {{-- Foto Administrasi --}}
                            <div class="space-y-3">
                                <div class="flex items-center justify-between">
                                    <label class="block text-sm font-bold text-gray-700">Foto Administrasi <span class="text-red-500">*</span></label>
                                    <button type="button" id="add_foto_administrasi_btn"
                                        class="flex items-center gap-1 px-2.5 py-1 bg-emerald-100 text-emerald-700 hover:bg-emerald-200 rounded-lg text-xs font-bold transition-colors">
                                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                        </svg>
                                        Tambah
                                    </button>
                                </div>
                                <div id="foto_administrasi_container" class="space-y-3">
                                    <div class="flex items-center gap-2 file-input-group">
                                        <input type="file" name="foto_administrasi[]" class="block w-full text-sm text-gray-500
                                            file:mr-2 file:py-1.5 file:px-3
                                            file:rounded-xl file:border-0
                                            file:text-xs file:font-semibold
                                            file:bg-emerald-50 file:text-emerald-700
                                            hover:file:bg-emerald-100 transition-colors
                                            border border-white/60 shadow-sm rounded-xl bg-white/50 p-1" required accept="image/*">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <p class="text-xs text-gray-500">Format yang didukung: JPG, JPEG, PNG. Maksimal 2MB per file. Harap pisahkan foto sesuai dengan masing-masing kategori di atas.</p>
                    </div>

                    <!-- Verification & Petty Cash Summary (Dynamic) -->
                    <div class="space-y-4">
                        <div class="flex items-center gap-2 pb-2 border-b border-gray-100">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 text-indigo-600 flex items-center justify-center">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path>
                                </svg>
                            </div>
                            <h3 class="text-lg font-bold text-gray-800">Verifikasi & Petty Cash</h3>
                        </div>

                        <!-- Petty Cash Card -->
                        <div class="p-4 rounded-xl border-2 border-[#674c1d] bg-[#674c1d]/5 transition-all">
                            <div class="flex justify-between items-center">
                                <div>
                                    <p class="text-[10px] uppercase tracking-wider text-gray-500 font-bold mb-1" id="petty-cash-label">
                                        Saldo Petty Cash (Cash) Anda</p>
                                    <p class="text-xl font-black text-[#674c1d]" id="petty-cash-value">Rp 0</p>
                                </div>
                                <div class="text-right">
                                    <div id="badge-petty-cash" class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-bold bg-green-100 text-green-700">
                                        <!-- Dynamic badge content via JS -->
                                    </div>
                                </div>
                            </div>
                        </div>

                        <!-- Warning Petty Cash -->
                        <div id="warningPetty" class="hidden p-3 bg-red-50 border border-red-200 rounded-xl">
                            <p class="text-xs text-red-600 font-semibold flex items-center">
                                <svg class="w-4 h-4 mr-1 shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                                </svg>
                                Saldo Petty Cash Anda tidak mencukupi untuk pencairan nominal deal ini!
                            </p>
                        </div>

                        <!-- Transfer Fee Info (Antarbank) -->
                        <div id="biaya-section" class="hidden p-4 bg-amber-50 border border-amber-200 rounded-xl">
                            <div class="flex items-center justify-between mb-2">
                                <p class="text-xs font-bold text-gray-500 uppercase">Biaya Transfer (dipotong dari tabungan):</p>
                                <p class="font-bold text-amber-700 text-sm" id="biaya-display">Rp 0</p>
                            </div>
                            <p class="text-[10px] text-amber-700">Biaya admin transfer antarbank ditanggung nasabah (dipotong dari saldo tabungan nasabah).</p>
                            <div id="warningTabungan" class="hidden mt-2 p-2 bg-red-100 rounded text-xs text-red-700 font-bold">
                                Saldo tabungan nasabah tidak cukup untuk membayar biaya transfer!
                            </div>
                        </div>
                    </div>

                    {{-- Info Box --}}
                    <div class="bg-gradient-to-r from-[#674c1d]/10 to-[#d4af37]/10 backdrop-blur-md border border-[#674c1d]/20 rounded-2xl p-5 flex items-start gap-4 shadow-sm">
                        <div class="w-10 h-10 rounded-full bg-white/80 flex items-center justify-center shrink-0 shadow-sm">
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
                            class="w-full flex items-center justify-center gap-2 py-4 px-8 bg-gradient-to-r from-[#674c1d] to-[#d4af37] hover:from-[#5a4118] hover:to-[#b3952f] text-white font-black rounded-xl shadow-xl shadow-[#674c1d]/20 transition-all hover:-translate-y-0.5 uppercase tracking-wide">
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
        function formatCurrency(input) {
            let value = input.value.replace(/[^0-9]/g, '');
            if (value) value = parseInt(value).toLocaleString('id-ID');
            input.value = value;
        }

        document.addEventListener('DOMContentLoaded', initGadaiForm);
        document.addEventListener('turbo:load', initGadaiForm);

        function initGadaiForm() {
            const katSelect = document.getElementById('kategori_id');
            if (!katSelect || katSelect.dataset.initialized) return;
            katSelect.dataset.initialized = 'true';

            const itemSelect = document.getElementById('item_id');
            const nominalInput = document.getElementById('nominal_deal');
            const errorNominal = document.getElementById('error_nominal');
            const btnSubmit = document.getElementById('btnSubmit');
            const taksiranInfo = document.getElementById('taksiranInfo');
            const maxTaksiranText = document.getElementById('max_taksiran_text');
            const tInfoBox = document.getElementById('taksiran_info');
            const slotSelect = document.getElementById('slot_kode');
            const namaBarangManual = document.getElementById('nama_barang_manual');

            const allItems = @json($itemList);
            const availableSlots = @json($availableSlots);

            function updateFormState() {
                const val = katSelect.value;
                const selectedOpt = katSelect.options[katSelect.selectedIndex];
                const kode = selectedOpt ? selectedOpt.dataset.kode : '';
                const jasa = selectedOpt ? (selectedOpt.dataset.jasa || '0.00') : '0.00';
                const inap = selectedOpt ? (selectedOpt.dataset.inap || '0.00') : '0.00';

                const rateJasaInput = document.getElementById('rate_jasa');
                if (rateJasaInput) rateJasaInput.value = jasa;
                
                const rateInapInput = document.getElementById('rate_inap_persen');
                const labelInap = document.getElementById('label_rate_inap');
                
                if (rateInapInput) {
                if (kode === 'vehicle') {
                    rateInapInput.readOnly = true;
                    rateInapInput.classList.add('bg-gray-100', 'cursor-not-allowed');
                    if (labelInap) {
                        labelInap.innerHTML = 'Biaya Inap (Nominal) <span class="text-red-500">*</span>';
                    }
                        
                        // Set the value based on the selected item's flat nominal inap fee
                        const selectedItemOpt = itemSelect.options[itemSelect.selectedIndex];
                        const itemInap = selectedItemOpt ? parseFloat(selectedItemOpt.dataset.inap) || 0 : 0;
                        rateInapInput.value = itemInap;
                } else {
                    rateInapInput.readOnly = false;
                    rateInapInput.classList.remove('bg-gray-100', 'cursor-not-allowed');
                    if (labelInap) {
                        labelInap.innerHTML = 'Bunga Inap (%) <span class="text-red-500">*</span>';
                    }
                        rateInapInput.value = inap;
                    }
                }

                // Update placeholder of nama_barang_manual
                if (namaBarangManual) {
                    if (kode === 'electronic') {
                        namaBarangManual.placeholder = 'Iphone, Macbook / 128 gb hitam';
                    } else if (kode === 'vehicle') {
                        namaBarangManual.placeholder = 'Beat / 125 Merah';
                    } else {
                        namaBarangManual.placeholder = 'Nama...';
                    }
                }
            }

            katSelect.addEventListener('change', function () {
                const val = this.value;
                const selectedOpt = this.options[this.selectedIndex];
                const kode = selectedOpt ? selectedOpt.dataset.kode : '';

                const currentItemVal = itemSelect.value;
                const currentSlotVal = slotSelect.value;
                
                itemSelect.innerHTML = '<option value="">Pilih Item</option>';
                slotSelect.innerHTML = '<option value="">Pilih Slot</option>';
                if (tInfoBox) tInfoBox.classList.add('hidden');
                $(itemSelect).trigger('change');
                $(slotSelect).trigger('change');
                tInfoBox.classList.add('hidden');

                // Dynamic field toggle
                document.getElementById('no_mesin_rangka').value = '';
                document.getElementById('no_imei_sn').value = '';
                document.getElementById('kelengkapan').value = '';
                document.getElementById('catatan').value = '';

                document.getElementById('col_no_mesin_rangka').classList.add('hidden');
                document.getElementById('col_no_imei_sn').classList.add('hidden');
                document.getElementById('col_kelengkapan').classList.add('hidden');
                document.getElementById('col_catatan').classList.add('hidden');

                if (kode === 'vehicle') {
                    document.getElementById('col_no_mesin_rangka').classList.remove('hidden');
                    document.getElementById('col_kelengkapan').classList.remove('hidden');
                    document.getElementById('col_catatan').classList.remove('hidden');
                } else if (kode === 'electronic') {
                    document.getElementById('col_no_imei_sn').classList.remove('hidden');
                    document.getElementById('col_kelengkapan').classList.remove('hidden');
                    document.getElementById('col_catatan').classList.remove('hidden');
                } else if (kode === 'gold') {
                    document.getElementById('col_kelengkapan').classList.remove('hidden');
                    document.getElementById('col_catatan').classList.remove('hidden');
                }

                if (val) {
                if (!val) {
                    $(itemSelect).trigger('change');
                    $(slotSelect).trigger('change');
                    return;
                }

                const filtered = allItems.filter(item => item.kategori_id == val);
                filtered.forEach(item => {
                    const opt = document.createElement('option');
                    opt.value = item.id;
                    opt.textContent = item.head_1 + (item.head_2 ? ` (${item.head_2})` : '');
                    opt.dataset.min = item.nominal_low;
                    opt.dataset.max = item.nominal_high;
                    opt.dataset.inap = item.nominal_inap || 0;
                    itemSelect.appendChild(opt);
                });
                }

                if (kode && availableSlots[kode]) {
                    const slots = availableSlots[kode];
                    slots.forEach(slot => {
                        const opt = document.createElement('option');
                        opt.value = slot.kode_slot;
                        opt.textContent = `${slot.kode_slot} (Baris ${slot.baris}, Kolom ${slot.kolom})`;
                        slotSelect.appendChild(opt);
                    });
                }

                if (currentItemVal) {
                    itemSelect.value = currentItemVal;
                    itemSelect.dispatchEvent(new Event('change'));
                }
                if (currentSlotVal) {
                    slotSelect.value = currentSlotVal;
                }

                updateFormState();
                
                $(itemSelect).trigger('change');
                $(slotSelect).trigger('change');
            });

            itemSelect.addEventListener('change', function () {
                if (!this.value) {
                    if (tInfoBox) tInfoBox.classList.add('hidden');
                    return;
                }
                const selectedOpt = this.options[this.selectedIndex];
                const min = parseFloat(selectedOpt.dataset.min) || 0;
                const max = parseFloat(selectedOpt.dataset.max) || 0;

                // Format to IDR
                document.getElementById('min_taksiran_text').textContent = new Intl.NumberFormat('id-ID').format(min);
                if (maxTaksiranText) maxTaksiranText.textContent = new Intl.NumberFormat('id-ID').format(max);
                if (tInfoBox) tInfoBox.classList.remove('hidden');

                updateFormState();

                // Trigger nominal validation if already filled
                if (nominalInput.value) {
                    nominalInput.dispatchEvent(new Event('keyup'));
                }
            });

            nominalInput.addEventListener('keyup', function () {
                if (!itemSelect.value) return;
                const selectedOpt = itemSelect.options[itemSelect.selectedIndex];
                const max = parseFloat(selectedOpt.dataset.max);
                const inputVal = parseFloat(this.value.replace(/[^0-9]/g, '')) || 0;

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
            function setupDynamicUpload(btnId, containerId, inputName) {
                const btn = document.getElementById(btnId);
                const container = document.getElementById(containerId);
                if (!btn || !container) return;

                btn.addEventListener('click', function () {
                    const row = document.createElement('div');
                    row.className = 'flex items-center gap-2 file-input-group';
                    row.innerHTML = `
                        <input type="file" name="${inputName}" class="block w-full text-sm text-gray-500
                            file:mr-2 file:py-1.5 file:px-3
                            file:rounded-xl file:border-0
                            file:text-xs file:font-semibold
                            file:bg-emerald-50 file:text-emerald-700
                            hover:file:bg-emerald-100 transition-colors
                            border border-white/60 shadow-sm rounded-xl bg-white/50 p-1" required accept="image/*">
                        <button type="button" class="w-11 h-11 flex items-center justify-center bg-red-50 text-red-500 rounded-xl border border-red-100 hover:bg-red-100 transition-colors remove-file-btn">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                        </button>
                    `;
                    container.appendChild(row);

                    row.querySelector('.remove-file-btn').addEventListener('click', function () {
                        row.remove();
                    });
                });
            }

            setupDynamicUpload('add_foto_barang_btn', 'foto_barang_container', 'foto_barang[]');
            setupDynamicUpload('add_foto_transaksi_btn', 'foto_transaksi_container', 'foto_transaksi[]');
            setupDynamicUpload('add_foto_administrasi_btn', 'foto_administrasi_container', 'foto_administrasi[]');

            // Balance and Transfer Fee checking logic
            const adminSaldoCash = {{ $adminSaldoCash ?? 0 }};
            const adminSaldoTransfer = {{ $adminSaldoTransfer ?? 0 }};
            const biayaTransferData = @json($biayaTransfer ?? []);

            const selectNasabah = document.getElementById('nasabah_id');
            const selectMetode = document.getElementById('metode_pencairan');
            
            function validateBalances() {
                const metode = selectMetode.value;
                const dealNominal = parseFloat(nominalInput.value.replace(/[^0-9]/g, '')) || 0;
                
                const selectedNasabahOpt = selectNasabah.options[selectNasabah.selectedIndex];
                const bankNasabah = selectedNasabahOpt ? selectedNasabahOpt.dataset.bank || '' : '';
                const saldoTabunganNasabah = selectedNasabahOpt ? parseFloat(selectedNasabahOpt.dataset.saldo) || 0 : 0;
                
                let adminSaldoAvailable = 0;
                let pettyLabel = '';
                
                if (metode === 'cash') {
                    adminSaldoAvailable = adminSaldoCash;
                    pettyLabel = 'Saldo Petty Cash (Cash) Anda';
                } else {
                    adminSaldoAvailable = adminSaldoTransfer;
                    pettyLabel = 'Saldo Petty Cash (Transfer) Anda';
                }
                
                const pLabel = document.getElementById('petty-cash-label');
                if (pLabel) pLabel.innerText = pettyLabel;
                const pVal = document.getElementById('petty-cash-value');
                if (pVal) pVal.innerText = 'Rp ' + adminSaldoAvailable.toLocaleString('id-ID');
                
                // Calculate Transfer Fee
                let biayaAdmin = 0;
                const biayaSection = document.getElementById('biaya-section');
                const biayaDisplay = document.getElementById('biaya-display');
                
                if (metode === 'transfer' && bankNasabah !== '' && bankNasabah.toUpperCase() !== 'BCA') {
                    const mapping = biayaTransferData.find(b => b.bank_pengirim === 'BCA' && b.bank_penerima === bankNasabah);
                    if (mapping) {
                        biayaAdmin = parseFloat(mapping.biaya_admin);
                    } else {
                        biayaAdmin = 6500; // default antarbank
                    }
                    
                    if (biayaSection) biayaSection.classList.remove('hidden');
                    if (biayaDisplay) biayaDisplay.innerText = 'Rp ' + biayaAdmin.toLocaleString('id-ID');
                } else {
                    if (biayaSection) biayaSection.classList.add('hidden');
                }
                
                const isInsufficientPetty = (adminSaldoAvailable < dealNominal);
                const isInsufficientTabungan = (metode === 'transfer' && saldoTabunganNasabah < biayaAdmin);
                
                const badgePetty = document.getElementById('badge-petty-cash');
                const warningPetty = document.getElementById('warningPetty');
                const warningTabungan = document.getElementById('warningTabungan');
                
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
                
                let isdealExceed = false;
                if (itemSelect.value) {
                    const selectedOpt = itemSelect.options[itemSelect.selectedIndex];
                    const max = parseFloat(selectedOpt.dataset.max);
                    if (dealNominal > max) {
                        isdealExceed = true;
                    }
                }

                if (isInsufficientPetty) {
                    if (warningPetty) warningPetty.classList.remove('hidden');
                    if (warningTabungan) warningTabungan.classList.add('hidden');
                    btnSubmit.disabled = true;
                    btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                } else if (isInsufficientTabungan) {
                    if (warningPetty) warningPetty.classList.add('hidden');
                    if (warningTabungan) warningTabungan.classList.remove('hidden');
                    btnSubmit.disabled = true;
                    btnSubmit.classList.add('opacity-50', 'cursor-not-allowed');
                } else {
                    if (warningPetty) warningPetty.classList.add('hidden');
                    if (warningTabungan) warningTabungan.classList.add('hidden');
                    if (!isdealExceed) {
                        btnSubmit.disabled = false;
                        btnSubmit.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                }
            }

            // Bind change events
            selectNasabah.addEventListener('change', function() {
                validateBalances();
                const selectedOpt = this.options[this.selectedIndex];
                if (selectedOpt) {
                    document.getElementById('nasabah_nik').value = selectedOpt.dataset.nik || '-';
                    document.getElementById('nasabah_rekening').value = selectedOpt.dataset.rekening || '-';
                }
            });
            selectMetode.addEventListener('change', validateBalances);
            nominalInput.addEventListener('input', validateBalances);
            nominalInput.addEventListener('keyup', validateBalances);
            
            // For Select2 integration (it triggers 'change' event on jquery)
            $(document).ready(function() {
                // Initialize selects with search enabled
                $('#nasabah_id').select2({
                    placeholder: 'Pilih Nasabah...',
                    allowClear: true,
                    width: '100%'
                });
                
                $('#item_id').select2({
                    placeholder: 'Pilih Item...',
                    width: '100%'
                });

                $('#slot_kode').select2({
                    placeholder: 'Pilih Slot...',
                    width: '100%'
                });

                // Initialize selects with search disabled (for simple selects)
                $('#lokasi_id, #kategori_id, #metode_pencairan').select2({
                    minimumResultsForSearch: Infinity,
                    width: '100%'
                });

                // Event listener specifically for nasabah select change
                $('#nasabah_id').on('change', function() {
                    validateBalances();
                    const selectedOpt = $(this).find(':selected');
                    if (selectedOpt.length) {
                        $('#nasabah_nik').val(selectedOpt.data('nik') || '-');
                        $('#nasabah_rekening').val(selectedOpt.data('rekening') || '-');
                    }
                });

                // Trigger initial populate for nasabah
                const selectedOpt = $('#nasabah_id').find(':selected');
                if (selectedOpt.length && selectedOpt.val()) {
                    $('#nasabah_nik').val(selectedOpt.data('nik') || '-');
                    $('#nasabah_rekening').val(selectedOpt.data('rekening') || '-');
                }
            });
            
            // Initial run
            validateBalances();
            katSelect.dispatchEvent(new Event('change'));
        }
    </script>
@endsection