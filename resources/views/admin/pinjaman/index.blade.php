@extends('layouts.admin')

@section('title', 'Dashboard Pinjaman')

@section('content')
<div class="space-y-6">
    <!-- Page Header -->
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Dashboard Pinjaman</h1>
            <p class="text-gray-600 mt-1">Kelola operasional, persetujuan, dan risiko pinjaman</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.pinjaman.pengajuan') }}" class="px-4 py-2 bg-white border border-gray-200 rounded-xl text-gray-700 hover:bg-[#674c1d]/5 hover:border-[#674c1d]/30 hover:text-[#674c1d] transition-all text-sm font-semibold shadow-sm flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                Pengajuan
            </a>
            <a href="{{ route('admin.pinjaman.angsuran') }}" class="px-4 py-2 bg-[#674c1d] text-white rounded-xl hover:bg-[#4a3514] transition-colors text-sm font-semibold shadow-md shadow-[#674c1d]/20 flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Pantau Angsuran
            </a>
        </div>
    </div>

    <!-- Statistics Cards -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-4">
        <!-- Pengajuan Pending -->
        <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 hover:border-[#674c1d]/30 transition-all cursor-pointer group" onclick="window.location.href='{{ route('admin.pinjaman.pengajuan') }}'">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-500 group-hover:text-gray-700 transition-colors">Pengajuan Pending</h3>
                <div class="w-8 h-8 bg-yellow-50 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_pengajuan_pending'] ?? 0, 0, ',', '.') }}</p>
                <span class="text-xs font-medium text-yellow-600 bg-yellow-100 px-2 py-1 rounded-md">Butuh Aksi</span>
            </div>
        </div>

        <!-- Pinjaman Aktif -->
        <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100 hover:border-[#8b6f2f]/30 transition-all cursor-pointer group" onclick="window.location.href='{{ route('admin.pinjaman.pinjaman-aktif') }}'">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-500 group-hover:text-gray-700 transition-colors">Pinjaman Aktif</h3>
                <div class="w-8 h-8 bg-[#8b6f2f]/10 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <p class="text-3xl font-bold text-gray-900">{{ number_format($stats['total_pinjaman_aktif'] ?? 0, 0, ',', '.') }}</p>
                <span class="text-xs font-medium text-gray-500 bg-gray-100 px-2 py-1 rounded-md">Nasabah Aktif</span>
            </div>
        </div>

        <!-- Total Nominal Pinjaman -->
        <div class="bg-white rounded-2xl shadow-sm p-5 border border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-medium text-gray-500">Total Outstanding</h3>
                <div class="w-8 h-8 bg-[#4a3514]/10 rounded-lg flex items-center justify-center">
                    <svg class="w-4 h-4 text-[#4a3514]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                </div>
            </div>
            <div>
                <p class="text-xl font-bold text-gray-900 truncate">Rp {{ number_format($stats['total_nominal_pinjaman_aktif'] ?? 0, 0, ',', '.') }}</p>
                <p class="text-xs text-gray-500 mt-1">Nilai pinjaman beredar</p>
            </div>
        </div>

        <!-- Angsuran Telat (High Priority) -->
        <div class="bg-white rounded-2xl shadow-sm p-5 border-2 {{ ($stats['total_angsuran_telat'] ?? 0) > 0 ? 'border-red-500 bg-red-50/30' : 'border-gray-100' }} hover:shadow-md transition-all cursor-pointer group" onclick="window.location.href='{{ route('admin.pinjaman.angsuran') }}?status=telat'">
            <div class="flex items-center justify-between mb-3">
                <h3 class="text-sm font-bold {{ ($stats['total_angsuran_telat'] ?? 0) > 0 ? 'text-red-700' : 'text-gray-500' }}">Angsuran Telat</h3>
                <div class="w-8 h-8 {{ ($stats['total_angsuran_telat'] ?? 0) > 0 ? 'bg-red-600' : 'bg-gray-100' }} rounded-full flex items-center justify-center animate-pulse">
                    <svg class="w-4 h-4 {{ ($stats['total_angsuran_telat'] ?? 0) > 0 ? 'text-white' : 'text-gray-400' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                    </svg>
                </div>
            </div>
            <div class="flex items-end justify-between">
                <p class="text-3xl font-bold {{ ($stats['total_angsuran_telat'] ?? 0) > 0 ? 'text-red-700' : 'text-gray-900' }}">{{ number_format($stats['total_angsuran_telat'] ?? 0, 0, ',', '.') }}</p>
                @if(($stats['total_angsuran_telat'] ?? 0) > 0)
                <span class="text-xs font-bold text-red-700 bg-red-100 px-2 py-1 rounded-md">Risiko NPL</span>
                @endif
            </div>
        </div>
    </div>

    <!-- Task-Oriented Main Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-12 gap-6">
        
        <!-- KOLOM KIRI: BUTUH PERSETUJUAN (Task/To-Do List) -->
        <div class="lg:col-span-7 space-y-6">
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100 bg-gray-50 flex items-center justify-between">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-white shadow-sm rounded-xl flex items-center justify-center">
                            <svg class="w-5 h-5 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                            </svg>
                        </div>
                        <div>
                            <h2 class="text-lg font-bold text-gray-900 font-display">Tugas: Butuh Persetujuan</h2>
                            <p class="text-xs text-gray-500">Pengajuan pinjaman baru yang menunggu ulasan</p>
                        </div>
                    </div>
                </div>
                
                <div class="p-0">
                    <ul class="divide-y divide-gray-100">
                        @forelse($pengajuan_terbaru ?? [] as $pengajuan)
                        <li class="p-4 hover:bg-[#674c1d]/5 transition-colors">
                            <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-4">
                                <div class="flex items-start gap-4">
                                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center shrink-0 border border-gray-200">
                                        <span class="text-gray-600 font-bold text-sm">{{ substr($pengajuan->nasabah->user->nama ?? 'N', 0, 1) }}</span>
                                    </div>
                                    <div>
                                        <h3 class="font-bold text-gray-900 text-sm">{{ $pengajuan->nasabah->user->nama ?? 'N/A' }}</h3>
                                        <div class="flex items-center gap-2 mt-1">
                                            <span class="text-xs font-semibold text-[#8b6f2f] bg-[#8b6f2f]/10 px-2 py-0.5 rounded">Rp {{ number_format($pengajuan->nominal, 0, ',', '.') }}</span>
                                            <span class="text-xs text-gray-500 flex items-center gap-1">
                                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                                {{ $pengajuan->created_at->diffForHumans() }}
                                            </span>
                                        </div>
                                    </div>
                                </div>
                                <div class="flex items-center gap-2 shrink-0">
                                    <!-- Inline Actions -->
                                    <form action="{{ route('admin.pinjaman.approve-pengajuan', $pengajuan->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 bg-green-50 text-green-600 hover:bg-green-600 hover:text-white rounded-lg flex items-center justify-center transition-colors" title="Setujui">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                                        </button>
                                    </form>
                                    <form action="{{ route('admin.pinjaman.reject-pengajuan', $pengajuan->id) }}" method="POST" class="inline">
                                        @csrf
                                        <button type="submit" class="w-8 h-8 bg-red-50 text-red-600 hover:bg-red-600 hover:text-white rounded-lg flex items-center justify-center transition-colors" title="Tolak">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                        </button>
                                    </form>
                                    <a href="{{ route('admin.pinjaman.detail-pengajuan', $pengajuan->id) }}" class="w-8 h-8 border border-gray-200 text-gray-600 hover:border-[#674c1d] hover:text-[#674c1d] rounded-lg flex items-center justify-center transition-colors" title="Lihat Detail">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                                    </a>
                                </div>
                            </div>
                        </li>
                        @empty
                        <li class="p-8 text-center">
                            <div class="inline-flex items-center justify-center w-12 h-12 rounded-full bg-green-50 mb-3">
                                <svg class="w-6 h-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-900">Semua Tugas Selesai</h3>
                            <p class="text-xs text-gray-500 mt-1">Tidak ada pengajuan pinjaman yang menunggu persetujuan.</p>
                        </li>
                        @endforelse
                    </ul>
                </div>
                @if(isset($pengajuan_terbaru) && count($pengajuan_terbaru) > 0)
                <div class="p-3 bg-gray-50 border-t border-gray-100 text-center">
                    <a href="{{ route('admin.pinjaman.pengajuan') }}" class="text-xs font-semibold text-[#674c1d] hover:underline">Lihat Semua Pengajuan</a>
                </div>
                @endif
            </div>

            <!-- Approval Pembayaran Terakhir -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden mt-6">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-900">Validasi Pembayaran Terbaru</h2>
                    <a href="{{ route('admin.pinjaman.pembayaran') }}" class="text-xs text-[#674c1d] font-semibold hover:underline">Kelola Semua</a>
                </div>
                <div class="p-0">
                    <ul class="divide-y divide-gray-100">
                        @forelse($pembayaran_terbaru ?? [] as $bayar)
                        <li class="p-4 hover:bg-[#674c1d]/5 transition-colors cursor-pointer" onclick="window.location.href='{{ route('admin.pinjaman.detail-pembayaran', $bayar->id) }}'">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-full bg-emerald-50 flex items-center justify-center border border-emerald-100">
                                        <svg class="w-4 h-4 text-emerald-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                                    </div>
                                    <div>
                                        <h3 class="text-sm font-semibold text-gray-900">{{ $bayar->nasabah->user->nama ?? 'N/A' }}</h3>
                                        <p class="text-xs text-gray-500">Rp {{ number_format($bayar->nominal, 0, ',', '.') }}</p>
                                    </div>
                                </div>
                                @php
                                    $statusLabels = ['1' => 'Pending', '2' => 'Ditolak', '3' => 'Disetujui', '4' => 'Terlaksana'];
                                    $statusClass = ['1' => 'bg-yellow-100 text-yellow-700 border-yellow-200', '2' => 'bg-red-100 text-red-700 border-red-200', '3' => 'bg-blue-100 text-blue-700 border-blue-200', '4' => 'bg-emerald-100 text-emerald-700 border-emerald-200'];
                                    $s = $bayar->status ?? '1';
                                @endphp
                                <span class="px-2.5 py-1 {{ $statusClass[$s] ?? $statusClass['1'] }} border rounded-full text-[10px] font-bold uppercase">{{ $statusLabels[$s] ?? 'Pending' }}</span>
                            </div>
                        </li>
                        @empty
                        <li class="p-6 text-center text-gray-500 text-sm">Tidak ada pembayaran terbaru</li>
                        @endforelse
                    </ul>
                </div>
            </div>
        </div>

        <!-- KOLOM KANAN: PANTAUAN RISIKO -->
        <div class="lg:col-span-5 space-y-6">
            
            <!-- Widget Pantauan Risiko (Angsuran Telat & Jatuh Tempo) -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-5 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-900 uppercase tracking-wider flex items-center gap-2">
                        <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
                        Pantauan Risiko
                    </h2>
                </div>
                
                @if(isset($angsuran_jatuh_tempo) && $angsuran_jatuh_tempo->count() > 0)
                <div class="p-4 bg-orange-50 border-b border-orange-100">
                    <h3 class="text-xs font-bold text-orange-800 uppercase tracking-wide mb-3">Jatuh Tempo Hari Ini</h3>
                    <div class="space-y-2">
                        @foreach($angsuran_jatuh_tempo as $item)
                        <div class="flex items-center justify-between p-3 bg-white rounded-xl shadow-sm border border-orange-100 hover:border-orange-300 transition-colors cursor-pointer" onclick="window.location.href='{{ route('admin.pinjaman.detail-angsuran', $item->id) }}'">
                            <div>
                                <p class="text-sm font-bold text-gray-900">{{ $item->nasabah->user->nama ?? 'N/A' }}</p>
                                <p class="text-xs text-gray-500">Angsuran #{{ $item->no_urut }}</p>
                            </div>
                            <div class="text-right">
                                <p class="text-sm font-bold text-orange-600">Rp {{ number_format($item->jumlah_tagihan, 0, ',', '.') }}</p>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>
                @else
                <div class="p-4 border-b border-gray-100 text-center bg-gray-50">
                    <p class="text-xs text-gray-500 font-medium">Tidak ada angsuran jatuh tempo hari ini.</p>
                </div>
                @endif
                
                <!-- If there are late installments, list top 3 here -->
                <div class="p-4">
                    <div class="flex items-center justify-between mb-3">
                        <h3 class="text-xs font-bold text-red-800 uppercase tracking-wide">Top Prioritas (Telat)</h3>
                        <a href="{{ route('admin.pinjaman.angsuran') }}?status=telat" class="text-[10px] text-red-600 font-bold uppercase hover:underline">Lihat Semua</a>
                    </div>
                    
                    @if(($stats['total_angsuran_telat'] ?? 0) > 0)
                    <!-- We use placeholder visual if actual late items aren't passed to view directly, just tell admin to check -->
                    <div class="p-4 bg-red-50 border border-red-100 rounded-xl flex items-start gap-3">
                        <svg class="w-5 h-5 text-red-500 shrink-0 mt-0.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        <div>
                            <p class="text-sm font-bold text-red-800">Terdapat {{ $stats['total_angsuran_telat'] }} Angsuran Bermasalah</p>
                            <p class="text-xs text-red-600 mt-1 mb-2">Sebagian portofolio pinjaman menunggak. Segera lakukan pengecekan dan penagihan.</p>
                            <a href="{{ route('admin.pinjaman.angsuran') }}?status=telat" class="inline-flex items-center justify-center px-3 py-1.5 bg-red-600 text-white text-xs font-bold rounded-lg hover:bg-red-700 transition-colors shadow-sm">
                                Buka Daftar Penagihan
                            </a>
                        </div>
                    </div>
                    @else
                    <div class="text-center py-6">
                        <div class="inline-flex items-center justify-center w-10 h-10 rounded-full bg-emerald-50 mb-2">
                            <svg class="w-5 h-5 text-emerald-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        </div>
                        <p class="text-xs font-medium text-emerald-600">Portofolio sangat sehat. Nol keterlambatan.</p>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Pinjaman Aktif Terbaru Mini List -->
            <div class="bg-white rounded-2xl shadow-sm border border-gray-200 overflow-hidden">
                <div class="p-4 border-b border-gray-100 flex items-center justify-between">
                    <h2 class="text-sm font-bold text-gray-900">Pencairan Terbaru</h2>
                </div>
                <div class="p-3">
                    <div class="space-y-2">
                        @forelse($pinjaman_aktif_terbaru ?? [] as $pinjaman)
                        <div class="flex items-center justify-between p-2 hover:bg-[#674c1d]/5 rounded-lg transition-colors cursor-pointer" onclick="window.location.href='{{ route('admin.pinjaman.detail-pinjaman', $pinjaman->id) }}'">
                            <div class="flex items-center gap-3">
                                <div class="w-8 h-8 rounded-full bg-[#8b6f2f]/10 flex items-center justify-center">
                                    <svg class="w-4 h-4 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                </div>
                                <div>
                                    <h3 class="text-xs font-bold text-gray-900">{{ $pinjaman->nasabah->user->nama ?? 'N/A' }}</h3>
                                    <p class="text-[10px] text-gray-500">{{ $pinjaman->created_at->format('d M') }}</p>
                                </div>
                            </div>
                            <span class="text-xs font-bold text-[#8b6f2f]">Rp {{ number_format($pinjaman->jumlah_pinjam, 0, ',', '.') }}</span>
                        </div>
                        @empty
                        <p class="text-xs text-center text-gray-500 py-4">Belum ada pencairan.</p>
                        @endforelse
                    </div>
                </div>
            </div>

        </div>
    </div>
</div>
@endsection
