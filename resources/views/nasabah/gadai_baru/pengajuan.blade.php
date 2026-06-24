@extends('layouts.nasabah')

@section('title', 'Pengajuan ' . ucfirst($jenis))

@section('content')
<div class="max-w-2xl mx-auto pb-12 mt-6 px-4">
    <!-- Breadcrumb or Back Button -->
    <div class="mb-4">
        <a href="{{ route('nasabah.gadai_baru.index') }}" class="inline-flex items-center text-sm font-medium text-gray-500 hover:text-[#674c1d] transition-colors">
            <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path></svg>
            Kembali
        </a>
    </div>

    <div class="bg-white rounded-3xl shadow-xl border border-gray-100 overflow-hidden">
        <!-- Premium Header Banner -->
        <div class="bg-gradient-to-r from-[#674c1d] to-[#d4af37] px-6 py-6 flex items-center justify-between flex-wrap gap-2 relative overflow-hidden">
            <!-- Decorative background elements -->
            <div class="absolute top-0 right-0 -mr-8 -mt-8 w-32 h-32 rounded-full bg-white opacity-10 blur-2xl"></div>
            <div class="absolute bottom-0 left-0 -ml-8 -mb-8 w-24 h-24 rounded-full bg-black opacity-10 blur-xl"></div>
            
            <div class="flex items-center gap-4 relative z-10">
                <div class="w-12 h-12 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-md border border-white/30 shadow-inner">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                    </svg>
                </div>
                <div>
                    <h2 class="text-xl font-bold text-white tracking-wide">Form Pengajuan {{ ucfirst($jenis) }}</h2>
                    <p class="text-white/80 text-sm font-medium mt-0.5">Lengkapi data untuk memproses pengajuan</p>
                </div>
            </div>
            <span class="relative z-10 bg-white/20 text-white text-xs font-semibold px-3 py-1.5 rounded-full backdrop-blur-md border border-white/30 shadow-sm">{{ $gadai->kategori->nama_kategori }}</span>
        </div>

        <div class="p-6 sm:p-8">
            <!-- Info Ringkasan -->
            <div class="mb-8 relative rounded-2xl overflow-hidden shadow-sm border border-amber-100/60">
                <div class="absolute inset-0 bg-gradient-to-br from-amber-50 to-white opacity-90 z-0"></div>
                <div class="relative z-10 p-5 sm:p-6">
                    <div class="flex flex-col sm:flex-row justify-between items-start gap-4 mb-4">
                        <div>
                            <h3 class="font-bold text-amber-900 text-lg">{{ $gadai->item->nama_item }}</h3>
                            <div class="flex items-center gap-2 mt-1 text-sm text-amber-700/80">
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4"></path></svg>
                                <span>Slot: {{ $gadai->slot_kode }}</span>
                            </div>
                        </div>
                        <div class="text-left sm:text-right bg-white/60 p-3 rounded-xl border border-amber-100 backdrop-blur-sm shadow-sm w-full sm:w-auto">
                            <p class="text-[11px] text-amber-600/80 font-bold uppercase tracking-widest mb-0.5">Total Pembayaran</p>
                            <p class="text-2xl font-black text-[#674c1d]">Rp {{ number_format($nominal, 0, ',', '.') }}</p>
                        </div>
                    </div>

                    <div class="text-sm text-amber-800 space-y-2.5 pt-4 border-t border-amber-200/50">
                        <div class="flex justify-between items-center group">
                            <span class="font-medium text-amber-800/80">Pokok Pinjaman</span>
                            <span class="font-bold text-amber-950 bg-amber-100/50 px-2 py-0.5 rounded transition-colors group-hover:bg-amber-100">Rp {{ number_format($gadai->nominal_deal, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between items-center group">
                            <div class="flex flex-col">
                                <span class="font-medium text-amber-800/80">Biaya Jasa Admin</span>
                                <span class="text-[10px] text-[#674c1d]/70 font-bold tracking-wide">Tarif: {{ number_format($gadai->kategori->rate_jasa, 2) }}%</span>
                            </div>
                            <span class="font-bold text-amber-950 bg-amber-100/50 px-2 py-0.5 rounded transition-colors group-hover:bg-amber-100">Rp {{ number_format($gadai->biaya_jasa, 0, ',', '.') }}</span>
                        </div>
                        @if($gadai->denda_aktif > 0)
                        <div class="flex justify-between items-center group text-red-700">
                            <div class="flex flex-col">
                                <span class="font-medium">Denda Keterlambatan</span>
                                <span class="text-[10px] text-red-500 font-bold tracking-wide">Tarif: {{ number_format($gadai->kategori->rate_denda, 2) }}%</span>
                            </div>
                            <span class="font-bold bg-red-50 px-2 py-0.5 rounded transition-colors group-hover:bg-red-100">Rp {{ number_format($gadai->denda_aktif, 0, ',', '.') }}</span>
                        </div>
                        @endif
                        @if($gadai->biaya_inap > 0)
                        <div class="flex justify-between items-center group">
                            <div class="flex flex-col">
                                <span class="font-medium text-amber-800/80">Biaya Inap</span>
                                @if($gadai->item->nominal_inap > 0)
                                    <span class="text-[10px] text-[#674c1d]/70 font-bold tracking-wide">Tarif Flat: Rp {{ number_format($gadai->item->nominal_inap, 0, ',', '.') }}</span>
                                @else
                                    <span class="text-[10px] text-[#674c1d]/70 font-bold tracking-wide">Tarif: {{ number_format($gadai->kategori->rate_inap_persen, 2) }}% dari Taksiran</span>
                                @endif
                            </div>
                            <span class="font-bold text-amber-950 bg-amber-100/50 px-2 py-0.5 rounded transition-colors group-hover:bg-amber-100">Rp {{ number_format($gadai->biaya_inap, 0, ',', '.') }}</span>
                        </div>
                        @endif
                    </div>
                </div>
            </div>

            @if(session('error'))
            <div class="mb-6 animate-in slide-in-from-top-2 duration-300">
                <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 shadow-sm">
                    <div class="flex items-center">
                        <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <p class="text-red-700 text-sm font-medium">{{ session('error') }}</p>
                    </div>
                </div>
            </div>
            @endif

            @if($errors->any())
            <div class="mb-6 animate-in slide-in-from-top-2 duration-300">
                <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 shadow-sm">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 text-red-500 mr-2 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        <div>
                            <p class="text-red-800 text-sm font-bold mb-1">Terjadi kesalahan:</p>
                            <ul class="list-disc list-inside text-red-700 text-xs space-y-1">
                                @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
            @endif

            <form action="{{ route('nasabah.gadai_baru.store-pengajuan', ['id' => $gadai->id, 'jenis' => $jenis]) }}" method="POST" enctype="multipart/form-data" class="space-y-6" id="form-pengajuan">
                @csrf
                <input type="hidden" name="pin" id="pin-hidden-input">
                
                <!-- Pilih Metode -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-3 flex items-center gap-2">
                        <svg class="w-4 h-4 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path></svg>
                        Pilih Metode Pembayaran
                    </label>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                        <label class="relative flex flex-col items-center justify-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer transition-all duration-200 hover:shadow-md hover:border-amber-200 peer-checked:border-[#d4af37] peer-checked:bg-gradient-to-br peer-checked:from-amber-50 peer-checked:to-white peer-checked:shadow-amber-100 group" id="label-cash">
                            <input type="radio" name="metode" value="cash" class="sr-only peer" checked onclick="toggleMetode('cash')">
                            <div class="absolute inset-0 bg-white opacity-0 peer-checked:opacity-50 rounded-2xl transition-opacity"></div>
                            
                            <div class="relative z-10 flex flex-col items-center">
                                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3 group-hover:bg-amber-100 transition-colors peer-checked:bg-[#674c1d] peer-checked:text-white">
                                    <svg class="w-6 h-6 text-gray-500 peer-checked:text-white group-hover:text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                                </div>
                                <span class="text-sm font-bold text-gray-700 peer-checked:text-[#674c1d]">Tunai / Cash</span>
                                <span class="text-[10px] text-gray-500 peer-checked:text-amber-700/80 font-medium text-center mt-1">Janji Temu di Kantor</span>
                            </div>
                            <!-- Selection Indicator -->
                            <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 text-[#d4af37] transition-opacity">
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                        </label>
                        
                        <label class="relative flex flex-col items-center justify-center p-4 border-2 border-gray-100 rounded-2xl cursor-pointer transition-all duration-200 hover:shadow-md hover:border-amber-200 peer-checked:border-[#d4af37] peer-checked:bg-gradient-to-br peer-checked:from-amber-50 peer-checked:to-white peer-checked:shadow-amber-100 group" id="label-transfer">
                            <input type="radio" name="metode" value="transfer" class="sr-only peer" onclick="toggleMetode('transfer')">
                            <div class="absolute inset-0 bg-white opacity-0 peer-checked:opacity-50 rounded-2xl transition-opacity"></div>
                            
                            <div class="relative z-10 flex flex-col items-center">
                                <div class="w-12 h-12 bg-gray-50 rounded-full flex items-center justify-center mb-3 group-hover:bg-amber-100 transition-colors peer-checked:bg-[#674c1d] peer-checked:text-white">
                                    <svg class="w-6 h-6 text-gray-500 peer-checked:text-white group-hover:text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                                </div>
                                <span class="text-sm font-bold text-gray-700 peer-checked:text-[#674c1d]">Transfer Bank</span>
                                <span class="text-[10px] text-gray-500 peer-checked:text-amber-700/80 font-medium text-center mt-1">Upload Bukti Transfer</span>
                            </div>
                            <!-- Selection Indicator -->
                            <div class="absolute top-3 right-3 opacity-0 peer-checked:opacity-100 text-[#d4af37] transition-opacity">
                                <svg class="w-5 h-5" viewBox="0 0 20 20" fill="currentColor"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path></svg>
                            </div>
                        </label>
                    </div>
                </div>

                <!-- Input Janji Temu (Hidden by default based on selection) -->
                <div id="section-cash" class="space-y-4 animate-in fade-in duration-300">
                    <div class="bg-gray-50/80 p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <label class="block text-sm font-bold text-gray-700 mb-2">Rencana Kedatangan</label>
                        <input type="datetime-local" name="tgl_janji_temu" class="w-full px-4 py-3 bg-white border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d]/20 focus:border-[#674c1d] transition-all shadow-sm" min="{{ date('Y-m-d\TH:i') }}">
                        
                        <div class="mt-3 flex items-start gap-2 bg-amber-50/50 p-3 rounded-lg border border-amber-100/50">
                            <svg class="w-4 h-4 text-amber-600 mt-0.5 shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                            <p class="text-xs text-amber-800 leading-relaxed font-medium">Silakan datang ke kantor cabang <span class="font-bold text-[#674c1d]">{{ $gadai->lokasi->nama_lokasi }}</span> sesuai waktu yang dipilih untuk memproses pembayaran secara langsung.</p>
                        </div>
                    </div>
                </div>

                <!-- Input Transfer (Hidden by default) -->
                <div id="section-transfer" class="hidden space-y-5 animate-in fade-in duration-300">
                    <div class="space-y-3">
                        <label class="block text-sm font-bold text-gray-700 mb-1">Rekening Tujuan</label>
                        @foreach($banks as $bank)
                        <div class="bg-gradient-to-r from-gray-50 to-white border border-gray-200 rounded-xl p-5 relative group transition-all hover:shadow-md hover:border-amber-200">
                            <div class="flex items-center gap-4">
                                <div class="w-12 h-12 bg-white rounded-xl p-2 flex items-center justify-center shadow-sm border border-gray-100">
                                    <img src="{{ $bank->logo_url }}" class="w-full h-full object-contain" alt="{{ $bank->bank }}">
                                </div>
                                <div class="min-w-0">
                                    <p class="font-extrabold text-gray-900 tracking-tight leading-none mb-1 text-lg">{{ $bank->bank }}</p>
                                    <div class="flex items-center gap-2">
                                        <p class="font-mono font-bold text-[#674c1d] text-base tracking-widest truncate">{{ $bank->no_rek }}</p>
                                        <button type="button" onclick="copyToClipboard('{{ $bank->no_rek }}')" class="text-gray-400 hover:text-[#d4af37] transition-colors p-1 bg-gray-50 hover:bg-amber-50 rounded-md" title="Salin No. Rekening">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                                        </button>
                                    </div>
                                    <p class="text-[11px] text-gray-500 font-bold uppercase tracking-widest mt-1.5">A/N: <span class="text-gray-700">{{ $bank->pemilik }}</span></p>
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    
                    <div class="bg-gray-50/80 p-5 rounded-2xl border border-gray-100 shadow-sm">
                        <label class="block text-sm font-bold text-gray-700 mb-3">Upload Bukti Transfer <span class="text-red-500">*</span></label>
                        <div id="bukti-container" class="space-y-3">
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-8 text-center bg-white hover:bg-gray-50 hover:border-[#d4af37] transition-all cursor-pointer group" onclick="addBuktiField()">
                                <div class="w-14 h-14 bg-gray-50 rounded-full flex items-center justify-center mx-auto mb-3 group-hover:bg-amber-50 group-hover:scale-110 transition-transform">
                                    <svg class="w-6 h-6 text-gray-400 group-hover:text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                    </svg>
                                </div>
                                <p class="text-sm text-gray-700 font-bold group-hover:text-[#674c1d]">Klik untuk tambah foto bukti transfer</p>
                                <p class="text-xs text-gray-400 mt-1.5">Format: JPG, PNG (Maks 2MB)</p>
                            </div>
                        </div>
                        <p class="text-[11px] text-gray-500 mt-3 font-medium bg-white px-3 py-2 rounded-lg border border-gray-200">
                            <span class="text-amber-600 font-bold">Info:</span> Anda wajib mengunggah minimal 1 bukti transfer. Anda bisa menambahkan lebih dari satu foto jika pembayaran dicicil ke beberapa rekening.
                        </p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-2">Keterangan Tambahan <span class="text-gray-400 font-normal">(Opsional)</span></label>
                    <textarea name="keterangan" rows="3" class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:ring-2 focus:ring-[#674c1d]/20 focus:border-[#674c1d] focus:bg-white transition-all shadow-sm placeholder-gray-400" placeholder="Contoh: Pembayaran melalui BCA a.n Budi..."></textarea>
                </div>

                <div class="pt-6 border-t border-gray-100">
                    <button type="button" onclick="showPinModal()" id="btnSubmit" class="w-full bg-gradient-to-r from-[#674c1d] to-[#8a6829] hover:from-[#543e18] hover:to-[#674c1d] text-white font-bold py-4 px-6 rounded-2xl transition-all shadow-[0_8px_20px_rgba(103,76,29,0.2)] hover:shadow-[0_8px_25px_rgba(103,76,29,0.3)] hover:-translate-y-0.5 flex items-center justify-center gap-3">
                        <span class="text-base tracking-wide">Kirim Pengajuan {{ ucfirst($jenis) }}</span>
                        <div class="w-6 h-6 rounded-full bg-white/20 flex items-center justify-center backdrop-blur-sm">
                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                        </div>
                    </button>
                    <a href="{{ route('nasabah.gadai_baru.index') }}" class="block text-center mt-5 text-sm text-gray-500 hover:text-gray-800 font-semibold transition-colors">Batalkan & Kembali ke Dashboard</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleMetode(metode) {
        const sectionCash = document.getElementById('section-cash');
        const sectionTransfer = document.getElementById('section-transfer');
        
        // Let Tailwind's peer-checked handle the label styling automatically
        
        if (metode === 'cash') {
            sectionCash.classList.remove('hidden');
            sectionTransfer.classList.add('hidden');
        } else {
            sectionCash.classList.add('hidden');
            sectionTransfer.classList.remove('hidden');
            
            // Auto add first field if empty
            if (buktiCount === 0) addBuktiField();
        }
    }

    let buktiCount = 0;
    function addBuktiField() {
        buktiCount++;
        const container = document.getElementById('bukti-container');
        const div = document.createElement('div');
        div.className = 'border border-gray-200 rounded-xl p-4 bg-white shadow-sm relative animate-in fade-in slide-in-from-top-2 duration-300 hover:border-amber-200 transition-colors group/item';
        div.innerHTML = `
            <div class="flex items-center justify-between mb-3">
                <label class="text-xs font-bold text-gray-700 bg-gray-100 px-2.5 py-1 rounded-md">Bukti Foto #${buktiCount}</label>
                <button type="button" onclick="this.closest('.border').remove();" class="text-gray-400 hover:text-red-500 hover:bg-red-50 p-1.5 rounded-lg transition-colors" title="Hapus foto ini">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                </button>
            </div>
            <input type="file" name="bukti_transfer[]" required accept="image/*" class="w-full text-sm text-gray-600 
                file:mr-4 file:py-2.5 file:px-4 file:rounded-xl file:border-0 
                file:text-xs file:font-bold file:bg-gray-100 file:text-gray-700
                hover:file:bg-[#674c1d] hover:file:text-white transition-all cursor-pointer">
        `;
        container.appendChild(div);
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Nomor rekening berhasil disalin: ' + text);
        });
    }
    
    function showPinModal() {
        const form = document.getElementById('form-pengajuan');
        if (!form.checkValidity()) {
            form.reportValidity();
            return;
        }

        // Validate files for transfer
        const metode = document.querySelector('input[name="metode"]:checked').value;
        if (metode === 'transfer') {
            const fileInputs = form.querySelectorAll('input[type="file"]');
            let hasFile = false;
            for (let input of fileInputs) {
                if (input.files && input.files[0]) {
                    hasFile = true;
                    if (input.files[0].size > 2 * 1024 * 1024) {
                        alert('Ukuran file ' + input.files[0].name + ' terlalu besar. Maksimal 2MB.');
                        return;
                    }
                }
            }
            if (!hasFile) {
                alert('Silakan tambahkan minimal 1 bukti transfer.');
                return;
            }
        }

        const modal = document.getElementById('pin-modal');
        modal.classList.remove('hidden');
        modal.classList.add('flex');
        
        // Small delay to allow display:flex to apply before adding opacity class for transition
        setTimeout(() => {
            modal.querySelector('.backdrop-blur-sm').classList.remove('opacity-0');
            modal.querySelector('.transform').classList.remove('scale-95', 'opacity-0');
            modal.querySelector('.transform').classList.add('scale-100', 'opacity-100');
        }, 10);
        
        document.getElementById('pin-input').value = '';
        document.getElementById('pin-error').classList.add('hidden');
        document.getElementById('pin-input').focus();
    }

    function closePinModal() {
        const modal = document.getElementById('pin-modal');
        
        modal.querySelector('.backdrop-blur-sm').classList.add('opacity-0');
        modal.querySelector('.transform').classList.remove('scale-100', 'opacity-100');
        modal.querySelector('.transform').classList.add('scale-95', 'opacity-0');
        
        setTimeout(() => {
            modal.classList.add('hidden');
            modal.classList.remove('flex');
        }, 200); // Wait for transition to finish
    }

    function verifyAndSubmit() {
        const pin = document.getElementById('pin-input').value;

        if (pin.length !== 6) {
            showPinError('PIN harus terdiri dari 6 angka');
            return;
        }

        document.getElementById('pin-hidden-input').value = pin;
        closePinModal();
        
        // Show elegant loading state on btnSubmit
        const btnSubmit = document.getElementById('btnSubmit');
        btnSubmit.disabled = true;
        btnSubmit.classList.add('opacity-80', 'cursor-not-allowed');
        btnSubmit.innerHTML = `
            <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            <span class="text-base tracking-wide">Memproses Pengajuan...</span>
        `;
        
        document.getElementById('form-pengajuan').submit();
    }

    function showPinError(message) {
        const errorDiv = document.getElementById('pin-error');
        errorDiv.querySelector('p').textContent = message;
        errorDiv.classList.remove('hidden');
        
        // Shake animation for the input
        const input = document.getElementById('pin-input');
        input.classList.add('border-red-400', 'bg-red-50');
        input.classList.remove('border-gray-200');
        
        setTimeout(() => {
            input.classList.remove('border-red-400', 'bg-red-50');
            input.classList.add('border-gray-200');
        }, 2000);
    }
    
    // Auto-verify when 6 digits are entered
    document.getElementById('pin-input')?.addEventListener('input', function(e) {
        if(this.value.length === 6) {
            verifyAndSubmit();
        }
    });
    
    // Set initial state
    document.addEventListener('DOMContentLoaded', function() {
        // Initialize state based on radio checked
        const checkedMetode = document.querySelector('input[name="metode"]:checked').value;
        toggleMetode(checkedMetode);
    });
</script>

<!-- Enhanced Premium PIN Modal -->
<div id="pin-modal" class="fixed inset-0 z-50 hidden items-center justify-center p-4">
    <div class="absolute inset-0 bg-black/60 backdrop-blur-sm opacity-0 transition-opacity duration-300" onclick="closePinModal()"></div>
    
    <div class="relative bg-white rounded-3xl shadow-2xl max-w-md w-full p-5 sm:p-8 transform scale-95 opacity-0 transition-all duration-300 overflow-hidden">
        <!-- Decorative Top Border -->
        <div class="absolute top-0 left-0 right-0 h-1.5 bg-gradient-to-r from-[#674c1d] via-[#d4af37] to-[#674c1d]"></div>
        
        <div class="flex flex-col items-center text-center mb-6 pt-2">
            <div class="w-16 h-16 bg-amber-50 rounded-full flex items-center justify-center mb-4 border border-amber-100 shadow-sm">
                <svg class="w-8 h-8 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                </svg>
            </div>
            <h3 class="text-xl font-extrabold text-gray-900 tracking-tight">Otorisasi Transaksi</h3>
            <p class="text-sm text-gray-500 mt-1">Masukkan 6 digit PIN keamanan Anda untuk mengkonfirmasi pengajuan {{ $jenis }}.</p>
        </div>

        <div id="pin-error" class="hidden mb-5 p-3 bg-red-50 border-l-4 border-red-500 rounded-r-xl">
            <div class="flex items-center">
                <svg class="w-5 h-5 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-red-700 text-sm font-semibold"></p>
            </div>
        </div>

        <div class="mb-8">
            <input type="password" id="pin-input" maxlength="6" pattern="[0-9]*" inputmode="numeric"
                class="w-full px-4 py-4 border-2 border-gray-200 rounded-2xl focus:border-[#674c1d] focus:ring-4 focus:ring-[#674c1d]/10 outline-none text-center text-2xl sm:text-3xl font-mono tracking-[0.3em] sm:tracking-[0.5em] text-gray-800 transition-all shadow-sm"
                placeholder="••••••" oninput="this.value = this.value.replace(/[^0-9]/g, '')" autocomplete="off">
        </div>

        <div class="flex gap-3">
            <button type="button" onclick="closePinModal()"
                class="flex-1 px-4 py-3.5 border-2 border-gray-200 text-gray-600 rounded-xl font-bold hover:bg-gray-50 hover:border-gray-300 hover:text-gray-800 transition-all">
                Batalkan
            </button>
            <button type="button" onclick="verifyAndSubmit()"
                class="flex-1 px-4 py-3.5 bg-gradient-to-r from-[#674c1d] to-[#8a6829] hover:from-[#543e18] hover:to-[#674c1d] text-white rounded-xl font-bold shadow-lg shadow-amber-900/20 transition-all">
                Verifikasi
            </button>
        </div>
    </div>
</div>
@endsection
