@extends('layouts.nasabah')

@section('title', 'Notifikasi')

@section('content')
<div class="w-full pb-8">
    <div class="mx-4 mt-4 space-y-6">
        <!-- Page Header -->
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold text-gray-900 font-display">Notifikasi</h1>
                <p class="text-gray-600 text-sm mt-1">Semua notifikasi dari pengajuan tabungan, pinjaman, dan aktivitas lainnya</p>
            </div>
            <div class="flex items-center gap-2 flex-wrap">
                @if($notifications->whereNull('read_at')->count() > 0)
                    <form method="POST" action="{{ route('nasabah.notifications.mark-all-read') }}" class="inline">
                        @csrf
                        <button type="submit" class="px-4 py-2.5 bg-[#674c1d] text-white rounded-xl hover:bg-[#4a3514] transition-colors text-sm font-medium">
                            Tandai semua dibaca
                        </button>
                    </form>
                @endif
                <a href="{{ route('nasabah.dashboard') }}" class="px-4 py-2.5 bg-white border border-gray-300 rounded-xl text-gray-700 hover:bg-gray-50 transition-colors text-sm font-medium">
                    ← Dashboard
                </a>
            </div>
        </div>

        @if(session('success'))
            <div class="p-4 bg-green-50 border border-green-200 rounded-xl text-green-800 text-sm">
                {{ session('success') }}
            </div>
        @endif

        <!-- Filter -->
        <div class="bg-white rounded-2xl shadow-md p-4 border border-gray-100">
            <form method="GET" action="{{ route('nasabah.notifications.index') }}" class="flex flex-wrap items-center gap-3">
                <label class="inline-flex items-center gap-2 cursor-pointer">
                    <input type="checkbox" name="unread_only" value="1" {{ request('unread_only') ? 'checked' : '' }} class="rounded border-gray-300 text-[#674c1d] focus:ring-[#674c1d]">
                    <span class="text-sm text-gray-700">Hanya belum dibaca</span>
                </label>
                <button type="submit" class="px-3 py-2 bg-gray-100 text-gray-700 rounded-lg hover:bg-gray-200 text-sm font-medium">Filter</button>
            </form>
        </div>

        <!-- List Notifikasi -->
        <div class="bg-white rounded-2xl shadow-md border border-gray-100 overflow-hidden">
            @forelse($notifications as $notif)
                <div class="border-b border-gray-100 last:border-0 {{ $notif->read_at ? 'bg-white' : 'bg-[#674c1d]/5' }}">
                    <form method="POST" action="{{ route('nasabah.notifications.mark-read', $notif->id) }}" class="block">
                        @csrf
                        <input type="hidden" name="redirect" value="{{ $notif->link ?: url()->current() }}">
                        <button type="submit" class="w-full text-left px-6 py-4 hover:bg-gray-100 transition-colors flex items-start gap-4">
                            <div class="shrink-0 w-10 h-10 rounded-xl flex items-center justify-center
                                @if($notif->type === 'tabungan_setor') bg-green-100 text-green-700
                                @elseif($notif->type === 'tabungan_tarik') bg-amber-100 text-amber-700
                                @elseif(str_starts_with($notif->type, 'pinjaman')) bg-blue-100 text-blue-700
                                @elseif($notif->type === 'janji_temu') bg-purple-100 text-purple-700
                                @elseif($notif->type === 'profil') bg-indigo-100 text-indigo-700
                                @else bg-gray-100 text-gray-600 @endif">
                                @if($notif->type === 'tabungan_setor')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path></svg>
                                @elseif($notif->type === 'tabungan_tarik')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 12H4"></path></svg>
                                @elseif(str_starts_with($notif->type, 'pinjaman'))
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                                @elseif($notif->type === 'janji_temu')
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                @else
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path></svg>
                                @endif
                            </div>
                            <div class="flex-1 min-w-0">
                                <p class="font-semibold text-gray-900">{{ $notif->title }}</p>
                                @if($notif->message)
                                    <p class="text-sm text-gray-500 mt-0.5">{{ $notif->message }}</p>
                                @endif
                                <p class="text-xs text-gray-400 mt-2">{{ $notif->created_at->format('d M Y, H:i') }} · {{ $notif->created_at->diffForHumans() }}</p>
                            </div>
                            @if($notif->link)
                                <span class="shrink-0 text-[#674c1d] text-sm font-medium">Buka →</span>
                            @endif
                        </button>
                    </form>
                </div>
            @empty
                <div class="px-6 py-16 text-center">
                    <svg class="w-16 h-16 text-gray-300 mx-auto mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9"></path>
                    </svg>
                    <p class="text-gray-500 font-medium">Tidak ada notifikasi</p>
                    <p class="text-sm text-gray-400 mt-1">Notifikasi dari pengajuan tabungan, pinjaman, dan aktivitas akan muncul di sini.</p>
                </div>
            @endforelse
        </div>

        @if($notifications->hasPages())
            <div class="flex justify-center">
                {{ $notifications->withQueryString()->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
