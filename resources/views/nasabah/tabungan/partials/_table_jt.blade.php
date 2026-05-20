<div class="overflow-x-auto">
    <table class="w-full">
        <thead>
            <tr class="bg-[#674c1d]/8 border-b border-[#674c1d]/10">
                <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Jadwal</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Nominal</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Status</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($janjiTemuTabungans as $jt)
                <tr class="hover:bg-[#674c1d]/5 transition-colors">
                    <td class="px-5 py-3">
                        <p class="text-sm text-gray-700">{{ $jt->tanggal_janji_temu->format('d M Y') }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">
                            {{ $jt->waktu_janji_temu ? \Carbon\Carbon::parse($jt->waktu_janji_temu)->format('H:i') : '-' }}
                        </p>
                    </td>
                    <td class="px-5 py-3">
                        <p class="text-sm font-bold text-[#674c1d]">Rp {{ number_format($jt->nominal, 0, ',', '.') }}</p>
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase {{ $jt->status_display['class'] }}">
                            {{ $jt->status_display['label'] }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <a href="{{ route('nasabah.tabungan.detail-janji-temu', $jt->id) }}"
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-600">Belum ada janji temu</p>
                            <p class="text-xs text-gray-400 mt-1">Buat janji temu untuk kunjungan ke koperasi</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($janjiTemuTabungans->hasPages())
    <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50">
        {{ $janjiTemuTabungans->links() }}
    </div>
@endif
