<div class="overflow-x-auto">
    <table class="w-full">
        <thead>
            <tr class="bg-[#674c1d]/8 border-b border-[#674c1d]/10">
                <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Tanggal</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Jumlah</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Jenis</th>
                <th class="px-5 py-3 text-left text-xs font-bold text-[#674c1d] uppercase tracking-wide">Aksi</th>
            </tr>
        </thead>
        <tbody class="divide-y divide-gray-50">
            @forelse($transTabungans as $trans)
                <tr class="hover:bg-[#674c1d]/5 transition-colors">
                    <td class="px-5 py-3">
                        <p class="text-sm text-gray-700">{{ $trans->tgl_transaksi->format('d M Y') }}</p>
                        <p class="text-[10px] text-gray-400 mt-0.5">{{ $trans->tgl_transaksi->format('H:i') }}</p>
                    </td>
                    <td class="px-5 py-3">
                        <p class="text-sm font-bold {{ $trans->jenis === 'setoran' ? 'text-green-600' : 'text-red-600' }}">
                            {{ $trans->jenis === 'setoran' ? '+' : '-' }}Rp {{ number_format(abs((float) $trans->nominal), 0, ',', '.') }}
                        </p>
                    </td>
                    <td class="px-5 py-3">
                        <span class="inline-flex items-center gap-1.5 px-2.5 py-1 rounded-full text-[10px] font-bold uppercase
                            {{ $trans->jenis === 'setoran' ? 'bg-green-100 text-green-700' : 'bg-red-100 text-red-700' }}">
                            <span class="w-1 h-1 rounded-full {{ $trans->jenis === 'setoran' ? 'bg-green-500' : 'bg-red-500' }}"></span>
                            {{ $trans->jenis }}
                        </span>
                    </td>
                    <td class="px-5 py-3">
                        <a href="{{ route('nasabah.tabungan.detail-transaksi', $trans->id) }}"
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
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                                </svg>
                            </div>
                            <p class="text-sm font-semibold text-gray-600">Belum ada transaksi</p>
                            <p class="text-xs text-gray-400 mt-1">Yuk, mulai menabung hari ini!</p>
                        </div>
                    </td>
                </tr>
            @endforelse
        </tbody>
    </table>
</div>
@if($transTabungans->hasPages())
    <div class="px-5 py-3 border-t border-gray-100 bg-gray-50/50">
        {{ $transTabungans->links() }}
    </div>
@endif
