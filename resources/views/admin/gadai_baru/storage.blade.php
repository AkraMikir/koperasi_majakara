@extends('layouts.admin')

@section('title', 'Penyimpanan Gadai')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-2xl font-bold text-gray-900 font-display">Penyimpanan Gadai</h1>
            <p class="text-gray-500 text-sm mt-1">Lihat status ketersediaan grid penyimpanan (Slot Storage)</p>
        </div>
        <div>
            <form action="{{ route('admin.gadai_baru.storage') }}" method="GET" class="flex gap-2">
                <select name="kategori" class="border-gray-200 rounded-xl focus:ring-[#674c1d] focus:border-[#674c1d]" onchange="this.form.submit()">
                    <option value="electronic" {{ $kategori == 'electronic' ? 'selected' : '' }}>Elektronik</option>
                    <option value="vehicle" {{ $kategori == 'vehicle' ? 'selected' : '' }}>Kendaraan</option>
                    <option value="gold" {{ $kategori == 'gold' ? 'selected' : '' }}>Emas</option>
                </select>
            </form>
        </div>
    </div>

    {{-- Alert Messages --}}
    @if(session('success'))
    <div class="bg-emerald-50 border-l-4 border-emerald-500 p-4 rounded-xl shadow-xs flex items-center justify-between animate-fade-in">
        <div class="flex items-center space-x-3">
            <span class="text-xl">✅</span>
            <p class="text-sm font-semibold text-emerald-800">{{ session('success') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-emerald-500 hover:text-emerald-700 font-bold">&times;</button>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-xs flex items-center justify-between animate-fade-in">
        <div class="flex items-center space-x-3">
            <span class="text-xl">❌</span>
            <p class="text-sm font-semibold text-red-800">{{ session('error') }}</p>
        </div>
        <button onclick="this.parentElement.remove()" class="text-red-500 hover:text-red-700 font-bold">&times;</button>
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-xl shadow-xs animate-fade-in">
        <div class="flex items-center space-x-3 mb-2">
            <span class="text-xl">⚠️</span>
            <p class="text-sm font-bold text-red-800">Ada kesalahan input data:</p>
        </div>
        <ul class="list-disc list-inside text-xs text-red-700 font-medium space-y-1 ml-6">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <div class="bg-white border border-gray-200 rounded-2xl shadow-sm p-6 overflow-x-auto">
        <div class="min-w-max space-y-4">
            @forelse($groupedGrid as $baris => $koloms)
                <div class="flex gap-4 items-center">
                    <div class="w-16 font-bold text-gray-500 text-sm text-right">Baris {{ $baris }}</div>
                    <div class="flex gap-4">
                        @foreach($koloms as $slot)
                            @if($slot->is_occupied)
                                @php
                                    $isExpired = $slot->gadai_status === 'expired_final';
                                @endphp
                                <div class="w-32 h-36 {{ $isExpired ? 'bg-amber-50 border-2 border-amber-300' : 'bg-red-50 border-2 border-red-200' }} rounded-xl p-3 flex flex-col justify-between shadow-xs transition-all hover:shadow-sm" title="{{ $slot->nasabah_nama }} - {{ $slot->item_nama }}">
                                    <div class="flex justify-between items-center">
                                        <div class="text-xs font-bold {{ $isExpired ? 'text-amber-800' : 'text-red-800' }}">{{ $slot->kode_slot }}</div>
                                        @if($isExpired)
                                            <span class="px-1.5 py-0.5 bg-amber-200 text-amber-900 text-[8px] font-black rounded-sm uppercase tracking-wide">Hangus</span>
                                        @else
                                            <span class="px-1.5 py-0.5 bg-red-100 text-red-700 text-[8px] font-bold rounded-sm uppercase tracking-wide">Terisi</span>
                                        @endif
                                    </div>
                                    <div class="my-1 flex-1 flex flex-col justify-center min-w-0">
                                        <div class="text-[10px] {{ $isExpired ? 'text-amber-900 font-extrabold' : 'text-red-900 font-bold' }} truncate">{{ $slot->item_nama }}</div>
                                        <div class="text-[9px] text-gray-500 truncate">{{ $slot->nasabah_nama }}</div>
                                    </div>
                                    @if($isExpired)
                                        <button onclick="openEmptyAuctionModal({{ $slot->active_gadai_id }}, '{{ $slot->kode_slot }}', '{{ $slot->nasabah_nama }}', '{{ $slot->item_nama }}')" class="w-full py-1.5 bg-amber-600 hover:bg-amber-700 active:scale-95 text-white text-[9px] font-black rounded-lg transition-all shadow-xs uppercase tracking-wider">
                                            Kosongkan
                                        </button>
                                    @else
                                        <div class="text-[9px] text-gray-400 italic text-center font-medium">Aktif</div>
                                    @endif
                                </div>
                            @else
                                <div class="w-32 h-36 bg-emerald-50/50 border-2 border-emerald-200 border-dashed rounded-xl p-3 flex flex-col justify-between hover:bg-emerald-50 hover:border-emerald-300 transition-colors">
                                    <div class="text-xs font-bold text-emerald-800">{{ $slot->kode_slot }}</div>
                                    <div class="flex-1 flex items-center justify-center">
                                        <span class="px-2 py-0.5 bg-emerald-100 text-emerald-700 text-xs rounded-full font-bold">Kosong</span>
                                    </div>
                                </div>
                            @endif
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="text-center py-12 text-gray-500">
                    Tidak ada grid penyimpanan untuk kategori ini.
                </div>
            @endforelse
        </div>
    </div>
</div>

<!-- Modal Empty Storage for Lelang -->
<div id="emptyAuctionModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500/70 backdrop-blur-xs transition-opacity" aria-hidden="true" onclick="closeEmptyAuctionModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-3xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full border border-gray-100 scale-95 duration-300">
            <form action="{{ route('admin.gadai_baru.storage.empty-auction') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="gadai_id" id="modal_gadai_id">
                
                <div class="bg-linear-to-r from-amber-600 to-[#8b6f2f] px-6 py-4 flex items-center justify-between">
                    <h3 class="text-lg font-bold text-white font-display" id="modal-title">Kosongkan Slot untuk Lelang</h3>
                    <button type="button" onclick="closeEmptyAuctionModal()" class="text-white/80 hover:text-white transition-colors">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>
                
                <div class="p-6 space-y-4">
                    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-4 flex items-start space-x-3">
                        <span class="text-2xl">🚨</span>
                        <div>
                            <p class="text-xs text-amber-800 font-bold uppercase tracking-wider">Perhatian</p>
                            <p class="text-xs text-amber-700 font-medium leading-relaxed mt-0.5">Barang pada slot ini berstatus <strong class="underline">hangus</strong>. Dengan melakukan konfirmasi, kapasitas slot akan dikosongkan agar bisa digunakan kembali, dan status barang akan diubah menjadi <strong>Sudah Dilelang (Auctioned)</strong>.</p>
                        </div>
                    </div>

                    <div class="grid grid-cols-2 gap-4 bg-gray-50 p-4 rounded-2xl border border-gray-100">
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Kode Slot</p>
                            <p id="modal_slot_kode" class="text-sm font-extrabold text-gray-800 mt-0.5">-</p>
                        </div>
                        <div>
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Barang / Kategori</p>
                            <p id="modal_item_nama" class="text-sm font-extrabold text-gray-800 mt-0.5">-</p>
                        </div>
                        <div class="col-span-2 pt-2 border-t border-gray-200/50">
                            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-wider">Nama Nasabah</p>
                            <p id="modal_nasabah_nama" class="text-sm font-semibold text-gray-700 mt-0.5">-</p>
                        </div>
                    </div>

                    <!-- Upload Foto Bukti (multiple) -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Upload Foto Bukti Pengambilan</label>
                        <div class="relative border-2 border-dashed border-gray-200 hover:border-amber-400 rounded-2xl p-4 transition-all bg-gray-50/50">
                            <input type="file" name="foto_bukti[]" multiple required class="absolute inset-0 w-full h-full opacity-0 cursor-pointer" onchange="previewImages(event)">
                            <div class="text-center space-y-1">
                                <svg class="mx-auto h-8 w-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                <p class="text-xs text-gray-600 font-semibold">Pilih atau Seret Foto di sini</p>
                                <p class="text-[10px] text-gray-400 font-medium">JPEG, PNG, JPG (Maks. 2MB per foto)</p>
                            </div>
                        </div>
                        <div id="image_preview_container" class="flex flex-wrap gap-2 pt-2"></div>
                    </div>

                    <!-- Catatan -->
                    <div class="space-y-1.5">
                        <label class="block text-xs font-bold text-gray-700 uppercase tracking-wider">Catatan Pengambilan Barang</label>
                        <textarea name="catatan" rows="3" required placeholder="Contoh: Barang diambil oleh tim logistik A untuk dipindahkan ke balai lelang utama..." class="w-full border-gray-200 rounded-2xl focus:ring-amber-500 focus:border-amber-500 text-xs font-medium placeholder:text-gray-400"></textarea>
                    </div>
                </div>

                <div class="bg-gray-50 px-6 py-4 flex flex-row-reverse gap-3 rounded-b-3xl">
                    <button type="submit" class="px-5 py-2.5 bg-amber-600 hover:bg-amber-700 active:scale-95 text-white text-xs font-black rounded-xl transition-all shadow-md shadow-amber-600/20 uppercase tracking-wider">
                        Konfirmasi & Kosongkan Slot
                    </button>
                    <button type="button" onclick="closeEmptyAuctionModal()" class="px-4 py-2.5 bg-white border border-gray-200 hover:bg-gray-50 text-gray-700 text-xs font-bold rounded-xl transition-all">
                        Batal
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    function openEmptyAuctionModal(gadaiId, slotKode, nasabahNama, itemNama) {
        document.getElementById('modal_gadai_id').value = gadaiId;
        document.getElementById('modal_slot_kode').textContent = slotKode;
        document.getElementById('modal_nasabah_nama').textContent = nasabahNama;
        document.getElementById('modal_item_nama').textContent = itemNama;
        
        const modal = document.getElementById('emptyAuctionModal');
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden';
    }

    function closeEmptyAuctionModal() {
        const modal = document.getElementById('emptyAuctionModal');
        modal.classList.add('hidden');
        document.body.style.overflow = '';
        
        // Reset preview
        document.getElementById('image_preview_container').innerHTML = '';
    }

    function previewImages(event) {
        const container = document.getElementById('image_preview_container');
        container.innerHTML = '';
        const files = event.target.files;
        
        if (files) {
            Array.from(files).forEach(file => {
                const reader = new FileReader();
                reader.onload = function(e) {
                    const div = document.createElement('div');
                    div.className = 'relative w-12 h-12 rounded-lg overflow-hidden border border-gray-200 shadow-xs';
                    div.innerHTML = `<img src="${e.target.result}" class="w-full h-full object-cover">`;
                    container.appendChild(div);
                }
                reader.readAsDataURL(file);
            });
        }
    }
</script>
@endsection
