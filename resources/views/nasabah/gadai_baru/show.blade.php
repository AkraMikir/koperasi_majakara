@extends('layouts.nasabah')

@section('title', 'Pengajuan Gadai')

@section('content')
    <div class="max-w-2xl mx-auto pb-8 mt-6 px-4 sm:px-0">
        <div class="bg-white rounded-[2rem] shadow-2xl border border-[#d4af37]/20 overflow-hidden relative">
            <!-- Header -->
            <div class="bg-gradient-to-br from-[#4a3614] via-[#674c1d] to-[#d4af37] px-8 py-8 text-white relative overflow-hidden">
                <div class="absolute inset-0 bg-[url('https://www.transparenttextures.com/patterns/stardust.png')] opacity-30 mix-blend-overlay"></div>
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-white/10 rounded-full blur-3xl"></div>
                <div class="absolute bottom-0 left-0 w-full h-1 bg-gradient-to-r from-transparent via-white/40 to-transparent"></div>
                
                <div class="relative z-10 flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                    <div>
                        <h2 class="text-2xl font-black tracking-tight mb-1">Form Pengajuan Gadai</h2>
                        <div class="flex items-center gap-2 text-[#f8f5ee]/90 text-sm font-medium">
                            <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" /></svg>
                            Diproses via WhatsApp
                        </div>
                    </div>
                    <div class="inline-flex items-center justify-center bg-white/20 backdrop-blur-md border border-white/40 text-white text-xs font-black px-4 py-2 rounded-full shadow-lg">
                        {{ $kategori->nama_kategori }}
                    </div>
                </div>
            </div>

            <div class="p-8">
                <!-- Receipt/Invoice Card -->
                <div class="mb-10 relative">
                    <div class="absolute -left-8 -right-8 -top-8 bottom-0 bg-[#fdfbf7] border-b border-dashed border-[#d4af37]/40 -z-10"></div>
                    <div class="flex flex-col sm:flex-row gap-6 items-start relative z-10 pt-2">
                        @if($item->file_pic)
                        <div class="w-32 h-32 rounded-2xl overflow-hidden shadow-xl border-4 border-white flex-shrink-0 bg-white rotate-[-2deg] hover:rotate-0 transition-transform hidden sm:block">
                            <img src="{{ asset('storage/' . $item->file_pic) }}" class="w-full h-full object-cover">
                        </div>
                        @endif
                        <div class="flex-1 w-full">
                            <h3 class="font-black text-gray-900 text-2xl mb-1 tracking-tight">{{ $item->head_1 }}</h3>
                            @if($item->head_2)
                                <p class="text-sm text-[#8b6f2f] mb-4 font-bold uppercase tracking-wider">{{ $item->head_2 }}</p>
                            @endif
                            
                            <div class="bg-white rounded-xl p-4 shadow-sm border border-gray-100 space-y-3">
                                <div class="flex justify-between items-center pb-2 border-b border-gray-50">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Range Taksiran</span>
                                    <span class="font-black text-[#674c1d]">Rp {{ number_format($item->nominal_low, 0, ',', '.') }} - {{ number_format($item->nominal_high, 0, ',', '.') }}</span>
                                </div>
                                <div class="flex justify-between items-center pb-2 border-b border-gray-50">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Biaya Jasa</span>
                                    <span class="font-black text-[#674c1d]">{{ $item->bunga_low }}% - {{ $item->bunga_high }}%</span>
                                </div>
                                <div class="flex justify-between items-center pb-2 border-b border-gray-50">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Denda Terlambat</span>
                                    <span class="font-black text-red-600">{{ $kategori->rate_denda }}%</span>
                                </div>
                                @if($item->rate_inap_nominal > 0)
                                <div class="flex justify-between items-center pb-2 border-b border-gray-50">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Biaya Inap</span>
                                    <span class="font-black text-[#674c1d]">Rp {{ number_format($item->rate_inap_nominal, 0, ',', '.') }}</span>
                                </div>
                                @endif
                                <div class="flex justify-between items-center pt-1">
                                    <span class="text-xs font-bold text-gray-400 uppercase tracking-widest">Masa Gadai</span>
                                    <span class="font-black text-gray-900 bg-gray-100 px-2 py-0.5 rounded-md">{{ $kategori->masa_gadai_hari }} Hari</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <form id="waForm" class="space-y-6">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <!-- Floating Label Input: Nama -->
                        <div class="relative group">
                            <input type="text" id="nama"
                                class="block rounded-2xl px-4 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50/50 border border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-[#d4af37] focus:bg-white peer shadow-sm transition-all font-semibold"
                                value="{{ Auth::user()->nama }}" placeholder=" " required />
                            <label for="nama" class="absolute text-xs text-gray-400 font-bold uppercase tracking-widest duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 peer-focus:text-[#d4af37]">Nama Lengkap</label>
                        </div>

                        <!-- Floating Label Input: No HP -->
                        <div class="relative group">
                            <input type="text" id="no_hp"
                                class="block rounded-2xl px-4 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50/50 border border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-[#d4af37] focus:bg-white peer shadow-sm transition-all font-semibold"
                                value="{{ Auth::user()->nomor_hp }}" placeholder=" " required />
                            <label for="no_hp" class="absolute text-xs text-gray-400 font-bold uppercase tracking-widest duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 peer-focus:text-[#d4af37]">Nomor HP / WA</label>
                        </div>
                    </div>

                    <!-- Floating Label Select: Cabang -->
                    <div class="relative group">
                        <select id="cabang"
                            class="block rounded-2xl px-4 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50/50 border border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-[#d4af37] focus:bg-white peer shadow-sm transition-all font-semibold" required>
                            <option value="" disabled selected></option>
                            @foreach($lokasi as $loc)
                                <option value="{{ $loc->nama_lokasi }}">{{ $loc->nama_lokasi }}</option>
                            @endforeach
                        </select>
                        <label for="cabang" class="absolute text-xs text-gray-400 font-bold uppercase tracking-widest duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 peer-focus:text-[#d4af37]">Lokasi Cabang Tujuan</label>
                        <div class="absolute inset-y-0 right-0 flex items-center px-4 pointer-events-none text-gray-400 group-focus-within:text-[#d4af37]">
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path></svg>
                        </div>
                    </div>

                    <!-- Floating Label Input: Waktu -->
                    <div class="relative group">
                        <input type="datetime-local" id="waktu_kedatangan"
                            class="block rounded-2xl px-4 pb-2.5 pt-6 w-full text-sm text-gray-900 bg-gray-50/50 border border-gray-200 appearance-none focus:outline-none focus:ring-0 focus:border-[#d4af37] focus:bg-white peer shadow-sm transition-all font-semibold"
                            placeholder=" " required />
                        <label for="waktu_kedatangan" class="absolute text-xs text-gray-400 font-bold uppercase tracking-widest duration-300 transform -translate-y-3 scale-75 top-4 z-10 origin-[0] left-4 peer-placeholder-shown:scale-100 peer-placeholder-shown:translate-y-0 peer-focus:scale-75 peer-focus:-translate-y-3 peer-focus:text-[#d4af37]">Rencana Waktu Kedatangan</label>
                    </div>

                    <div class="pt-8">
                        <button type="button" onclick="submitWA()"
                            class="w-full group relative flex justify-center items-center gap-3 bg-gradient-to-r from-[#4a3614] via-[#674c1d] to-[#8b6f2f] hover:from-[#3a2a0f] hover:to-[#674c1d] text-white font-black py-4 px-6 rounded-2xl transition-all duration-300 shadow-xl shadow-[#d4af37]/30 hover:shadow-2xl hover:shadow-[#d4af37]/40 overflow-hidden active:scale-[0.98]">
                            <!-- Shine Effect -->
                            <div class="absolute inset-0 -translate-x-full group-hover:animate-[shimmer_1.5s_infinite] bg-gradient-to-r from-transparent via-white/20 to-transparent skew-x-12"></div>
                            
                            <svg class="w-6 h-6 relative z-10" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                            </svg>
                            <span class="relative z-10">Kirim Pengajuan via WhatsApp</span>
                        </button>
                        <p class="text-center text-xs text-gray-400 mt-4 font-medium">Pengajuan ini tidak mengikat sebelum ada kesepakatan di kantor cabang.</p>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function submitWA() {
            const nama = document.getElementById('nama').value;
            const no_hp = document.getElementById('no_hp').value;
            const cabang = document.getElementById('cabang').value;
            const waktu = document.getElementById('waktu_kedatangan').value;

            if (!cabang || !waktu) {
                alert('Mohon lengkapi cabang dan waktu kedatangan.');
                return;
            }

            const dateObj = new Date(waktu);
            const formattedDate = dateObj.toLocaleString('id-ID', { dateStyle: 'medium', timeStyle: 'short' });

            const phoneAdmin = "628139552626"; // Nomor WA Admin Majakara

            const itemDetail = `{{ $item->head_1 }} @if($item->head_2)({{ $item->head_2 }})@endif`;

            const message = `Halo Admin Majakara, saya ingin mengajukan Gadai Barang.%0A%0A` +
                `*Detail Pengajuan:*%0A` +
                `- Nama: ${nama}%0A` +
                `- No HP: ${no_hp}%0A` +
                `- Kategori: {{ $kategori->nama_kategori }}%0A` +
                `- Item Gadai: ${itemDetail}%0A` +
                `- Rencana Kedatangan: ${formattedDate}%0A` +
                `- Cabang Tujuan: ${cabang}%0A%0A` +
                `Mohon info proses selanjutnya. Terima kasih.`;

            const waUrl = `https://wa.me/${phoneAdmin}?text=${message}`;
            window.open(waUrl, '_blank');

            // Redirect back to index after opening WA
            setTimeout(() => {
                window.location.href = "{{ route('nasabah.gadai_baru.index') }}";
            }, 1000);
        }
    </script>
@endsection