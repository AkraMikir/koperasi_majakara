<div class="overflow-x-auto flex-1">
    <table class="w-full">
        <thead>
            <tr class="bg-linear-to-r from-[#d4af37] to-[#8b6f2f] text-white">
                <th class="px-6 py-3 text-left text-xs font-bold uppercase">ID</th>
                <th class="px-6 py-3 text-left text-xs font-bold uppercase">Nominal</th>
                <th class="px-6 py-3 text-left text-xs font-bold uppercase">Status</th>
                <th class="px-6 py-3 text-left text-xs font-bold uppercase">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-100">
            @forelse($janjiTemuTabungans as $jt)
                <tr class="hover:bg-gray-50/50 transition-colors">
                    <td class="px-6 py-4">
                        <p class="text-sm font-mono text-gray-600">{{ $jt->id }}</p>
                        <p class="text-[10px] text-gray-400 mt-1">
                            {{ $jt->tanggal_janji_temu->format('d M Y') }} 
                            {{ $jt->waktu_janji_temu ? \Carbon\Carbon::parse($jt->waktu_janji_temu)->format('H:i') : '' }}
                        </p>
                    </td>
                    <td class="px-6 py-4">
                        <p class="text-sm font-bold text-[#674c1d]">Rp {{ number_format($jt->nominal, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-6 py-4">
                        <span class="px-2 py-1 rounded-full text-[10px] font-bold uppercase {{ $jt->status_display['class'] }}">
                            {{ $jt->status_display['label'] }}
                        </span>
                    </td>
                    <td class="px-6 py-4">
                        <a href="{{ route('nasabah.tabungan.detail-janji-temu', $jt->id) }}" class="text-[#674c1d] hover:text-[#8b6f2f] text-xs font-semibold flex items-center gap-1 group border-none bg-transparent">
                            Detail
                            <svg class="w-3 h-3 group-hover:translate-x-1 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                            </svg>
                        </a>
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="4" class="px-6 py-12 text-center text-gray-400 text-sm italic">Belum ada janji temu</td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($janjiTemuTabungans->hasPages())
    <div class="px-6 py-4 border-t border-gray-100 bg-gray-50/50 pagination-container">
        {{ $janjiTemuTabungans->links() }}
    </div>
@endif
