@props([
    'title' => 'Semua Beres!',
    'message' => 'Tidak ada data untuk ditampilkan saat ini.',
    'icon' => null,
    'minHeight' => 'py-12'
])

<div class="flex flex-col items-center justify-center text-center w-full {{ $minHeight }}">
    <div class="inline-flex items-center justify-center w-16 h-16 rounded-full bg-gray-50 mb-3 border border-gray-100 shadow-sm">
        @if($icon)
            <div class="w-8 h-8 text-gray-300">
                {{ $icon }}
            </div>
        @else
            <svg class="w-8 h-8 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M5 13l4 4L19 7"></path></svg>
        @endif
    </div>
    <h3 class="text-sm font-bold text-gray-900 font-display">{{ $title }}</h3>
    <p class="text-xs text-gray-400 mt-1 font-medium">{{ $message }}</p>
    @if(isset($action))
        <div class="mt-4">
            {{ $action }}
        </div>
    @endif
</div>
