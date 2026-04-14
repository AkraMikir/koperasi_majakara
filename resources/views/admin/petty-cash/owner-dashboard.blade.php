@extends('layouts.admin')

@section('title', 'Dashboard Petty Cash')

@section('content')
<div class="space-y-6">
    {{-- Page Header --}}
    <div class="flex items-center justify-between">
        <div>
            <h1 class="text-3xl font-bold text-gray-900 font-display">Dashboard Petty Cash (Owner)</h1>
            <p class="text-gray-600 mt-1">Helicopter View: Monitoring aliran dana operasional koperasi</p>
        </div>
        <div class="flex items-center space-x-3">
            <a href="{{ route('admin.petty-cash.penerimaan.create') }}"
               class="px-4 py-2 bg-[#674c1d] text-white rounded-lg text-sm font-medium hover:bg-[#4a3514] transition-colors flex items-center gap-2">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                </svg>
                Kirim Dana ke Admin
            </a>
            <a href="{{ route('admin.petty-cash.setoran-approval.index') }}"
               class="px-4 py-2 bg-white border border-gray-300 text-gray-700 rounded-lg text-sm font-medium hover:bg-gray-50 transition-colors">
                Verifikasi Setoran
            </a>
        </div>
    </div>

    @if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-xl flex items-center gap-3">
        <svg class="w-5 h-5 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"/>
        </svg>
        {{ session('success') }}
    </div>
    @endif

    {{-- Stats Cards --}}
    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        {{-- Saldo Owner --}}
        <div class="bg-gradient-to-br from-[#674c1d] to-[#4a3514] rounded-2xl shadow-lg p-6 text-white">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-white/20 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"/>
                    </svg>
                </div>
                <span class="text-white/60 text-xs font-medium uppercase tracking-wider">Owner</span>
            </div>
            <p class="text-white/80 text-sm mb-1">Saldo Petty Cash Owner</p>
            <p class="text-3xl font-bold">Rp {{ number_format($saldoOwner, 0, ',', '.') }}</p>
        </div>

        {{-- Alert Setoran Pending --}}
        <a href="{{ route('admin.petty-cash.setoran-approval.index') }}"
           class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all {{ $pendingSetoran > 0 ? 'border-l-4 border-l-amber-500' : '' }}">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-amber-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                </div>
                @if($pendingSetoran > 0)
                <span class="px-3 py-1 bg-amber-100 text-amber-700 rounded-full text-xs font-bold">{{ $pendingSetoran }} Pending</span>
                @endif
            </div>
            <p class="text-gray-600 text-sm mb-1">Setoran Menunggu Verifikasi</p>
            <p class="text-3xl font-bold text-gray-900">{{ $pendingSetoran }}</p>
        </a>

        {{-- Laporan --}}
        <a href="{{ route('admin.petty-cash.laporan') }}"
           class="bg-white rounded-2xl shadow-md p-6 border border-gray-100 hover:shadow-lg transition-all">
            <div class="flex items-center justify-between mb-4">
                <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center">
                    <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                </div>
            </div>
            <p class="text-gray-600 text-sm mb-1">Laporan Lengkap</p>
            <p class="text-lg font-bold text-gray-900">Lihat Laporan →</p>
        </a>
    </div>

    {{-- Saldo Per Admin --}}
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <div class="flex items-center justify-between mb-6 pb-4 border-b border-gray-200">
            <div class="flex items-center space-x-3">
                <div class="w-10 h-10 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-xl flex items-center justify-center">
                    <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0z"/>
                    </svg>
                </div>
                <h2 class="text-lg font-bold text-[#674c1d] font-display">Saldo Petty Cash Per Admin Operasional</h2>
            </div>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            @forelse($admins as $admin)
            <div class="p-4 bg-gray-50 rounded-xl border border-gray-200 hover:border-[#674c1d]/30 transition-colors">
                <div class="flex items-center gap-3 mb-3">
                    <div class="w-10 h-10 bg-[#674c1d]/10 rounded-full flex items-center justify-center">
                        <span class="text-[#674c1d] font-bold text-sm">{{ strtoupper(substr($admin->nama, 0, 2)) }}</span>
                    </div>
                    <div>
                        <h3 class="font-semibold text-gray-900 text-sm">{{ $admin->nama }}</h3>
                        <p class="text-xs text-gray-500">Admin Operasional</p>
                    </div>
                </div>
                <p class="text-xl font-bold text-[#674c1d]">
                    Rp {{ number_format($admin->saldo_petty_cash, 0, ',', '.') }}
                </p>
                <div class="flex items-center justify-between mt-2 pt-2 border-t border-gray-100">
                    <div class="text-[10px] text-gray-500">
                        <span class="font-medium text-green-600">Cash:</span> Rp {{ number_format($admin->saldo_cash, 0, ',', '.') }}
                    </div>
                    <div class="text-[10px] text-gray-500">
                        <span class="font-medium text-blue-600">TF:</span> Rp {{ number_format($admin->saldo_tf, 0, ',', '.') }}
                    </div>
                </div>
                <div class="mt-3">
                     <a href="{{ route('admin.petty-cash.penerimaan.create', ['admin_id' => $admin->id]) }}" 
                        class="block w-full text-center px-3 py-1.5 bg-[#674c1d]/10 text-[#674c1d] rounded-lg text-xs font-semibold hover:bg-[#674c1d]/20 transition-colors">
                          Kirim Dana Ke Admin Ini
                     </a>
                </div>
            </div>
            @empty
            <div class="col-span-3 text-center py-8 text-gray-500">
                <p>Belum ada admin operasional terdaftar</p>
            </div>
            @endforelse
        </div>
    </div>

    {{-- Grafik 7 Hari --}}
    <div class="bg-white rounded-2xl shadow-md p-6 border border-gray-100">
        <div class="flex items-center space-x-3 mb-6 pb-4 border-b border-gray-200">
            <div class="w-10 h-10 bg-gradient-to-br from-[#4a3514] to-[#674c1d] rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-[#674c1d] font-display">Penerimaan vs Setoran (7 Hari Terakhir)</h2>
        </div>

        <div class="overflow-x-auto">
            <table class="w-full text-sm">
                <thead>
                    <tr class="border-b-2 border-[#674c1d]/20 bg-gradient-to-r from-[#674c1d]/5 to-[#8b6f2f]/5">
                        <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">Tanggal</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-green-700 uppercase">Kiriman Dana (Owner → Admin)</th>
                        <th class="px-4 py-3 text-right text-xs font-bold text-blue-700 uppercase">Setoran (Admin → Owner)</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($grafik as $row)
                    <tr class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                        <td class="px-4 py-3 font-medium">{{ \Carbon\Carbon::parse($row['tanggal'])->format('d M Y') }}</td>
                        <td class="px-4 py-3 text-right text-green-700 font-semibold">
                            Rp {{ number_format($row['penerimaan'], 0, ',', '.') }}
                        </td>
                        <td class="px-4 py-3 text-right text-blue-700 font-semibold">
                            Rp {{ number_format($row['setoran'], 0, ',', '.') }}
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    {{-- Detail Setoran Pending --}}
    @if($setoranPending->isNotEmpty())
    <div class="bg-amber-50 border border-amber-200 rounded-2xl p-6">
        <div class="flex items-center gap-3 mb-4">
            <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                <svg class="w-5 h-5 text-amber-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"/>
                </svg>
            </div>
            <h2 class="text-lg font-bold text-amber-900">{{ $pendingSetoran }} Setoran Menunggu Verifikasi Anda</h2>
        </div>
        <div class="space-y-3">
            @foreach($setoranPending as $s)
            <div class="flex items-center justify-between bg-white rounded-xl p-4 border border-amber-200 hover:shadow-sm transition-all">
                <div>
                    <p class="font-semibold text-gray-900">Admin {{ $s->admin->nama ?? '-' }}</p>
                    <p class="text-sm text-gray-600">{{ $s->jumlah_nasabah }} nasabah · {{ $s->tgl_setoran->format('d M Y') }}</p>
                </div>
                <div class="flex items-center gap-3">
                    <span class="font-bold text-[#674c1d]">Rp {{ number_format($s->total_setor, 0, ',', '.') }}</span>
                    <a href="{{ route('admin.petty-cash.setoran-approval.detail', $s->id) }}"
                       class="px-3 py-1.5 bg-[#674c1d] text-white rounded-lg text-sm hover:bg-[#4a3514] transition-colors">
                        Verifikasi
                    </a>
                </div>
            </div>
            @endforeach
        </div>
    </div>
    @endif
</div>
@endsection
