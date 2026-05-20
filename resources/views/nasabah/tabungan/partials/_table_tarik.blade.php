<div class="overflow-x-auto">
    <table class="w-full">
        <thead>
            <tr class="bg-[#674c1d]/8 border-b border-[#674c1d]/10">
                <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Tanggal</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Nominal</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Status</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($pengajuanTariks as $item)
                @php
                    $statusConfig = [
                        '1' => ['bg' => 'bg-amber-100', 'text' => 'text-amber-700', 'dot' => 'bg-amber-500', 'label' => 'Menunggu'],
                        '2' => ['bg' => 'bg-green-100', 'text' => 'text-green-700', 'dot' => 'bg-green-500', 'label' => 'Disetujui'],
                        '3' => ['bg' => 'bg-red-100', 'text' => 'text-red-700', 'dot' => 'bg-red-500', 'label' => 'Ditolak'],
                    ];
                    $status = $statusConfig[$item->status] ?? $statusConfig['1'];
                @endphp
                <tr class="hover:bg-[#674c1d]/5 transition-colors">
                    <td class="px-5 py-3">
                        <p class="text-sm text-gray-700">{{ $item->created_at->format('d M Y') }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $item->created_at->format('H:i') }}</p>
                    </td>
                    <td class="px-5 py-3">
                        <p class="text-sm font-bold text-[#8b6f2f]">Rp {{ number_format($item->nominal, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $status['bg'] }} {{ $status['text'] }}">
                            <span class="w-1 h-1 rounded-full {{ $status['dot'] }}"></span>
                            {{ $status['label'] }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <a href="{{ route('nasabah.tabungan.detail-pengajuan-tarik', $item->id) }}"
                           class="text-[#674c1d] hover:text-[#8b6f2f] text-xs font-semibold flex items-center gap-1 group">
                            Detail
                            <svg class="w-3 h-3 group-hover:translate-x-0.5 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
                            </svg>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-5 py-12 text-center">
                        <div class="flex flex-col items-center">
                            <div class="w-14 h-14 bg-gradient-to-br from-[#faf9f0] to-[#fff9e0] border border-[#d4af37]/30 rounded-full flex items-center justify-center mb-3">
                                <svg class="w-7 h-7 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M20 12H4"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-600">Belum ada pengajuan penarikan</p>
                            <p class="text-xs text-gray-400 mt-1">Pengajuan penarikan akan muncul di sini</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($pengajuanTariks->hasPages())
    <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50">
        {{ $pengajuanTariks->links() }}
    </div>
@endif
