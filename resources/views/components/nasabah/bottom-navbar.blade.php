@php
    $currentRoute = request()->route()->getName() ?? '';
@endphp

<nav id="bottomNavbar" class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 shadow-lg z-50 transition-transform duration-300">
    <div class="w-full px-2">
        <div class="flex items-center justify-around py-2">
            <!-- Home/Dashboard -->
            <a href="{{ route('nasabah.dashboard') }}" class="flex flex-col items-center justify-center space-y-1 px-3 py-2 rounded-lg transition-colors {{ $currentRoute === 'nasabah.dashboard' ? 'text-primary' : 'text-gray-600 hover:text-primary' }}">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"></path>
                </svg>
                <span class="text-xs font-medium">Dashboard</span>
            </a>
            
            <!-- Tabungan -->
            <a href="#" class="flex flex-col items-center justify-center space-y-1 px-3 py-2 rounded-lg transition-colors text-gray-600 hover:text-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <span class="text-xs font-medium">Tabungan</span>
            </a>
            
            <!-- Pinjaman -->
            <a href="#" class="flex flex-col items-center justify-center space-y-1 px-3 py-2 rounded-lg transition-colors text-gray-600 hover:text-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                <span class="text-xs font-medium">Pinjaman</span>
            </a>
            
            <!-- Guide -->
            <a href="#" class="flex flex-col items-center justify-center space-y-1 px-3 py-2 rounded-lg transition-colors text-gray-600 hover:text-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                </svg>
                <span class="text-xs font-medium">Guide</span>
            </a>
            
            <!-- Deposito -->
            <a href="#" class="flex flex-col items-center justify-center space-y-1 px-3 py-2 rounded-lg transition-colors text-gray-600 hover:text-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4"></path>
                </svg>
                <span class="text-xs font-medium">Deposito</span>
            </a>
            
            <!-- Gadai -->
            <a href="#" class="flex flex-col items-center justify-center space-y-1 px-3 py-2 rounded-lg transition-colors text-gray-600 hover:text-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path>
                </svg>
                <span class="text-xs font-medium">Gadai</span>
            </a>
            
            <!-- Settings -->
            <a href="#" class="flex flex-col items-center justify-center space-y-1 px-3 py-2 rounded-lg transition-colors text-gray-600 hover:text-primary">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                </svg>
                <span class="text-xs font-medium">Settings</span>
            </a>
        </div>
    </div>
</nav>

<script>
    // Hide/show bottom navbar on scroll
    let lastScrollTop = 0;
    let scrollTimeout;
    const bottomNavbar = document.getElementById('bottomNavbar');
    
    window.addEventListener('scroll', function() {
        const scrollTop = window.pageYOffset || document.documentElement.scrollTop;
        
        // Clear existing timeout
        clearTimeout(scrollTimeout);
        
        // Hide navbar when scrolling down
        if (scrollTop > lastScrollTop && scrollTop > 100) {
            bottomNavbar.style.transform = 'translateY(100%)';
        } else {
            // Show navbar when scrolling up
            bottomNavbar.style.transform = 'translateY(0)';
        }
        
        lastScrollTop = scrollTop <= 0 ? 0 : scrollTop;
        
        // Show navbar after scrolling stops
        scrollTimeout = setTimeout(function() {
            bottomNavbar.style.transform = 'translateY(0)';
        }, 150);
    });
</script>
