@extends('layouts.admin')

@section('title', 'Settings Struk & Gadai')

@section('content')
<div class="max-w-7xl mx-auto space-y-6" x-data="{
    tab: 'tabungan',
    // Header properties for live preview
    nama_koperasi: {{ json_encode($settings->nama_koperasi) }},
    alamat_koperasi: {{ json_encode($settings->alamat_koperasi) }},
    no_telp: {{ json_encode($settings->no_telp) }},
    nama_pt: {{ json_encode($settings->nama_pt) }},
    format_no_struk: {{ json_encode($settings->format_no_struk) }},
    email: {{ json_encode($settings->email) }},
    website: {{ json_encode($settings->website) }},
    syarat_ketentuan_gadai: {{ json_encode($settings->syarat_ketentuan_gadai) }},
    syarat_ketentuan_pinjaman: {{ json_encode($settings->syarat_ketentuan_pinjaman) }},
    info_box_pinjaman: {{ json_encode($settings->info_box_pinjaman) }},
    bunga_admin_gadai: '1.2',
    
    // Preview properties
    tabungan_jenis: 'NABUNG',
    tabungan_nominal: 500000,
    tabungan_saldo_sebelum: 1500000,
    
    pinjaman_jenis: 'PENCAIRAN',
    pinjaman_jumlah: 5000000,
    pinjaman_tenor: 12,
    pinjaman_bunga: 12,
    
    deposito_jenis: 'PENCAIRAN SESUDAH TEMPO',
    deposito_nominal: 10000000,
    deposito_tenor: 6,
    deposito_bunga: 5,
    
    gadai_jenis: 'AKTIF',
    gadai_nominal: 15000000,
    gadai_inap: 150000,
    
    // HTML cache
    tabunganHtml: '',
    pinjamanHtml: '',
    depositoHtml: '',
    gadaiHtml: '',
    
    isLoading: false,
    
    init() {
        this.updateTabungan();
        this.updatePinjaman();
        this.updateDeposito();
        this.updateGadai();
    },
    
    updateTabungan() {
        this.isLoading = true;
        fetch('{{ route('admin.settings.struk.preview-tabungan') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                nama_koperasi: this.nama_koperasi,
                alamat_koperasi: this.alamat_koperasi,
                no_telp: this.no_telp,
                nama_pt: this.nama_pt,
                format_no_struk: this.format_no_struk,
                email: this.email,
                website: this.website,
                jenis_trans: this.tabungan_jenis,
                nominal: this.tabungan_nominal,
                saldo_sebelum: this.tabungan_saldo_sebelum
            })
        })
        .then(res => res.text())
        .then(html => {
            this.tabunganHtml = html;
            this.isLoading = false;
        });
    },
    
    updatePinjaman() {
        this.isLoading = true;
        fetch('{{ route('admin.settings.struk.preview-pinjaman') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                nama_koperasi: this.nama_koperasi,
                alamat_koperasi: this.alamat_koperasi,
                no_telp: this.no_telp,
                nama_pt: this.nama_pt,
                format_no_struk: this.format_no_struk,
                email: this.email,
                website: this.website,
                jenis_trans: this.pinjaman_jenis,
                jumlah_pinjam: this.pinjaman_jumlah,
                lama_pinjam: this.pinjaman_tenor,
                bunga: this.pinjaman_bunga,
                syarat_ketentuan_pinjaman: this.syarat_ketentuan_pinjaman,
                info_box_pinjaman: this.info_box_pinjaman
            })
        })
        .then(res => res.text())
        .then(html => {
            this.pinjamanHtml = html;
            this.isLoading = false;
        });
    },
    
    updateDeposito() {
        this.isLoading = true;
        fetch('{{ route('admin.settings.struk.preview-deposito') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                nama_koperasi: this.nama_koperasi,
                alamat_koperasi: this.alamat_koperasi,
                no_telp: this.no_telp,
                nama_pt: this.nama_pt,
                format_no_struk: this.format_no_struk,
                email: this.email,
                website: this.website,
                jenis_trans: this.deposito_jenis,
                nominal_awal: this.deposito_nominal,
                jangka_waktu: this.deposito_tenor,
                bunga: this.deposito_bunga
            })
        })
        .then(res => res.text())
        .then(html => {
            this.depositoHtml = html;
            this.isLoading = false;
        });
    },
    
    updateGadai() {
        this.isLoading = true;
        fetch('{{ route('admin.settings.struk.preview-gadai') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}'
            },
            body: JSON.stringify({
                nama_koperasi: this.nama_koperasi,
                alamat_koperasi: this.alamat_koperasi,
                no_telp: this.no_telp,
                nama_pt: this.nama_pt,
                format_no_struk: this.format_no_struk,
                email: this.email,
                website: this.website,
                syarat_ketentuan_gadai: this.syarat_ketentuan_gadai,
                bunga_admin_gadai: this.bunga_admin_gadai,
                jenis_trans: this.gadai_jenis,
                nominal_deal: this.gadai_nominal,
                biaya_inap: this.gadai_inap
            })
        })
        .then(res => res.text())
        .then(html => {
            this.gadaiHtml = html;
            this.isLoading = false;
        });
    }
}">
    <!-- Header Page -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 md:p-8 relative overflow-hidden">
        <div class="absolute top-0 right-0 w-64 h-64 bg-gradient-to-br from-[#674c1d]/10 to-transparent rounded-full -translate-y-1/2 translate-x-1/3 blur-2xl"></div>
        <div class="relative z-10">
            <h1 class="text-2xl md:text-3xl font-bold text-gray-900 font-display">Pengaturan Struk & Parameter Koperasi</h1>
            <p class="text-gray-500 mt-2">Atur kop surat, header struk, syarat ketentuan gadai, serta parameter nominal koperasi Anda secara sentral.</p>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-lg flex items-start shadow-sm">
        <svg class="w-5 h-5 text-green-500 mt-0.5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-green-800 text-sm font-medium">{{ session('success') }}</p>
    </div>
    @endif

    @if($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-lg shadow-sm">
        <div class="flex items-start">
            <svg class="w-5 h-5 text-red-500 mt-0.5 mr-3 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
            <div>
                <p class="text-red-800 text-sm font-medium">Terdapat kesalahan pada input Anda:</p>
                <ul class="mt-1 text-sm text-red-700 list-disc list-inside">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        </div>
    </div>
    @endif

    <!-- Layout Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 items-start">
        
        <!-- Left Column: Forms -->
        <div class="lg:col-span-7 space-y-6">
            
            <!-- Header Settings Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center space-x-3 bg-gray-50/50">
                    <div class="p-2.5 bg-amber-50 text-[#674c1d] rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Header & Identitas Koperasi</h2>
                        <p class="text-xs text-gray-500 font-sans">Akan tampil di bagian atas cetakan struk thermal</p>
                    </div>
                </div>
                <div class="p-6">
                    <div @input="nama_koperasi = $event.target.form.nama_koperasi.value;
                                 alamat_koperasi = $event.target.form.alamat_koperasi.value;
                                 no_telp = $event.target.form.no_telp.value;
                                 nama_pt = $event.target.form.nama_pt.value;
                                 format_no_struk = $event.target.form.format_no_struk.value;
                                 email = $event.target.form.email.value;
                                 website = $event.target.form.website.value;
                                 updateTabungan(); updatePinjaman(); updateDeposito(); updateGadai();">
                        @include('admin.settings.partials.header-settings')
                    </div>
                </div>
            </div>

            <!-- Syarat & Ketentuan Gadai Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center space-x-3 bg-gray-50/50">
                    <div class="p-2.5 bg-amber-50 text-[#674c1d] rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Syarat & Ketentuan Gadai</h2>
                        <p class="text-xs text-gray-500 font-sans">Dicetak di bagian bawah (footer) khusus lembar struk gadai</p>
                    </div>
                </div>
                <div class="p-6">
                    <div @input="syarat_ketentuan_gadai = $event.target.form.syarat_ketentuan_gadai.value; updateGadai();">
                        @include('admin.settings.partials.syarat-ketentuan')
                    </div>
                </div>
            </div>

            <!-- Settings Gadai Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center space-x-3 bg-gray-50/50">
                    <div class="p-2.5 bg-amber-50 text-[#674c1d] rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6V4m0 2a2 2 0 100 4m0-4a2 2 0 110 4m-6 8a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4m6 6v10m6-2a2 2 0 100-4m0 4a2 2 0 110-4m0 4v2m0-6V4"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Parameter Nominal & Bunga Gadai</h2>
                        <p class="text-xs text-gray-500 font-sans">Atur variabel operasional hitungan gadai koperasi</p>
                    </div>
                </div>
                <div class="p-6">
                    <div @input="updateGadai();">
                        @include('admin.settings.partials.settings-gadai')
                    </div>
                </div>
            </div>

            <!-- Syarat & Ketentuan Pinjaman Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center space-x-3 bg-gray-50/50">
                    <div class="p-2.5 bg-amber-50 text-[#674c1d] rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Syarat & Ketentuan Pinjaman</h2>
                        <p class="text-xs text-gray-500 font-sans">Dicetak di bagian tengah khusus lembar struk pinjaman B5</p>
                    </div>
                </div>
                <div class="p-6">
                    <div @input="syarat_ketentuan_pinjaman = $event.target.form.syarat_ketentuan_pinjaman.value; updatePinjaman();">
                        @include('admin.settings.partials.syarat-ketentuan-pinjaman')
                    </div>
                </div>
            </div>

            <!-- Info Box Pinjaman Card -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center space-x-3 bg-gray-50/50">
                    <div class="p-2.5 bg-amber-50 text-[#674c1d] rounded-xl">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                    </div>
                    <div>
                        <h2 class="text-lg font-bold text-gray-900">Info Box Pinjaman</h2>
                        <p class="text-xs text-gray-500 font-sans">Kotak informasi jam operasional dan ketentuan angsuran (kiri bawah B5)</p>
                    </div>
                </div>
                <div class="p-6">
                    <div @input="info_box_pinjaman = $event.target.form.info_box_pinjaman.value; updatePinjaman();">
                        @include('admin.settings.partials.info-box-pinjaman')
                    </div>
                </div>
            </div>

        </div>

        <!-- Right Column: Interactive Thermal Preview -->
        <div class="lg:col-span-5 lg:sticky lg:top-6">
            
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden flex flex-col min-h-[500px]">
                
                <!-- Preview Header / Tab Selectors -->
                <div class="p-4 bg-gray-50 border-b border-gray-100">
                    <div class="flex items-center justify-between mb-3">
                        <span class="text-sm font-bold text-gray-900 font-display">Live Preview Struk</span>
                        <div class="flex items-center space-x-1" x-show="isLoading">
                            <svg class="animate-spin h-4 w-4 text-[#674c1d]" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                            <span class="text-[10px] text-gray-400">updating...</span>
                        </div>
                    </div>
                    
                    <!-- Tabs -->
                    <div class="grid grid-cols-4 gap-1 bg-gray-200/60 p-1 rounded-xl">
                        <button type="button" @click="tab = 'tabungan'" :class="tab === 'tabungan' ? 'bg-white text-[#674c1d] shadow-xs font-semibold' : 'text-gray-600 hover:text-gray-900'" class="py-2 text-[10px] rounded-lg text-center transition-all duration-200">
                            Tabungan
                        </button>
                        <button type="button" @click="tab = 'pinjaman'" :class="tab === 'pinjaman' ? 'bg-white text-[#674c1d] shadow-xs font-semibold' : 'text-gray-600 hover:text-gray-900'" class="py-2 text-[10px] rounded-lg text-center transition-all duration-200">
                            Pinjaman
                        </button>
                        <button type="button" @click="tab = 'deposito'" :class="tab === 'deposito' ? 'bg-white text-[#674c1d] shadow-xs font-semibold' : 'text-gray-600 hover:text-gray-900'" class="py-2 text-[10px] rounded-lg text-center transition-all duration-200">
                            Deposito
                        </button>
                        <button type="button" @click="tab = 'gadai'" :class="tab === 'gadai' ? 'bg-white text-[#674c1d] shadow-xs font-semibold' : 'text-gray-600 hover:text-gray-900'" class="py-2 text-[10px] rounded-lg text-center transition-all duration-200">
                            Gadai
                        </button>
                    </div>
                </div>

                <!-- Preview Area -->
                <div class="p-6 flex-1 flex flex-col justify-between bg-[#fdfdfb]">
                    
                    <!-- Dynamic view templates -->
                    <div class="flex-1 flex items-center justify-center min-h-[350px]">
                        <!-- Tabungan -->
                        <div x-show="tab === 'tabungan'" class="w-full" x-html="tabunganHtml"></div>
                        
                        <!-- Pinjaman -->
                        <div x-show="tab === 'pinjaman'" class="w-full" x-html="pinjamanHtml"></div>
                        
                        <!-- Deposito -->
                        <div x-show="tab === 'deposito'" class="w-full" x-html="depositoHtml"></div>
                        
                        <!-- Gadai -->
                        <div x-show="tab === 'gadai'" class="w-full" x-html="gadaiHtml"></div>
                    </div>

                    <!-- Receipt Customizer Panel (Bottom of Preview) -->
                    <div class="mt-6 pt-4 border-t border-gray-200/80 bg-gray-50/50 -mx-6 -mb-6 p-6 rounded-b-2xl">
                        <span class="text-xs font-bold text-gray-500 uppercase tracking-wider block mb-3">Simulasi Nilai Struk</span>
                        
                        <!-- Tabungan Customizer Controls -->
                        <div x-show="tab === 'tabungan'" class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">JENIS TRANSAKSI</label>
                                <select x-model="tabungan_jenis" @change="updateTabungan()" class="w-full text-xs rounded-lg border-gray-300 py-1">
                                    <option value="NABUNG">Setoran (Nabung)</option>
                                    <option value="TARIK">Penarikan (Narik)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">NOMINAL (Rp)</label>
                                <input type="number" x-model.number="tabungan_nominal" @input.debounce.300ms="updateTabungan()" class="w-full text-xs rounded-lg border-gray-300 py-1">
                            </div>
                        </div>

                        <!-- Pinjaman Customizer Controls -->
                        <div x-show="tab === 'pinjaman'" class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">JENIS TRANSAKSI</label>
                                <select x-model="pinjaman_jenis" @change="updatePinjaman()" class="w-full text-xs rounded-lg border-gray-300 py-1">
                                    <option value="PENCAIRAN">Pencairan Baru</option>
                                    <option value="ANGSURAN">Pembayaran Angsuran</option>
                                    <option value="LUNAS">Pelunasan</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">JUMLAH PINJAM (Rp)</label>
                                <input type="number" x-model.number="pinjaman_jumlah" @input.debounce.300ms="updatePinjaman()" class="w-full text-xs rounded-lg border-gray-300 py-1">
                            </div>
                        </div>

                        <!-- Deposito Customizer Controls -->
                        <div x-show="tab === 'deposito'" class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">JENIS TRANSAKSI</label>
                                <select x-model="deposito_jenis" @change="updateDeposito()" class="w-full text-xs rounded-lg border-gray-300 py-1">
                                    <option value="PENGAJUAN">Penempatan Baru</option>
                                    <option value="PENCAIRAN SEBELUM TEMPO">Pencairan Sebelum Tempo (Batal)</option>
                                    <option value="PENCAIRAN SESUDAH TEMPO">Pencairan Setelah Tempo (JT)</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">NOMINAL (Rp)</label>
                                <input type="number" x-model.number="deposito_nominal" @input.debounce.300ms="updateDeposito()" class="w-full text-xs rounded-lg border-gray-300 py-1">
                            </div>
                        </div>

                        <!-- Gadai Customizer Controls -->
                        <div x-show="tab === 'gadai'" class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">JENIS TRANSAKSI</label>
                                <select x-model="gadai_jenis" @change="updateGadai()" class="w-full text-xs rounded-lg border-gray-300 py-1">
                                    <option value="AKTIF">Penerimaan Gadai</option>
                                    <option value="PERPANJANGAN">Perpanjangan Tenor</option>
                                    <option value="PELUNASAN">Pelunasan/Tebus</option>
                                </select>
                            </div>
                            <div>
                                <label class="block text-[10px] font-bold text-gray-500 mb-1">NOMINAL DEAL (Rp)</label>
                                <input type="number" x-model.number="gadai_nominal" @input.debounce.300ms="updateGadai()" class="w-full text-xs rounded-lg border-gray-300 py-1">
                            </div>
                        </div>

                    </div>
                    
                </div>
            </div>
            
        </div>
        
    </div>
</div>
@endsection
