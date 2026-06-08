@extends('layouts.admin')

@section('title', 'Master Jenis Via')

@section('content')
<div class="space-y-6">
    <div class="flex items-center justify-between">
        <div>
            <div class="flex items-center space-x-2 text-sm text-gray-600 mb-2">
                <a href="{{ route('admin.master-data.index') }}">Master Data</a>
                <span>/</span>
                <span class="text-gray-900 font-medium">Jenis Via</span>
            </div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Master Jenis Via</h1>
            <p class="text-gray-600 mt-1">Daftar metode/saluran transaksi sistem Koperasi Majakara</p>
        </div>
    </div>

    <div class="bg-white rounded-2xl shadow-md border border-gray-100">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-200">
                    <tr>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">No</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Kode</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Nama Via</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Deskripsi</th>
                        <th class="px-6 py-4 text-left text-xs font-semibold text-gray-700 uppercase">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    @forelse($data as $index => $item)
                    <tr class="hover:bg-gray-50">
                        <td class="px-6 py-4 text-sm text-gray-900">{{ $data->firstItem() + $index }}</td>
                        <td class="px-6 py-4 text-sm font-bold text-majakara-brown"><span class="px-2.5 py-1 bg-majakara-gold/10 rounded-lg text-xs">{{ $item->kode }}</span></td>
                        <td class="px-6 py-4 text-sm font-semibold text-gray-900">{{ $item->nama }}</td>
                        <td class="px-6 py-4 text-sm text-gray-600">{{ $item->deskripsi }}</td>
                        <td class="px-6 py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-medium {{ $item->is_active ? 'bg-green-100 text-green-700' : 'bg-gray-100 text-gray-700' }}">
                                {{ $item->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="5" class="px-6 py-12 text-center text-gray-500">Belum ada data</td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($data->hasPages())
        <div class="px-6 py-4 border-t">{{ $data->links() }}</div>
        @endif
    </div>
</div>
@endsection
