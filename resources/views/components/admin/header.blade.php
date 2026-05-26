@php
    $currentDate = now()->locale('id')->isoFormat('D MMMM YYYY');
    $currentTime = now()->format('H.i.s');
@endphp

<header class="bg-white shadow-sm border-b border-gray-200 h-20 flex items-center justify-between px-6 sticky top-0 z-30">
    <!-- Left Side: Menu Toggle & Breadcrumb -->
    <div class="flex items-center space-x-4">
        <!-- Mobile Menu Button -->
        <button onclick="toggleSidebar()" class="lg:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100 transition-colors">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"></path>
            </svg>
        </button>
        
        <!-- Breadcrumb -->
        <div class="hidden md:flex items-center space-x-2 text-sm">
            <span class="text-gray-500">Admin</span>
            <svg class="w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="text-primary font-medium">@yield('title', 'Dashboard')</span>
        </div>
    </div>
    
    <!-- Right Side: Search, Notifications, Profile -->
    <div class="flex items-center space-x-4">
        <!-- Search -->
        <div class="hidden md:flex items-center relative">
            <input type="text" placeholder="Cari..." 
                class="pl-10 pr-4 py-2 w-64 border border-gray-300 rounded-lg focus:ring-2 focus:ring-[#674c1d] focus:border-[#674c1d] outline-none text-sm">
            <svg class="w-5 h-5 text-gray-400 absolute left-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </div>
        
        <!-- Notifications (hover = preview dropdown, badge = jumlah belum dibaca) -->
        @php
            $notificationsUnreadCount = $notificationsUnreadCount ?? 0;
            $notificationsRecent = $notificationsRecent ?? collect([]);
        @endphp
        <div class="relative group" x-data="{ open: false }">
            <a href="{{ route('admin.notifications.index') }}" class="relative inline-flex p-2 text-gray-600 hover:text-primary transition-colors rounded-lg hover:bg-gray-100" @mouseenter="open = true" @mouseleave="open = false">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                </svg>
                @if($notificationsUnreadCount > 0)
                    <span class="absolute -top-0.5 -right-0.5 min-w-[18px] h-[18px] px-1 flex items-center justify-center bg-red-500 text-white text-xs font-bold rounded-full border-2 border-white">
                        {{ $notificationsUnreadCount > 99 ? '99+' : $notificationsUnreadCount }}
                    </span>
                @endif
            </a>
            <!-- Dropdown preview on hover -->
            <div x-show="open" x-cloak
                 @mouseenter="open = true" @mouseleave="open = false"
                 x-transition:enter="transition ease-out duration-150"
                 x-transition:enter-start="opacity-0 scale-95"
                 x-transition:enter-end="opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-100"
                 class="absolute right-0 mt-1 w-80 bg-white rounded-xl shadow-lg border border-gray-200 py-2 z-[100]">
                <div class="px-4 py-2 border-b border-gray-100 flex items-center justify-between">
                    <span class="font-semibold text-gray-900">Notifikasi</span>
                    @if($notificationsUnreadCount > 0)
                        <form method="POST" action="{{ route('admin.notifications.mark-all-read') }}" class="inline">
                            @csrf
                            <button type="submit" class="text-xs text-[#674c1d] hover:underline">Tandai semua dibaca</button>
                        </form>
                    @endif
                </div>
                <div class="max-h-80 overflow-y-auto">
                    @forelse($notificationsRecent as $notif)
                        @php $targetUrl = $notif->link ?: route('admin.notifications.index'); @endphp
                        <form method="POST" action="{{ route('admin.notifications.mark-read', $notif->id) }}" class="block border-b border-gray-50 last:border-0 {{ $notif->read_at ? '' : 'bg-[#674c1d]/5' }}">
                            @csrf
                            <input type="hidden" name="redirect" value="{{ $targetUrl }}">
                            <button type="submit" class="w-full text-left px-4 py-3 hover:bg-gray-50 transition-colors">
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
                    <a href="{{ route('admin.notifications.index') }}" class="text-sm font-medium text-[#674c1d] hover:underline">Lihat semua notifikasi</a>
                </div>
            </div>
        </div>
        
        <!-- Date & Time -->
        <div class="hidden lg:flex flex-col items-end text-right border-r border-gray-200 pr-4">
            <span class="text-sm font-semibold text-gray-700" id="currentDate">{{ $currentDate }}</span>
            <span class="text-xs text-gray-500 time-display">{{ $currentTime }}</span>
        </div>
        
        <!-- Profile Dropdown -->
        <div class="relative" x-data="{ open: false }">
            <button @click="open = !open" class="flex items-center space-x-3 p-2 rounded-lg hover:bg-gray-100 transition-colors">
                @if(auth()->user()->foto && Storage::disk('public')->exists(auth()->user()->foto) && auth()->user()->foto !== 'default-avatar.jpg')
                    <img src="{{ Storage::url(auth()->user()->foto) }}" alt="{{ auth()->user()->nama }}" class="w-10 h-10 rounded-full object-cover border border-gray-200">
                @else
                    <div class="w-10 h-10 bg-linear-to-br from-[#674c1d] to-[#8b6f2f] rounded-full flex items-center justify-center text-white font-bold">
                        {{ substr(auth()->user()->nama ?? 'A', 0, 1) }}
                    </div>
                @endif
                <div class="hidden md:block text-left">
                    <p class="text-sm font-semibold text-gray-900">{{ auth()->user()->nama ?? 'Admin' }}</p>
                    @php
                        $roleBadge = $permissionService->getRoleBadgeColor(auth()->user());
                        $roleDisplay = $permissionService->getRoleDisplayName(auth()->user());
                    @endphp
                    <span class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium 
                        @if($roleBadge === 'danger') bg-red-100 text-red-800
                        @elseif($roleBadge === 'warning') bg-yellow-100 text-yellow-800
                        @else bg-gray-100 text-gray-800
                        @endif">
                        {{ $roleDisplay }}
                    </span>
                </div>
                <svg class="w-5 h-5 text-gray-400 hidden md:block" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                </svg>
            </button>
            
            <!-- Dropdown Menu -->
            <div x-show="open" 
                 x-cloak
                 @click.away="open = false" 
                 x-transition:enter="transition ease-out duration-100"
                 x-transition:enter-start="transform opacity-0 scale-95"
                 x-transition:enter-end="transform opacity-100 scale-100"
                 x-transition:leave="transition ease-in duration-75"
                 x-transition:leave-start="transform opacity-100 scale-100"
                 x-transition:leave-end="transform opacity-0 scale-95"
                class="absolute right-0 mt-2 w-48 bg-white rounded-lg shadow-lg border border-gray-200 py-2 z-50">
                <a href="{{ route('admin.profile.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Profil Saya</a>
                <a href="{{ route('admin.setting.index') }}" class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Pengaturan</a>
                <div class="border-t border-gray-200 my-2"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="block w-full text-left px-4 py-2 text-sm text-red-600 hover:bg-gray-100">
                        Keluar
                    </button>
                </form>
            </div>
        </div>
        
        <style>
            [x-cloak] { display: none !important; }
        </style>
    </div>
</header>

