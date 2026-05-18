@extends('layouts.nasabah')

@section('title', 'Pengajuan ' . ucfirst($jenis))

@section('content')
<div class="max-w-2xl mx-auto pb-10 mt-6 px-4">
    <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
        <div class="bg-[#674c1d] px-6 py-4 flex items-center justify-between">
            <h2 class="text-lg font-bold text-white">Form Pengajuan {{ ucfirst($jenis) }}</h2>
            <span class="bg-white/20 text-white text-xs font-semibold px-2.5 py-0.5 rounded backdrop-blur-sm">{{ $gadai->kategori->nama_kategori }}</span>
        </div>

        <div class="p-6">
            <!-- Info Ringkasan -->
            <div class="mb-8 bg-amber-50 border border-amber-100 rounded-2xl p-5">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-bold text-amber-900">{{ $gadai->item->nama_item }}</h3>
                        <p class="text-sm text-amber-700">Slot: {{ $gadai->slot_kode }}</p>
                    </div>
                    <div class="text-right">
                        <p class="text-xs text-amber-600 font-medium uppercase tracking-wider">Total Pembayaran</p>
                        <p class="text-2xl font-black text-amber-900">Rp {{ number_format($nominal, 0, ',', '.') }}</p>
                    </div>
                </div>
                <div class="text-sm text-amber-800 space-y-1 pt-3 border-t border-amber-200/50">
                    <div class="flex justify-between">
                        <span>Pokok Pinjaman:</span>
                        <span>Rp {{ number_format($gadai->nominal_deal, 0, ',', '.') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span>Biaya Jasa:</span>
                        <span>Rp {{ number_format($gadai->biaya_jasa, 0, ',', '.') }}</span>
                    </div>
                    @if($gadai->denda_aktif > 0)
                    <div class="flex justify-between text-red-600">
                        <span>Denda:</span>
                        <span>Rp {{ number_format($gadai->denda_aktif, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @if($gadai->biaya_inap > 0)
                    <div class="flex justify-between text-orange-600">
                        <span>Biaya Inap:</span>
                        <span>Rp {{ number_format($gadai->biaya_inap, 0, ',', '.') }}</span>
                    </div>
                    @endif
                </div>
            </div>

            <form action="{{ route('nasabah.gadai_baru.store-pengajuan', ['id' => $gadai->id, 'jenis' => $jenis]) }}" method="POST" enctype="multipart/form-data" class="space-y-6" onsubmit="document.getElementById('btnSubmit').disabled=true; document.getElementById('btnSubmit').innerHTML='Mengolah...';">
                @csrf
                
                <!-- Pilih Metode -->
                <div>
                    <label class="block text-sm font-bold text-gray-700 mb-3">Metode Pembayaran</label>
                    <div class="grid grid-cols-2 gap-4">
                        <label class="relative flex flex-col items-center justify-center p-4 border-2 rounded-2xl cursor-pointer transition-all hover:bg-gray-50 peer-checked:border-[#674c1d] peer-checked:bg-amber-50" id="label-cash">
                            <input type="radio" name="metode" value="cash" class="sr-only peer" checked onclick="toggleMetode('cash')">
                            <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                            <span class="text-sm font-bold text-gray-700">Tunai / Cash</span>
                            <span class="text-[10px] text-gray-500 text-center mt-1">Janji Temu di Kantor</span>
                        </label>
                        
                        <label class="relative flex flex-col items-center justify-center p-4 border-2 rounded-2xl cursor-pointer transition-all hover:bg-gray-50 peer-checked:border-[#674c1d] peer-checked:bg-amber-50" id="label-transfer">
                            <input type="radio" name="metode" value="transfer" class="sr-only peer" onclick="toggleMetode('transfer')">
                            <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7h12m0 0l-4-4m4 4l-4 4m0 6H4m0 0l4 4m-4-4l4-4"></path></svg>
                            <span class="text-sm font-bold text-gray-700">Transfer Bank</span>
                            <span class="text-[10px] text-gray-500 text-center mt-1">Upload Bukti Transfer</span>
                        </label>
                    </div>
                </div>

                <!-- Input Janji Temu (Hidden by default based on selection) -->
                <div id="section-cash" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rencana Kedatangan</label>
                        <input type="datetime-local" name="tgl_janji_temu" class="w-full border-gray-300 rounded-xl focus:ring-[#674c1d] focus:border-[#674c1d]" min="{{ date('Y-m-d\TH:i') }}">
                        <p class="text-[10px] text-gray-500 mt-1">*Silakan datang ke kantor cabang {{ $gadai->lokasi->nama_lokasi }} sesuai waktu yang dipilih.</p>
                    </div>
                </div>

                <!-- Input Transfer (Hidden by default) -->
                <div id="section-transfer" class="hidden space-y-4">
                    <div class="space-y-3">
                        @foreach($banks as $bank)
                        <div class="bg-blue-50 border border-blue-100 rounded-xl p-4 text-sm text-blue-800 relative group transition-all hover:bg-blue-100/50">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 bg-white rounded-lg p-1 flex items-center justify-center shadow-xs">
                                    <img src="{{ $bank->logo_url }}" class="w-full h-full object-contain" alt="{{ $bank->bank }}">
                                </div>
                                <div>
                                    <p class="font-black text-blue-900 tracking-tight leading-none mb-1">{{ $bank->bank }} ({{ $bank->nama }})</p>
                                    <p class="font-mono font-black text-blue-800 text-lg tracking-tighter">{{ $bank->no_rek }}</p>
                                    <p class="text-[10px] text-blue-600 font-bold uppercase tracking-widest mt-1">A/N: {{ $bank->pemilik }}</p>
                                </div>
                            </div>
                            <button type="button" onclick="copyToClipboard('{{ $bank->no_rek }}')" class="absolute top-4 right-4 text-blue-400 hover:text-blue-600 transition-colors" title="Salin No. Rekening">
                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path></svg>
                            </button>
                        </div>
                        @endforeach
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Upload Bukti Transfer *</label>
                        <div id="bukti-container" class="space-y-3">
                            <div class="border-2 border-dashed border-gray-300 rounded-xl p-6 text-center hover:border-[#674c1d] transition-colors cursor-pointer" onclick="addBuktiField()">
                                <svg class="w-10 h-10 text-gray-400 mx-auto mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 16a4 4 0 01-.88-7.903A5 5 0 1115.9 6L16 6a5 5 0 011 9.9M15 13l-3-3m0 0l-3 3m3-3v12"></path>
                                </svg>
                                <p class="text-xs text-gray-600 font-semibold">Klik untuk tambah bukti transfer</p>
                                <p class="text-[10px] text-gray-400 mt-1">Format: JPG, PNG (Max 2MB per file)</p>
                            </div>
                        </div>
                        <p class="text-[10px] text-gray-500 mt-2">Minimal upload 1 bukti transfer. Anda bisa upload beberapa bukti jika diperlukan.</p>
                    </div>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Keterangan (Opsional)</label>
                    <textarea name="keterangan" rows="2" class="w-full border-gray-300 rounded-xl focus:ring-[#674c1d] focus:border-[#674c1d]" placeholder="Tambahkan pesan jika perlu..."></textarea>
                </div>

                <div class="pt-4">
                    <button type="submit" class="w-full bg-[#674c1d] hover:bg-[#543e18] text-white font-bold py-4 rounded-2xl transition-all shadow-lg shadow-amber-200 flex items-center justify-center gap-2">
                        <span>Kirim Pengajuan {{ ucfirst($jenis) }}</span>
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </button>
                    <a href="{{ route('nasabah.gadai_baru.index') }}" class="block text-center mt-4 text-sm text-gray-500 hover:text-gray-700 font-medium">Batal & Kembali</a>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function toggleMetode(metode) {
        const sectionCash = document.getElementById('section-cash');
        const sectionTransfer = document.getElementById('section-transfer');
        const labelCash = document.getElementById('label-cash');
        const labelTransfer = document.getElementById('label-transfer');

        if (metode === 'cash') {
            sectionCash.classList.remove('hidden');
            sectionTransfer.classList.add('hidden');
            labelCash.classList.add('border-[#674c1d]', 'bg-amber-50');
            labelTransfer.classList.remove('border-[#674c1d]', 'bg-amber-50');
        } else {
            sectionCash.classList.add('hidden');
            sectionTransfer.classList.remove('hidden');
            labelTransfer.classList.add('border-[#674c1d]', 'bg-amber-50');
            labelCash.classList.remove('border-[#674c1d]', 'bg-amber-50');
            
            // Auto add first field if empty
            if (buktiCount === 0) addBuktiField();
        }
    }

    let buktiCount = 0;
    function addBuktiField() {
        buktiCount++;
        const container = document.getElementById('bukti-container');
        const div = document.createElement('div');
        div.className = 'border border-gray-200 rounded-xl p-3 bg-gray-50 relative animate-in fade-in slide-in-from-top-2 duration-300';
        div.innerHTML = `
            <div class="flex items-center justify-between mb-2">
                <label class="text-[10px] font-bold text-gray-500 uppercase">Bukti Foto</label>
                <button type="button" onclick="this.closest('.border').remove();" class="text-red-500 hover:text-red-700">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            </div>
            <input type="file" name="bukti_transfer[]" required accept="image/*" class="w-full text-xs text-gray-500 file:mr-3 file:py-1 file:px-3 file:rounded-lg file:border-0 file:text-[10px] file:font-bold file:bg-[#674c1d] file:text-white">
        `;
        container.appendChild(div);
    }

    function copyToClipboard(text) {
        navigator.clipboard.writeText(text).then(() => {
            alert('Nomor rekening berhasil disalin!');
        });
    }
    
    // Set initial state
    document.addEventListener('DOMContentLoaded', function() {
        toggleMetode('cash');
    });
</script>

<style>
    .peer:checked + label {
        border-color: #674c1d;
        background-color: rgba(251, 191, 36, 0.05);
    }
</style>
@endsection
