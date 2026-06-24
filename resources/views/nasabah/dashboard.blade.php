@extends('layouts.nasabah')

@section('title', 'Dashboard')

@section('content')
    <div class="w-full pb-20 px-4 sm:px-6 lg:px-8 max-w-[1600px] mx-auto animate-fade-in" x-data="{ showBalance: false }">
        <!-- Personalized Greeting / Premium Member Card -->
        <div class="relative w-full rounded-[2rem] overflow-hidden mb-6 shadow-2xl mt-4 group">
            <!-- Background Gradients & Effects -->
            <div class="absolute inset-0 bg-gradient-to-br from-majakara-dark-gold via-[#4A3728] to-majakara-brown"></div>
            <!-- Glassy overlays and noise (simulated) -->
            <div class="absolute top-0 right-0 w-96 h-96 bg-white/10 rounded-full blur-3xl -translate-y-1/2 translate-x-1/3"></div>
            <div class="absolute bottom-0 left-0 w-64 h-64 bg-majakara-gold/20 rounded-full blur-3xl translate-y-1/3 -translate-x-1/4"></div>
            <!-- Diagonal lines pattern -->
            <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,0.5) 10px, rgba(255,255,255,0.5) 11px);"></div>

            <div class="relative z-10 p-6 md:p-8 flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
                
                <!-- User Info -->
                <div class="flex items-center gap-4 w-full lg:w-auto">
                    <div class="w-16 h-16 rounded-full border-2 border-majakara-gold/50 p-1 flex-shrink-0 bg-white/10 backdrop-blur-sm shadow-[0_0_15px_rgba(212,175,55,0.3)]">
                        @php
                            $nama = auth()->user()->nama ?? 'Anggota';
                            $initials = collect(explode(' ', $nama))->map(fn($w) => substr($w, 0, 1))->take(2)->join('');
                        @endphp
                        @if(auth()->user()->foto && auth()->user()->foto !== 'default-avatar.jpg' && \Illuminate\Support\Facades\Storage::disk('public')->exists(auth()->user()->foto))
                            <img src="{{ asset('storage/' . auth()->user()->foto) }}" alt="Foto Profil" class="w-full h-full rounded-full object-cover shadow-inner">
                        @else
                            <div class="w-full h-full rounded-full bg-gradient-to-br from-majakara-gold to-majakara-dark-gold flex items-center justify-center text-white font-bold text-xl shadow-inner">
                                {{ strtoupper($initials) }}
                            </div>
                        @endif
                    </div>
                    <div>

                        <p class="text-xs md:text-sm font-medium text-white/70 tracking-[0.2em] uppercase mb-1">Selamat Datang,</p>

                        <h1 class="text-2xl md:text-4xl font-bold font-display text-white drop-shadow-md">{{ $nama }}</h1>
                        <p class="text-[10px] md:text-xs text-white/50 mt-1 font-mono tracking-widest">NO. ANGGOTA : {{ auth()->user()->id }}</p>
                    </div>
                </div>
                
                <!-- Financial Summary Container -->
                <div class="bg-white/10 backdrop-blur-xl rounded-[1.5rem] border border-white/20 p-5 flex flex-col sm:flex-row items-center gap-4 sm:gap-6 divide-y sm:divide-y-0 sm:divide-x divide-white/10 shadow-[inset_0_1px_1px_rgba(255,255,255,0.2)] w-full lg:w-auto relative group-hover:bg-white/15 transition-colors duration-500">
                    
                    <!-- Total Saldo Aktif -->
                    <div class="flex-1 w-full sm:w-auto sm:min-w-[170px] pt-1 sm:pt-0 sm:pr-2 flex flex-col justify-center">
                        <div class="flex items-center justify-between mb-1.5">
                            <p class="text-[10px] text-white/80 uppercase tracking-widest font-bold">Total Saldo Aktif</p>
                            <button @click="showBalance = !showBalance" class="text-white/60 hover:text-white transition-colors cursor-pointer" title="Sembunyikan/Tampilkan Saldo">
                                <svg x-show="!showBalance" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.88 9.88l-3.29-3.29m7.532 7.532l3.29 3.29M3 3l3.59 3.59m0 0A9.953 9.953 0 0112 5c4.478 0 8.268 2.943 9.543 7a10.025 10.025 0 01-4.132 5.411m0 0L21 21"></path></svg>
                                <svg x-show="showBalance" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24" style="display: none;"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path></svg>
                            </button>
                        </div>
                        @php
                            $totalSaldo = ($stats['saldo_tabungan'] ?? 0) + ($stats['total_deposito'] ?? 0);
                        @endphp
                        <div class="h-8 flex items-center">
                            <p x-show="showBalance" class="text-xl md:text-2xl font-black font-display tracking-tight text-white drop-shadow-sm" style="display: none;">
                                <span class="text-sm md:text-base font-bold text-majakara-gold">Rp</span> {{ number_format($totalSaldo, 0, ',', '.') }}
                            </p>
                            <p x-show="!showBalance" class="text-xl md:text-2xl font-black font-display tracking-[0.2em] text-white/90 drop-shadow-sm flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
                            </p>
                        </div>
                    </div>
                
                    <!-- Total Kewajiban -->
                    <div class="flex-1 w-full sm:w-auto sm:min-w-[170px] pt-4 sm:pt-0 sm:pl-6 flex flex-col justify-center relative">
                        <!-- Tiny decorative indicator for Liabilities -->
                        <div class="absolute right-0 top-1/2 -translate-y-1/2 w-1.5 h-6 bg-red-400/50 rounded-full hidden sm:block"></div>
                        <div class="flex items-center justify-between mb-1.5">
                            <p class="text-[10px] text-white/80 uppercase tracking-widest font-bold">Total Kewajiban</p>
                        </div>
                        @php
                            $totalKewajiban = ($stats['total_pinjaman'] ?? 0) + ($stats['total_gadai'] ?? 0);
                        @endphp
                        <div class="h-8 flex items-center">
                            <p x-show="showBalance" class="text-xl md:text-2xl font-black font-display tracking-tight text-white drop-shadow-sm" style="display: none;">
                                <span class="text-sm md:text-base font-bold text-red-300">Rp</span> {{ number_format($totalKewajiban, 0, ',', '.') }}
                            </p>
                            <p x-show="!showBalance" class="text-xl md:text-2xl font-black font-display tracking-[0.2em] text-white/90 drop-shadow-sm flex items-center gap-1.5">
                                <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
                                <span class="w-2.5 h-2.5 rounded-full bg-white"></span>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        @if(session('error'))
        <!-- Error Session Flash Alert -->
        <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 md:p-5 flex items-start gap-4 shadow-sm animate-fade-in relative z-20">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-red-800 mb-1">Akses Dibatasi</h3>
                <p class="text-xs text-red-600 leading-relaxed">{{ session('error') }}</p>
            </div>
        </div>
        @endif

        @if(session('success'))
        <!-- Success Session Flash Alert -->
        <div class="mb-6 bg-green-50 border border-green-200 rounded-2xl p-4 md:p-5 flex items-start gap-4 shadow-sm animate-fade-in relative z-20">
            <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-green-800 mb-1">Berhasil</h3>
                <p class="text-xs text-green-600 leading-relaxed">{{ session('success') }}</p>
            </div>
        </div>
        @endif

        @if(isset($bankInfo) && !$bankInfo['allowed'])
        <!-- Premium Feature Lock Banner -->
        <div class="mb-6 bg-red-50 border border-red-200 rounded-2xl p-4 md:p-5 flex items-start gap-4 shadow-sm animate-fade-in relative z-20">
            <div class="w-10 h-10 rounded-full bg-red-100 flex items-center justify-center shrink-0">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>
            </div>
            <div>
                <h3 class="text-sm font-bold text-red-800 mb-1">Akses Fitur Dibatasi</h3>
                <p class="text-xs text-red-600 leading-relaxed">{{ $bankInfo['reason'] }}</p>
                <div class="mt-3">
                    <a href="{{ route('nasabah.tabungan.nabung-sekarang') }}" class="inline-flex items-center gap-1.5 text-xs font-bold text-red-700 hover:text-red-800 bg-white px-3 py-1.5 rounded-lg border border-red-200 shadow-sm transition-colors">
                        Setor Tabungan
                        <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3"></path></svg>
                    </a>
                </div>
            </div>
        </div>
        @endif

        <!-- Quick Access (Akses Cepat) - Full Width Grid -->
        <div class="mb-8 relative z-20">
            <div class="flex items-center justify-between mb-3 px-2">
                <h2 class="text-sm font-bold font-display text-gray-900 uppercase tracking-wider">Akses Cepat</h2>
            </div>
            <div class="grid grid-cols-4 md:grid-cols-8 gap-3 md:gap-4">
                @php
                    $quickAccess = [
                        ['route' => 'nasabah.tabungan.nabung-sekarang', 'label' => 'Nabung', 'is_premium' => false, 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['route' => 'nasabah.tabungan.penarikan', 'label' => 'Tarik', 'is_premium' => false, 'icon' => 'M15 13l-3 3m0 0l-3-3m3 3V8m0 13a9 9 0 110-18 9 9 0 010 18z'],
                        ['route' => 'nasabah.pinjaman.pembayaran', 'label' => 'Bayar', 'is_premium' => true, 'icon' => 'M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2'],
                        ['route' => 'nasabah.pinjaman.angsuran', 'params' => ['jenis' => 'bulanan'], 'label' => 'Angsuran', 'is_premium' => true, 'icon' => 'M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['route' => 'nasabah.deposito.pengajuan', 'label' => 'Deposito', 'is_premium' => true, 'icon' => 'M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z'],
                        ['route' => 'nasabah.gadai_baru.index', 'label' => 'Gadai', 'is_premium' => true, 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                        ['route' => 'nasabah.pengajuan-pending', 'label' => 'Status', 'is_premium' => false, 'icon' => 'M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z'],
                        ['route' => 'nasabah.pinjaman.pinjaman-aktif', 'label' => 'Pinjaman', 'is_premium' => true, 'icon' => 'M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z'],
                    ];
                @endphp

                @foreach($quickAccess as $item)
                @php
                    $isVerified = auth()->user() && auth()->user()->verified !== null;
                    $isPremiumLocked = isset($item['is_premium']) && $item['is_premium'] && isset($bankInfo) && !$bankInfo['allowed'];
                    $isUnverifiedLocked = !$isVerified && $item['label'] !== 'Status';
                    $isDisabled = $isPremiumLocked || $isUnverifiedLocked;
                    
                    $lockTitle = 'Akses dibatasi';
                    if ($isUnverifiedLocked) {
                        $lockTitle = 'Akun Anda belum diverifikasi oleh admin.';
                    } elseif ($isPremiumLocked) {
                        $lockTitle = $bankInfo['reason'];
                    }
                @endphp
                @if($isDisabled)
                <div class="flex flex-col items-center gap-2 cursor-not-allowed opacity-60 group relative" title="{{ $lockTitle }}">
                    <div class="w-14 h-14 md:w-16 md:h-16 rounded-[1.25rem] bg-gray-50 border border-gray-200 flex items-center justify-center text-gray-400 relative overflow-hidden">
                        <svg class="w-6 h-6 md:w-7 md:h-7 relative z-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"></path>
                        </svg>
                        <!-- Lock Icon overlay -->
                        <div class="absolute inset-0 bg-white/40 backdrop-blur-[1px] flex items-center justify-center z-20">
                            <i class="fas fa-lock text-gray-600 drop-shadow-md text-lg"></i>
                        </div>
                    </div>
                    <span class="text-[10px] md:text-xs font-bold text-gray-400 text-center leading-tight truncate w-full">{{ $item['label'] }}</span>
                </div>
                @else
                <a href="{{ route($item['route'], $item['params'] ?? []) }}" class="flex flex-col items-center gap-2 group">
                    <div class="w-14 h-14 md:w-16 md:h-16 rounded-[1.25rem] bg-white border border-gray-100 shadow-[0_4px_10px_-2px_rgba(0,0,0,0.05)] flex items-center justify-center text-majakara-brown group-hover:bg-gradient-to-br group-hover:from-majakara-brown group-hover:to-majakara-dark-gold group-hover:text-white group-hover:border-transparent group-hover:shadow-[0_10px_20px_-5px_rgba(103,76,29,0.3)] transition-all duration-300 transform group-hover:-translate-y-1 relative overflow-hidden">
                        <div class="absolute inset-0 bg-white/20 translate-y-full group-hover:translate-y-0 transition-transform duration-300 ease-out"></div>
                        <svg class="w-6 h-6 md:w-7 md:h-7 relative z-10" fill="none" stroke="currentColor" stroke-width="2" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"></path>
                        </svg>
                    </div>
                    <span class="text-[10px] md:text-xs font-bold text-gray-600 group-hover:text-majakara-dark-gold transition-colors text-center leading-tight truncate w-full">{{ $item['label'] }}</span>
                </a>
                @endif
                @endforeach
            </div>
        </div>

        <!-- Main Split Dashboard Content -->
        <div class="grid grid-cols-1 lg:grid-cols-12 gap-6 mb-8">
            
            <!-- Asset Distribution -->
            <div class="lg:col-span-7 bg-white rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 flex flex-col relative overflow-hidden">
                <!-- Decorative background elements -->
                <div class="absolute -top-10 -right-10 w-40 h-40 bg-majakara-gold/5 rounded-full blur-2xl pointer-events-none"></div>
                
                <div class="flex items-center justify-between mb-6 relative z-10">
                    <div>
                        <h2 class="text-lg font-bold font-display text-gray-900 flex items-center gap-2">
                            Portofolio Aktivitas Koperasi
                            <span class="flex h-2 w-2 rounded-full bg-green-500 animate-pulse"></span>
                        </h2>
                        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest font-bold">Portofolio Keuangan</p>
                    </div>
                </div>
                
                <div class="flex-1 flex flex-col sm:flex-row items-center gap-6 sm:gap-8 relative z-10">
                    <!-- Chart -->
                    <div class="relative w-48 h-48 sm:w-56 sm:h-56 flex-shrink-0">
                        <canvas id="assetChart"></canvas>
                        <!-- Center text -->
                        <div class="absolute inset-0 flex flex-col items-center justify-center pointer-events-none">
                            <p class="text-[9px] font-black text-gray-400 uppercase tracking-[0.2em] mb-0.5">Total</p>
                            <h4 class="text-xl sm:text-2xl font-black text-majakara-dark-gold font-display">100%</h4>
                        </div>
                    </div>
                    
                    <!-- Legend List -->
                    <div class="flex-1 w-full space-y-3">
                        @php
                            $chartItems = [
                                ['label' => 'Tabungan', 'value' => $stats['saldo_tabungan'], 'color' => '#674c1d', 'icon' => 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z'],
                                ['label' => 'Deposito', 'value' => $stats['total_deposito'], 'color' => '#d4af37', 'icon' => 'M2 19l10-10 4 4 6-6m0 0v6m0-6h6'],
                                ['label' => 'Pinjaman', 'value' => $stats['total_pinjaman'], 'color' => '#8b6f2f', 'icon' => 'M2 10a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4zm0 0V6a2 2 0 012-2h14a2 2 0 012 2v4M6 12h.01M10 12h.01M14 12h.01M18 12h.01'],
                                ['label' => 'Gadai', 'value' => $stats['total_gadai'], 'color' => '#a67c52', 'icon' => 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z'],
                            ];
                            $totalVal = array_sum(array_column($chartItems, 'value'));
                        @endphp
                        @foreach($chartItems as $item)
                            @php
                                $percent = $totalVal > 0 ? ($item['value'] / $totalVal) * 100 : 0;
                            @endphp
                            <div class="group flex items-center justify-between p-2.5 rounded-xl hover:bg-gray-50 transition-colors border border-transparent hover:border-gray-100">
                                <div class="flex items-center gap-3">
                                    <div class="w-8 h-8 rounded-lg flex items-center justify-center shadow-sm" style="background-color: {{ $item['color'] }}15">
                                        <svg class="w-4 h-4" style="color: {{ $item['color'] }}" fill="none" stroke="currentColor" stroke-width="2.5" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" d="{{ $item['icon'] }}"></path>
                                        </svg>
                                    </div>
                                    <div>
                                        <span class="block text-xs font-bold text-gray-800 uppercase tracking-wide">{{ $item['label'] }}</span>
                                        <span class="text-[9px] font-semibold text-gray-400">{{ number_format($percent, 1) }}% Distribusi</span>
                                    </div>
                                </div>
                                <div class="text-right">
                                    <span class="block text-sm font-black text-gray-900 font-display" x-show="showBalance" style="display: none;">Rp {{ number_format($item['value'], 0, ',', '.') }}</span>
                                    <span class="block text-sm font-black text-gray-900 font-display tracking-widest" x-show="!showBalance">••••••</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            </div>

            <!-- Recent Transactions (List View) -->
            <div class="lg:col-span-5 bg-white rounded-[2rem] p-6 shadow-[0_8px_30px_rgb(0,0,0,0.04)] border border-gray-100 flex flex-col">
                <div class="flex items-center justify-between mb-6">
                    <div>
                        <h2 class="text-lg font-bold font-display text-gray-900">Aktivitas Terakhir</h2>
                        <p class="text-[10px] text-gray-400 mt-1 uppercase tracking-widest font-bold">Riwayat Transaksi</p>
                    </div>
                    <a href="{{ route('nasabah.tabungan.index') }}" class="text-[10px] font-bold text-majakara-dark-gold hover:text-majakara-brown uppercase tracking-wider flex items-center gap-1 group">
                        Lihat Semua
                        <svg class="w-3.5 h-3.5 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path></svg>
                    </a>
                </div>
                
                <div class="flex-1 overflow-y-auto pr-1 space-y-4 max-h-[280px]">
                    @forelse(collect($transaksiTerbaru ?? [])->take(5) as $transaksi)
                        @php
                            $isInflow = $transaksi->is_inflow;
                            $iconColor = '';
                            $iconBg = '';
                            $svgPath = '';
                            
                            switch($transaksi->icon_type) {
                                case 'tabungan':
                                    $iconColor = 'text-[#674c1d]';
                                    $iconBg = 'bg-[#674c1d]/10';
                                    $svgPath = 'M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z';
                                    break;
                                case 'deposito':
                                    $iconColor = 'text-[#d4af37]';
                                    $iconBg = 'bg-[#d4af37]/10';
                                    $svgPath = 'M2 19l10-10 4 4 6-6m0 0v6m0-6h6';
                                    break;
                                case 'pinjaman':
                                    $iconColor = 'text-[#8b6f2f]';
                                    $iconBg = 'bg-[#8b6f2f]/10';
                                    $svgPath = 'M2 10a2 2 0 012-2h14a2 2 0 012 2v4a2 2 0 01-2 2H4a2 2 0 01-2-2v-4zm0 0V6a2 2 0 012-2h14a2 2 0 012 2v4M6 12h.01M10 12h.01M14 12h.01M18 12h.01';
                                    break;
                                case 'gadai':
                                    $iconColor = 'text-[#a67c52]';
                                    $iconBg = 'bg-[#a67c52]/10';
                                    $svgPath = 'M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z';
                                    break;
                                default:
                                    $iconColor = 'text-gray-500';
                                    $iconBg = 'bg-gray-100';
                                    $svgPath = 'M9 5l7 7-7 7';
                            }
                        @endphp
                        <a href="{{ $transaksi->url }}" class="flex items-center justify-between group p-2 -mx-2 rounded-xl hover:bg-gray-50 transition-colors">
                            <div class="flex items-center gap-3">
                                <div class="w-10 h-10 rounded-full flex items-center justify-center shrink-0 border border-gray-100 {{ $iconBg }} {{ $iconColor }} group-hover:scale-110 transition-transform duration-300">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="{{ $svgPath }}"></path></svg>
                                </div>
                                <div>
                                    <h4 class="text-sm font-bold text-gray-800 group-hover:text-majakara-dark-gold transition-colors">{{ $transaksi->jenis }}</h4>
                                    <p class="text-[10px] text-gray-400 mt-0.5">{{ \Carbon\Carbon::parse($transaksi->tgl_transaksi)->format('d M Y • H:i') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                <span class="block text-sm font-black font-display {{ $isInflow ? 'text-green-600' : 'text-red-600' }}" x-show="showBalance" style="display: none;">
                                    {{ $isInflow ? '+' : '-' }}Rp {{ number_format($transaksi->nominal, 0, ',', '.') }}
                                </span>
                                <span class="block text-sm font-black font-display text-gray-400 tracking-widest" x-show="!showBalance">••••••</span>
                            </div>
                        </a>
                    @empty
                        <div class="flex flex-col items-center justify-center h-full text-center py-6">
                            <div class="w-16 h-16 bg-gray-50 rounded-full flex items-center justify-center text-gray-300 mb-3">
                                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                            </div>
                            <h3 class="text-sm font-bold text-gray-700">Belum Ada Transaksi</h3>
                            <p class="text-[10px] text-gray-400 mt-1 max-w-xs">Mulai bertransaksi untuk melihat riwayat aktivitas Anda di sini.</p>
                        </div>
                    @endforelse
                </div>
            </div>
            
        </div>

        <!-- Help & Customer Service Banner -->
        <div class="bg-gradient-to-r from-majakara-brown to-majakara-dark-gold rounded-[2rem] p-6 md:p-8 shadow-xl relative overflow-hidden flex flex-col sm:flex-row items-center justify-between gap-6 group">
            <!-- Decorative Background -->
            <div class="absolute inset-0 opacity-10" style="background-image: repeating-linear-gradient(45deg, transparent, transparent 10px, rgba(255,255,255,0.5) 10px, rgba(255,255,255,0.5) 11px);"></div>
            <div class="absolute -right-10 -top-10 w-64 h-64 bg-white/20 rounded-full blur-3xl group-hover:scale-110 transition-transform duration-700 pointer-events-none"></div>

            <div class="flex items-center gap-4 md:gap-5 relative z-10 w-full sm:w-auto">
                <div class="w-14 h-14 md:w-16 md:h-16 rounded-full bg-white/10 backdrop-blur-sm border border-white/20 flex items-center justify-center shrink-0 shadow-[inset_0_1px_1px_rgba(255,255,255,0.3)]">
                    <svg class="w-7 h-7 md:w-8 md:h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                </div>
                <div>
                    <h3 class="text-white font-bold font-display text-lg md:text-xl tracking-wide">Butuh Bantuan?</h3>
                    <p class="text-white/80 text-xs md:text-sm mt-1 max-w-md">Tim Layanan Anggota Kospin Majakara siap membantu segala kendala dan menjawab pertanyaan Anda.</p>
                </div>
            </div>

            <a href="https://wa.me/628139552626" target="_blank" class="relative z-10 w-full sm:w-auto inline-flex justify-center items-center gap-2.5 bg-white text-majakara-brown font-bold text-sm px-6 py-3.5 rounded-[1rem] shadow-lg hover:shadow-xl hover:bg-gray-50 hover:-translate-y-1 transition-all duration-300">
                <svg class="w-5 h-5 text-[#25D366]" fill="currentColor" viewBox="0 0 24 24"><path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51a12.8 12.8 0 00-.57-.01c-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z"/></svg>
                Hubungi CS Sekarang
            </a>
        </div>

    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const ctx = document.getElementById('assetChart').getContext('2d');
            
            // Get data from PHP
            const chartData = @json($stats['chart_data']['data']);
            const hasData = chartData.some(val => val > 0);
            
            new Chart(ctx, {
                type: 'doughnut',
                data: {
                    labels: ['Tabungan', 'Deposito', 'Pinjaman', 'Gadai'],
                    datasets: [{
                        data: hasData ? chartData : [1],
                        backgroundColor: hasData ? [
                            '#674c1d', // Tabungan (Brown)
                            '#d4af37', // Deposito (Gold)
                            '#8b6f2f', // Pinjaman (Darker Gold)
                            '#a67c52'  // Gadai (Light Brown)
                        ] : ['#f3f4f6'],
                        borderWidth: 0,
                        hoverOffset: 6,
                        borderRadius: hasData ? 4 : 0
                    }]
                },
                options: {
                    cutout: '75%',
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            display: false
                        },
                        tooltip: {
                            enabled: hasData,
                            backgroundColor: 'rgba(255, 255, 255, 0.98)',
                            titleColor: '#1f2937',
                            bodyColor: '#4b5563',
                            borderColor: 'rgba(212, 175, 55, 0.2)',
                            borderWidth: 1,
                            padding: 12,
                            boxPadding: 6,
                            usePointStyle: true,
                            callbacks: {
                                label: function(context) {
                                    return ' ' + context.label + ': ' + context.formattedValue + '%';
                                }
                            }
                        }
                    },
                    animation: {
                        animateScale: true,
                        animateRotate: true
                    }
                }
            });
        });
    </script>
@endpush
