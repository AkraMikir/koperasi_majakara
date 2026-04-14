@forelse($pengiriman as $item)
<tr class="hover:bg-gray-50 transition-colors">
    <td class="px-6 py-4">
        <div class="flex items-center gap-3">
            <div class="w-10 h-10 bg-[#674c1d] rounded-full flex items-center justify-center text-white text-xs font-bold shadow-sm">
                {{ substr($item->admin->nama ?? 'A', 0, 1) }}
            </div>
            <div>
                <p class="font-bold text-gray-900 text-sm">{{ $item->admin->nama ?? '-' }}</p>
                <p class="text-[10px] font-mono text-gray-400 mt-0.5 tracking-tighter">{{ $item->id }}</p>
            </div>
        </div>
    </td>
    <td class="px-6 py-4">
        @php $total = (float)$item->nominal_tf + (float)$item->nominal_cash; @endphp
        <p class="font-bold text-gray-900 text-base">Rp {{ number_format($total, 0, ',', '.') }}</p>
    </td>
    <td class="px-6 py-4">
        <div class="flex flex-col gap-1.5 justify-center">
            @if($item->nominal_tf > 0)
            <div class="flex items-center gap-2">
                <span class="w-7 h-7 flex items-center justify-center bg-blue-100 text-blue-700 rounded-full border border-blue-200" title="Transfer">
                    <i class="fas fa-university text-[10px]"></i>
                </span>
                <span class="text-[10px] font-bold text-blue-600">Rp {{ number_format($item->nominal_tf, 0, ',', '.') }}</span>
            </div>
            @endif
            @if($item->nominal_cash > 0)
            <div class="flex items-center gap-2">
                <span class="w-7 h-7 flex items-center justify-center bg-emerald-100 text-emerald-700 rounded-full border border-emerald-200" title="Tunai">
                    <i class="fas fa-money-bill-wave text-[10px]"></i>
                </span>
                <span class="text-[10px] font-bold text-emerald-600">Rp {{ number_format($item->nominal_cash, 0, ',', '.') }}</span>
            </div>
            @endif
        </div>
    </td>
    <td class="px-6 py-4 text-center">
        @if($item->status === 'pending')
            <span class="px-3 py-1 bg-yellow-100 text-yellow-700 rounded-full text-[11px] font-bold uppercase tracking-wider">Pending</span>
        @elseif($item->status === 'approved')
            <span class="px-3 py-1 bg-green-100 text-green-700 rounded-full text-[11px] font-bold uppercase tracking-wider">ACC</span>
        @else
            <span class="px-3 py-1 bg-red-100 text-red-700 rounded-full text-[11px] font-bold uppercase tracking-wider">Ditolak</span>
        @endif
    </td>
    <td class="px-6 py-4 text-right">
        <p class="text-sm text-gray-700 font-medium">{{ $item->created_at->format('d/m/Y') }}</p>
        <p class="text-[10px] text-gray-400">{{ $item->created_at->format('H:i') }} WIB</p>
    </td>
</tr>
@empty
<tr>
    <td colspan="5" class="px-6 py-20 text-center">
        <div class="max-w-xs mx-auto">
            <i class="fas fa-paper-plane text-4xl text-gray-200 mb-4"></i>
            <p class="text-gray-500 font-medium">Data tidak ditemukan atau belum ada pengiriman</p>
        </div>
    </td>
</tr>
@endforelse
