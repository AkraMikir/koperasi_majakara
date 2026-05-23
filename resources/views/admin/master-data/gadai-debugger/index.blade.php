@extends('layouts.admin')

@section('title', 'Gadai Debugger (Time Travel)')

@section('content')
<div class="space-y-6">

    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Gadai Debugger</h1>
            <p class="text-gray-500 mt-1">Simulasi perjalanan waktu (Time Travel) untuk menguji status dan denda Gadai.</p>
        </div>
        <a href="{{ route('admin.master-data.index') }}" class="px-4 py-2 bg-white border border-gray-200 text-gray-700 rounded-xl hover:bg-gray-50 transition-colors font-medium text-sm flex items-center gap-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path></svg>
            Kembali
        </a>
    </div>

    @if(session('success'))
        <div class="bg-green-50 border-l-4 border-green-500 p-4 rounded-xl">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-green-400" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm font-medium text-green-800">{{ session('success') }}</p>
                </div>
            </div>
        </div>
    @endif

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        
        <!-- Form Simulasi -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 lg:col-span-1">
            <div class="flex items-center gap-3 mb-6">
                <div class="w-10 h-10 bg-indigo-100 text-indigo-600 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-900">Simulasi Waktu Maju</h2>
                    <p class="text-xs text-gray-500">Uji perubahan status gadai</p>
                </div>
            </div>

            <form action="{{ route('admin.master-data.gadai-debugger.maju-hari') }}" method="POST">
                @csrf
                <div class="mb-5">
                    <label class="block text-sm font-medium text-gray-700 mb-2">Pilih jumlah hari maju:</label>
                    <div class="flex items-center gap-2">
                        <input type="number" name="days" min="1" max="365" value="1" class="w-24 px-4 py-2 border border-gray-300 rounded-xl focus:ring-indigo-500 focus:border-indigo-500 block text-center font-bold text-lg" required>
                        <span class="text-gray-500 font-medium">Hari</span>
                    </div>
                    <p class="text-xs text-gray-400 mt-2">Ini akan secara permanen mengubah tanggal jatuh tempo di database menjadi lebih lampau dan memicu proses cek status.</p>
                </div>
                <button type="submit" onclick="return confirm('Yakin ingin memajukan waktu sistem (Gadai)? Ini akan memicu denda jika ada yang lewat batas.')" class="w-full flex justify-center items-center gap-2 py-3 px-4 border border-transparent rounded-xl shadow-sm text-sm font-bold text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Simulasikan Sekarang
                </button>
            </form>

            @if(session('output'))
            <div class="mt-6 border-t border-gray-100 pt-6">
                <p class="text-sm font-bold text-gray-700 mb-2">Log Hasil Pengecekan Sistem:</p>
                <div class="bg-gray-900 text-green-400 p-4 rounded-xl text-xs font-mono whitespace-pre-wrap overflow-x-auto">
                    {{ session('output') }}
                </div>
            </div>
            @endif
        </div>

        <!-- Tabel Status Saat Ini -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden lg:col-span-2">
            <div class="px-6 py-4 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                <h3 class="font-bold text-gray-900">Kondisi Gadai Aktif Saat Ini</h3>
                <span class="px-2.5 py-1 bg-gray-200 text-gray-700 rounded-lg text-xs font-bold">{{ $gadaiList->count() }} Data</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-wider">ID / Slot</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Item</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Jatuh Tempo</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Batas Tenggang</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-black text-gray-500 uppercase tracking-wider">Status</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        @forelse($gadaiList as $gadai)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-bold text-gray-900">#{{ $gadai->id }}</div>
                                <div class="text-xs text-gray-500">{{ $gadai->slot_kode }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 font-medium">{{ $gadai->item->nama_item ?? 'Item' }}</div>
                                <div class="text-xs font-bold text-gray-500">Rp {{ number_format($gadai->nominal_deal, 0, ',', '.') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm {{ $gadai->tgl_jatuh_tempo < now() ? 'text-red-600 font-bold' : 'text-gray-900' }}">
                                    {{ $gadai->tgl_jatuh_tempo->format('d M Y, H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm {{ $gadai->tgl_tenggang < now() ? 'text-red-600 font-bold' : 'text-gray-900' }}">
                                    {{ $gadai->tgl_tenggang->format('d M Y, H:i') }}
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @if($gadai->status == 'active')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-green-100 text-green-800">AKTIF</span>
                                @elseif($gadai->status == 'grace_period')
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-red-100 text-red-800">MASA TENGGANG</span>
                                @else
                                    <span class="px-3 py-1 inline-flex text-xs leading-5 font-bold rounded-full bg-gray-100 text-gray-800">{{ strtoupper($gadai->status) }}</span>
                                @endif
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="5" class="px-6 py-10 text-center text-gray-500 text-sm font-medium">Belum ada gadai yang aktif.</td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

    </div>
</div>
@endsection
