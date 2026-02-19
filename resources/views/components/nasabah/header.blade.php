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
            <div class="flex items-center space-x-3">
                <img src="{{ asset('images/logo/logo_coklat.png') }}" alt="Logo" class="h-16 w-auto object-contain" style="mix-blend-mode: multiply; filter: brightness(1.1) contrast(1.2);">
                <span class="text-xl font-bold text-primary hidden sm:block">Kospin Majakara</span>
            </div>
            
            <!-- Welcome Text -->
            <div class="flex-1 text-center mx-2">
                <h1 class="text-base sm:text-lg font-bold text-primary font-display">
                @if($isTabunganPage)
                        TABUNGAN
                @else
                        SELAMAT DATANG DI KOPERASI MAJAKARA!!
                    @endif
                    </h1>
            </div>
            
            <!-- Right Side: Icons and Time -->
            <div class="flex items-center space-x-2">
                <!-- Notification (dropdown seperti admin + hover effect) -->
                @php
                    $nasabahNotificationsUnreadCount = $nasabahNotificationsUnreadCount ?? 0;
                    $nasabahNotificationsRecent = $nasabahNotificationsRecent ?? collect([]);
                @endphp
                <div class="relative group">
                    <a href="{{ route('nasabah.notifications.index') }}" class="relative inline-flex p-2 text-gray-600 hover:text-primary transition-colors rounded-lg hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                        </svg>
                        @if($nasabahNotificationsUnreadCount > 0)
                            <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 flex items-center justify-center bg-red-500 text-white text-xs font-bold rounded-full border-2 border-white">
                                {{ $nasabahNotificationsUnreadCount > 99 ? '99+' : $nasabahNotificationsUnreadCount }}
                            </span>
                        @endif
                    </a>
                    <!-- Dropdown preview on hover (sama seperti admin) -->
                    <div class="absolute right-0 mt-1 w-80 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-[100] opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 transform group-hover:translate-y-0 -translate-y-1">
                        <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
                            <span class="font-semibold text-gray-900">Notifikasi</span>
                            @if($nasabahNotificationsUnreadCount > 0)
                                <form method="POST" action="{{ route('nasabah.notifications.mark-all-read') }}" class="inline">
                                    @csrf
                                    <button type="submit" class="text-xs text-[#674c1d] hover:underline">Tandai semua dibaca</button>
                                </form>
                            @endif
                        </div>
                        <div class="max-h-80 overflow-y-auto">
                            @forelse($nasabahNotificationsRecent as $notif)
                                @php $targetUrl = $notif->link ?: route('nasabah.notifications.index'); @endphp
                                <form method="POST" action="{{ route('nasabah.notifications.mark-read', $notif->id) }}" class="block border-b border-gray-50 last:border-0 {{ $notif->read_at ? '' : 'bg-[#674c1d]/5' }}">
                                    @csrf
                                    <input type="hidden" name="redirect" value="{{ $targetUrl }}">
                                    <button type="submit" class="w-full text-left px-4 py-3 hover:bg-gray-100 transition-colors rounded-none">
                                        <p class="text-sm font-medium text-gray-900 line-clamp-1">{{ $notif->title }}</p>
                                        @if($notif->message)
                                            <p class="text-xs text-gray-500 mt-0.5 line-clamp-2">{{ $notif->message }}</p>
                                        @endif
                                        <p class="text-xs text-gray-400 mt-1">{{ $notif->created_at->diffForHumans() }}</p>
                                    </button>
                                </form>
                            @empty
                                <p class="px-4 py-6 text-sm text-gray-500 text-center">Tidak ada notifikasi</p>
                            @endforelse
                        </div>
                        <div class="px-4 py-2 border-t border-gray-100 text-center">
                            <a href="{{ route('nasabah.notifications.index') }}" class="text-sm font-medium text-[#674c1d] hover:underline">Lihat semua notifikasi</a>
                        </div>
                    </div>
                </div>
                
                <!-- Profile Dropdown -->
                <div class="relative group">
                    <button class="p-2 text-gray-600 hover:text-primary transition-colors focus:outline-none">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                    </button>
                    
                    <!-- Dropdown Menu -->
                    <div class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-xl border border-gray-200 opacity-0 invisible group-hover:opacity-100 group-hover:visible transition-all duration-200 z-50 transform group-hover:translate-y-0 -translate-y-2">
                        <div class="py-1">
                            <!-- Profile Link -->
                            <a href="{{ route('nasabah.profile') }}" class="block px-4 py-2.5 text-sm text-gray-700 hover:bg-[#674c1d] hover:text-white transition-colors">
                                <div class="flex items-center space-x-2">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                                    </svg>
                                    <span>Profile</span>
                                </div>
                            </a>
                            
                            <!-- Divider -->
                            <div class="border-t border-gray-200 my-1"></div>
                            
                            <!-- Logout Button -->
                            <form action="{{ route('logout') }}" method="POST" class="block">
                                @csrf
                                <button type="submit" class="w-full text-left px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors">
                                    <div class="flex items-center space-x-2">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"></path>
                                        </svg>
                                        <span>Logout</span>
                                    </div>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
                
                <!-- Date and Time -->
                <div class="hidden md:flex flex-col items-end text-right">
                    <span class="text-sm font-semibold text-gray-700" id="currentDate">{{ $currentDate }}</span>
                    <span class="text-xs text-gray-500 time-display">{{ $currentTime }}</span>
                </div>
            </div>
        </div>
    </div>
</header>
