@extends('layouts.admin')

@section('title', 'Master OTP Default')

@section('content')
<div class="space-y-6 animate-fade-in">
    <!-- Page Header -->
    <div class="flex items-center justify-between border-b border-gray-100 pb-5">
        <div>
            <div class="flex items-center gap-3 mb-1">
                <a href="{{ route('admin.master-data.index') }}" class="text-gray-400 hover:text-red-600 transition-colors">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                    </svg>
                </a>
                <h1 class="text-3xl font-bold text-gray-900 font-display">Master OTP Default</h1>
            </div>
            <p class="text-gray-600 mt-1 ml-8">Konfigurasi kode OTP default untuk fallback darurat ketika pengiriman Email terkendala</p>
        </div>
        <div class="flex items-center gap-2.5 px-4 py-2 bg-white border border-gray-100 rounded-xl shadow-sm">
            <span class="relative flex h-2.5 w-2.5">
                <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-red-400 opacity-75"></span>
                <span class="relative inline-flex rounded-full h-2.5 w-2.5 bg-red-500"></span>
            </span>
            <span class="text-xs text-gray-600 font-bold uppercase tracking-wider">Fallback Mode</span>
        </div>
    </div>

    <!-- Alert Messages -->
    @if(session('success'))
    <div class="bg-green-50 border-l-4 border-green-500 rounded-r-xl p-4 shadow-sm flex items-center gap-3">
        <svg class="w-6 h-6 text-green-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-green-700 font-semibold">{{ session('success') }}</p>
    </div>
    @endif

    @if(session('error'))
    <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 shadow-sm flex items-center gap-3">
        <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
        </svg>
        <p class="text-red-700 font-semibold">{{ session('error') }}</p>
    </div>
    @endif

    @if ($errors->any())
    <div class="bg-red-50 border-l-4 border-red-500 rounded-r-xl p-4 shadow-sm">
        <div class="flex items-center gap-3 mb-2">
            <svg class="w-6 h-6 text-red-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L4.082 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
            <p class="text-red-700 font-semibold">Terdapat kesalahan input:</p>
        </div>
        <ul class="list-disc list-inside text-red-600 text-sm ml-9 space-y-1">
            @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
            @endforeach
        </ul>
    </div>
    @endif

    <!-- Content Grid -->
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
        <!-- Configuration Card -->
        <div class="lg:col-span-1 space-y-6">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6 space-y-6">
                <div>
                    <h3 class="text-lg font-bold text-gray-900 mb-1 font-display">Pengaturan Kode Default</h3>
                    <p class="text-xs text-gray-500 leading-relaxed">Masukkan 6 digit kode OTP default baru. Kode ini akan di-hash sebelum disimpan ke database agar tetap aman.</p>
                </div>

                <form method="POST" action="{{ route('admin.master-data.otp-default.update') }}" class="space-y-4">
                    @csrf
                    <div>
                        <label for="otp_code" class="block text-xs font-bold text-gray-700 uppercase tracking-wider mb-2">Kode OTP Default (6 Digit)</label>
                        <input type="text" name="otp_code" id="otp_code" maxlength="6" pattern="[0-9]{6}" required
                            class="w-full px-4 py-3 bg-gray-50 border border-gray-200 rounded-xl focus:bg-white focus:border-red-500 focus:ring-1 focus:ring-red-500 transition-all font-mono text-center text-2xl tracking-widest font-bold"
                            placeholder="******">
                    </div>
                    <button type="submit"
                        class="w-full inline-flex items-center justify-center gap-2 px-5 py-3 bg-gradient-to-r from-red-600 to-red-700 text-white rounded-xl font-semibold hover:from-red-700 hover:to-red-800 transition-all shadow-sm">
                        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Simpan Kode
                    </button>
                </form>
            </div>

            <!-- Stats Card -->
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm p-6">
                <div class="flex items-center gap-4">
                    <div class="w-12 h-12 bg-red-50 rounded-xl flex items-center justify-center text-red-600">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 002 2h2a2 2 0 002-2"></path>
                        </svg>
                    </div>
                    <div>
                        <p class="text-2xl font-bold text-red-600 font-display">{{ $masterOtp ? $masterOtp->used : 0 }}</p>
                        <p class="text-xs text-gray-500 font-medium">Total Penggunaan OTP Fallback</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Log List Card -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-2xl border border-gray-100 shadow-sm overflow-hidden flex flex-col justify-between h-full">
                <div>
                    <div class="px-6 py-5 border-b border-gray-50 flex items-center justify-between">
                        <div>
                            <h3 class="text-lg font-bold text-gray-900 font-display">Log Penggunaan OTP Fallback</h3>
                            <p class="text-xs text-gray-500">Daftar riwayat verifikasi OTP yang sukses melewati bypass default code</p>
                        </div>
                    </div>

                    <div class="overflow-x-auto">
                        <table class="w-full text-left border-collapse">
                            <thead>
                                <tr class="bg-gray-50 text-gray-600 text-xs font-bold uppercase tracking-wider border-b border-gray-100">
                                    <th class="px-6 py-4">Waktu</th>
                                    <th class="px-6 py-4">Nomor HP</th>
                                    <th class="px-6 py-4">Tipe</th>
                                    <th class="px-6 py-4">Nasabah / User</th>
                                    <th class="px-6 py-4">Session ID</th>
                                </tr>
                            </thead>
                            <tbody class="divide-y divide-gray-50 text-sm text-gray-700">
                                @forelse($logs as $log)
                                <tr>
                                    <td class="px-6 py-4 font-mono text-xs whitespace-nowrap text-gray-500">
                                        {{ $log->created_at->format('d/m/Y H:i:s') }}
                                    </td>
                                    <td class="px-6 py-4 font-mono font-semibold whitespace-nowrap">
                                        {{ $log->phone_number }}
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="px-2 py-1 rounded-lg text-xs font-semibold
                                            @if($log->type == 'registration') bg-blue-50 text-blue-700 border border-blue-100
                                            @elseif($log->type == 'password_reset') bg-yellow-50 text-yellow-700 border border-yellow-100
                                            @elseif($log->type == 'pin') bg-indigo-50 text-indigo-700 border border-indigo-100
                                            @else bg-gray-50 text-gray-700 border border-gray-100 @endif border">
                                            {{ $log->type }}
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        @if($log->user)
                                            <div class="flex items-center gap-2">
                                                <span class="font-semibold text-gray-900">{{ $log->user->nama }}</span>
                                                <span class="text-xs text-gray-400 font-mono">({{ $log->user->email }})</span>
                                            </div>
                                        @else
                                            <span class="text-gray-400 italic">Guest (Registrasi)</span>
                                        @endif
                                    </td>
                                    <td class="px-6 py-4 font-mono text-xs text-gray-400 whitespace-nowrap max-w-xs truncate" title="{{ $log->session_id }}">
                                        {{ $log->session_id ?: '-' }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="5" class="px-6 py-10 text-center text-gray-400">
                                        <svg class="w-12 h-12 mx-auto text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                        </svg>
                                        Belum ada data log penggunaan fallback OTP.
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>

                @if($logs->hasPages())
                <div class="px-6 py-4 border-t border-gray-50">
                    {{ $logs->links() }}
                </div>
                @endif
            </div>
        </div>
    </div>
</div>
@endsection
