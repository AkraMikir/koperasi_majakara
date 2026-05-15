@extends('layouts.nasabah')

@section('title', 'Pengajuan Gadai')

@section('content')
    <div class="max-w-2xl mx-auto pb-6 mt-6">
        <div class="bg-white rounded-2xl shadow-xl border border-gray-100 overflow-hidden">
            <div class="bg-gray-50 px-6 py-4 border-b border-gray-100 flex items-center justify-between">
                <h2 class="text-lg font-bold text-gray-800">Form Pengajuan via WhatsApp</h2>
                <span
                    class="bg-blue-100 text-blue-800 text-xs font-semibold px-2.5 py-0.5 rounded">{{ $kategori->nama_kategori }}</span>
            </div>

            <div class="p-6">
                <div class="mb-6 bg-yellow-50 border border-yellow-100 rounded-3xl p-6 relative overflow-hidden">
                    <div class="flex flex-col md:flex-row gap-6 items-center">
                        @if($item->file_pic)
                        <div class="w-32 h-32 rounded-2xl overflow-hidden shadow-lg border-2 border-white flex-shrink-0">
                            <img src="{{ asset('storage/' . $item->file_pic) }}" class="w-full h-full object-cover">
                        </div>
                        @endif
                        <div class="flex-1">
                            <h3 class="font-black text-yellow-800 text-xl mb-1 tracking-tight">{{ $item->head_1 }}</h3>
                            @if($item->head_2)
                                <p class="text-sm text-yellow-700/70 mb-2 italic">{{ $item->head_2 }}</p>
                            @endif
                            <ul class="text-sm text-yellow-700 space-y-1 font-medium">
                                <li class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span>
                                    Range Taksiran: <strong class="text-yellow-900">Rp {{ number_format($item->nominal_low, 0, ',', '.') }} - {{ number_format($item->nominal_high, 0, ',', '.') }}</strong>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span>
                                    Biaya Jasa: <strong class="text-yellow-900">{{ $item->bunga_low }}% - {{ $item->bunga_high }}%</strong>
                                </li>
                                <li class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span>
                                    Denda Keterlambatan: <strong class="text-yellow-900">{{ $kategori->rate_denda }}%</strong>
                                </li>
                                @if($item->rate_inap_nominal > 0)
                                    <li class="flex items-center gap-2">
                                        <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span>
                                        Biaya Inap Kendaraan: <strong class="text-yellow-900">Rp {{ number_format($item->rate_inap_nominal, 0, ',', '.') }}</strong>
                                    </li>
                                @endif
                                <li class="flex items-center gap-2">
                                    <span class="w-1.5 h-1.5 rounded-full bg-yellow-400"></span>
                                    Masa Gadai: <strong class="text-yellow-900">{{ $kategori->masa_gadai_hari }} hari</strong>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>

                <form id="waForm" class="space-y-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
                        <input type="text" id="nama"
                            class="w-full border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ Auth::user()->nama }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Nomor HP / WhatsApp</label>
                        <input type="text" id="no_hp"
                            class="w-full border-gray-300 rounded-lg bg-gray-50 focus:ring-blue-500 focus:border-blue-500"
                            value="{{ Auth::user()->nomor_hp }}">
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Pilih Cabang (Lokasi Fisik
                            Barang)</label>
                        <select id="cabang"
                            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                            <option value="">-- Pilih Cabang --</option>
                            @foreach($lokasi as $loc)
                                <option value="{{ $loc->nama_lokasi }}">{{ $loc->nama_lokasi }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Rencana Waktu Kedatangan</label>
                        <input type="datetime-local" id="waktu_kedatangan"
                            class="w-full border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500" required>
                    </div>

                    <div class="pt-4">
                        <button type="button" onclick="submitWA()"
                            class="w-full flex justify-center items-center gap-2 bg-[#25D366] hover:bg-[#128C7E] text-white font-bold py-3 px-4 rounded-xl transition-colors shadow-lg shadow-green-200">
                            <svg class="w-6 h-6" fill="currentColor" viewBox="0 0 24 24">
                                <path
                                    d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
                                </svg>
                                Kirim Pengajuan via WhatsApp
                            </button>
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