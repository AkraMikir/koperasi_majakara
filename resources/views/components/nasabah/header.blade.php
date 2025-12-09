@php
    // Frontend only - no auth required
    $currentDate = now()->locale('id')->isoFormat('D MMMM YYYY');
    $currentTime = now()->format('H.i.s');
@endphp

<header class="bg-white shadow-sm sticky top-0 z-40">
    <div class="container mx-auto px-4 py-2 max-w-7xl">
        <div class="flex items-center justify-between">
            <!-- Logo -->
            <div class="flex items-center space-x-2">
                <div class="w-10 h-10 gradient-primary rounded-lg flex items-center justify-center">
                    <span class="text-white font-bold text-xl">K</span>
                </div>
                <span class="text-xl font-bold text-primary hidden sm:block">Kospin Majakara</span>
            </div>
            
            <!-- Welcome Text -->
            <div class="flex-1 text-center mx-2">
                <h1 class="text-base sm:text-lg font-bold text-primary font-display">
                    SELAMAT DATANG DI KOPERASI MAJAKARA!!
                </h1>
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
