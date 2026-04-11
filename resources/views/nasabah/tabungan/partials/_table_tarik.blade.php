<div class="overflow-x-auto flex-1">
    <table class="w-full">
        <thead>
            <tr class="bg-linear-to-r from-[#8b6f2f] to-[#d4af37] text-white">
                <th class="px-6 py-3 text-left text-xs font-bold uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-bold uppercase">Nominal</th>
                <th class="px-6 py-3 text-left text-xs font-bold uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-bold uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($pengajuanTariks as $item)
                @php
                    $statusConfig = [
                        '1' => ['bg' => 'bg-yellow-100', 'text' => 'text-yellow-700', 'label' => 'Menunggu'],
                        '2' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'label' => 'Disetujui'],
                        '3' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'label' => 'Ditolak'],
                    ];
                    $status = $statusConfig[$item->status] ?? $statusConfig['1'];
                @endphp
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-xs font-mono text-gray-500">ID: #{{ $item->id }}</p>
                        <p class="text-[10px] text-gray-400 mt-1">{{ $item->created_at->format('d M Y, H:i') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-[#8b6f2f]">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $status['bg'] }} {{ $status['text'] }}">
                            {{ $status['label'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('nasabah.tabungan.detail-pengajuan-tarik', $item->id) }}" class="text-[#674c1d] hover:text-[#8b6f2f] text-xs font-semibold flex items-center gap-1 group border-none bg-transparent">
                            Detail
                            <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 text-sm italic">Belum ada pengajuan tarik</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($pengajuanTariks->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 pagination-container">
        {{ $pengajuanTariks->links() }}
    </div>
@endif
