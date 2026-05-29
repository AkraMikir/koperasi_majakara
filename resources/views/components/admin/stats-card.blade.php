@props([
    'title',
    'value',
    'fullValue' => null,
    'iconColor' => 'text-majakara-brown',
    'iconHoverColor' => 'group-hover:text-majakara-brown',
    'iconHoverBorder' => 'group-hover:border-majakara-brown/20',
    'iconBg' => 'bg-gray-50',
    'gradient' => false,
    'badgeText' => null,
    'badgeClass' => 'text-green-600 bg-green-50 border border-green-100',
    'subtext' => null
])

@if($gradient)
<div class="bg-gradient-to-br from-majakara-brown to-majakara-dark-gold rounded-[1.5rem] p-5 shadow-md border border-majakara-gold/20 relative overflow-hidden group hover:shadow-lg transition-all text-white">
    <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full blur-2xl -translate-y-1/2 translate-x-1/2"></div>
    <div class="absolute bottom-0 left-0 w-24 h-24 bg-majakara-gold/40 rounded-full blur-xl translate-y-1/2 -translate-x-1/2"></div>
    <div class="flex justify-between items-start mb-4 relative z-10">
        <div class="w-full pr-4">
            <p class="text-[10px] text-white/80 font-bold uppercase tracking-widest mb-1">{{ $title }}</p>
            <h3 class="text-xl font-black font-display truncate" @if($fullValue) title="{{ $fullValue }}" @endif>{{ $value }}</h3>
        </div>
        <div class="w-10 h-10 shrink-0 rounded-xl bg-white/10 flex items-center justify-center border border-white/20 backdrop-blur-sm shadow-inner group-hover:scale-110 transition-transform">
            <div class="w-5 h-5 text-white">
                {{ $slot }}
            </div>
        </div>
    </div>
    @if($badgeText || $subtext)
    <div class="flex items-center gap-2 relative z-10">
        @if($badgeText)
        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md backdrop-blur-sm {{ $badgeClass }}">{{ $badgeText }}</span>
        @endif
        @if($subtext)
        <span class="text-[10px] text-white/80 font-medium truncate">{{ $subtext }}</span>
        @endif
    </div>
    @endif
</div>
@else
<div class="bg-white rounded-[1.5rem] p-5 shadow-sm border border-gray-100 relative overflow-hidden group hover:shadow-md transition-all">
    <div class="absolute top-0 right-0 w-24 h-24 bg-majakara-gold/5 rounded-full blur-xl -translate-y-1/2 translate-x-1/2 group-hover:bg-majakara-gold/10 transition-colors"></div>
    <div class="flex justify-between items-start mb-4 relative z-10">
        <div class="w-full pr-4">
            <p class="text-[10px] text-gray-400 font-bold uppercase tracking-widest mb-1">{{ $title }}</p>
            <h3 class="{{ strlen($value) > 10 ? 'text-xl' : 'text-2xl' }} font-black text-gray-900 font-display truncate" @if($fullValue) title="{{ $fullValue }}" @endif>{{ $value }}</h3>
        </div>
        <div class="w-10 h-10 shrink-0 rounded-xl {{ $iconBg }} flex items-center justify-center border border-gray-100 text-gray-400 {{ $iconHoverBorder }} {{ $iconHoverColor }} transition-colors">
            <div class="w-5 h-5">
                {{ $slot }}
            </div>
        </div>
    </div>
    @if($badgeText || $subtext)
    <div class="flex items-center gap-2 relative z-10">
        @if($badgeText)
        <span class="text-[10px] font-bold px-2 py-0.5 rounded-md {{ $badgeClass }}">{{ $badgeText }}</span>
        @endif
        @if($subtext)
        <span class="text-[10px] text-gray-400 font-medium truncate">{{ $subtext }}</span>
        @endif
    </div>
    @endif
</div>
@endif
