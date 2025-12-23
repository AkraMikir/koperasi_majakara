@php
    // Frontend only - no auth required
    $currentDate = now()->locale('id')->isoFormat('D MMMM YYYY');
    $currentTime = now()->format('H.i.s');
    $currentRoute = request()->route()->getName() ?? '';
    $isTabunganPage = str_contains($currentRoute, 'tabungan');
@endphp

<header class="bg-white shadow-sm sticky top-0 z-40">
    <div class="w-full px-4 py-2 max-w-full">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 gradient-primary rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-xl">K</span>
                </div>
                <span class="text-xl font-bold text-primary hidden sm:block">Kospin Majakara</span>
            </div>
            
            <!-- Welcome Text & Action Buttons -->
            <div class="flex-1 text-center mx-2">
                @if($isTabunganPage)
                    <!-- Tab Buttons for Tabungan Pages -->
                    <div class="flex items-center justify-center gap-3 flex-wrap">
                        <a href="{{ route('nasabah.tabungan.nabung-sekarang') }}" 
                            class="group relative px-6 py-3 {{ $currentRoute === 'nasabah.tabungan.nabung-sekarang' ? 'bg-gradient-to-r from-[#674c1d] to-[#4a3514] text-white shadow-lg' : 'bg-gradient-to-r from-[#8b6f2f]/10 to-[#d4af37]/10 text-[#674c1d] border-2 border-[#8b6f2f]/30 hover:from-[#8b6f2f]/20 hover:to-[#d4af37]/20' }} text-sm font-bold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center gap-2">
                            <div class="w-8 h-8 {{ $currentRoute === 'nasabah.tabungan.nabung-sekarang' ? 'bg-white/20' : 'bg-gradient-to-br from-[#674c1d] to-[#8b6f2f]' }} rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 {{ $currentRoute === 'nasabah.tabungan.nabung-sekarang' ? 'text-white' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path>
                                </svg>
                            </div>
                            <span>NABUNG SEKARANG</span>
                            @if($currentRoute === 'nasabah.tabungan.nabung-sekarang')
                                <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-3/4 h-1 bg-gradient-to-r from-[#d4af37] to-[#8b6f2f] rounded-full"></div>
                            @endif
                        </a>
                        <a href="{{ route('nasabah.tabungan.penarikan') }}" 
                            class="group relative px-6 py-3 {{ $currentRoute === 'nasabah.tabungan.penarikan' ? 'bg-gradient-to-r from-[#674c1d] to-[#4a3514] text-white shadow-lg' : 'bg-gradient-to-r from-[#8b6f2f]/10 to-[#d4af37]/10 text-[#674c1d] border-2 border-[#8b6f2f]/30 hover:from-[#8b6f2f]/20 hover:to-[#d4af37]/20' }} text-sm font-bold rounded-xl transition-all duration-300 shadow-md hover:shadow-lg transform hover:scale-105 flex items-center gap-2">
                            <div class="w-8 h-8 {{ $currentRoute === 'nasabah.tabungan.penarikan' ? 'bg-white/20' : 'bg-gradient-to-br from-[#674c1d] to-[#8b6f2f]' }} rounded-lg flex items-center justify-center">
                                <svg class="w-5 h-5 {{ $currentRoute === 'nasabah.tabungan.penarikan' ? 'text-white' : 'text-white' }}" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"></path>
                                </svg>
                            </div>
                            <span>PENARIKAN TABUNGAN</span>
                            @if($currentRoute === 'nasabah.tabungan.penarikan')
                                <div class="absolute -bottom-1 left-1/2 transform -translate-x-1/2 w-3/4 h-1 bg-gradient-to-r from-[#d4af37] to-[#8b6f2f] rounded-full"></div>
                            @endif
                        </a>
                    </div>
                @else
                    <h1 class="text-base sm:text-lg font-bold text-primary font-display">
                        SELAMAT DATANG DI KOPERASI MAJAKARA!!
                    </h1>
                @endif
            </div>
            
            <!-- Right Side: Icons and Time -->
            <div class="flex items-center space-x-2">
                <!-- Notification Icon -->
                <button class="relative p-2 text-gray-600 hover:text-primary transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <span class="absolute top-0 right-0 w-2 h-2 bg-red-500 rounded-full"></span>
                </button>
                
                <!-- Profile/Settings Icon -->
                <a href="#" class="p-2 text-gray-600 hover:text-primary transition-colors">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                    </svg>
                </a>
                
                <!-- Date and Time -->
                <div class="hidden md:flex flex-col items-end text-right">
                    <span class="text-sm font-semibold text-gray-700" id="currentDate">{{ $currentDate }}</span>
                    <span class="text-xs text-gray-500 time-display">{{ $currentTime }}</span>
                </div>
            </div>
        </div>
    </div>
</header>
