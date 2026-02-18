@props(['data' => [], 'columns' => []])

<div class="overflow-x-auto">
    <table class="w-full">
        <thead>
            <tr class="border-b-2 border-[#674c1d]/20 bg-linear-to-r from-[#674c1d]/5 to-[#8b6f2f]/5">
                @foreach($columns as $key => $column)
                    <th class="px-4 py-3 text-left text-xs font-bold text-[#674c1d] uppercase">
                        <div class="flex items-center gap-2">
                            @if($key === 'tanggal')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                </svg>
                            @elseif($key === 'jumlah' || $key === 'nominal')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($key === 'waktu')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($key === 'lokasi')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                </svg>
                            @elseif($key === 'status')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            @elseif($key === 'jenis')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path>
                                </svg>
                            @elseif($key === 'via')
                                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                </svg>
                            @endif
                            <span>{{ $column }}</span>
                        </div>
                    </th>
                @endforeach
            </tr>
        </thead>
        <tbody>
            @if(count($data) > 0)
                @foreach($data as $index => $row)
                    <tr class="border-b border-gray-100 hover:bg-linear-to-r hover:from-[#674c1d]/5 hover:to-[#8b6f2f]/5 transition-all">
                        @foreach($columns as $key => $column)
                            <td class="px-4 py-3 text-sm">
                                @if($key === 'jumlah' && isset($row->{$key}))
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-linear-to-br from-[#d4af37]/20 to-[#8b6f2f]/20 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-[#674c1d]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="font-semibold text-[#4a3514]">Rp {{ number_format($row->{$key}, 0, ',', '.') }}</span>
                                    </div>
                                @elseif($key === 'tanggal' && isset($row->{$key}))
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-linear-to-br from-[#8b6f2f]/20 to-[#674c1d]/20 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-gray-900">
                                            @php
                                                try {
                                                    $date = \Carbon\Carbon::parse($row->{$key});
                                                    echo $date->format('d M Y');
                                                } catch (\Exception $e) {
                                                    echo $row->{$key};
                                                }
                                            @endphp
                                        </span>
                                    </div>
                                @elseif($key === 'nominal' && isset($row->{$key}))
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-linear-to-br from-[#d4af37]/20 to-[#674c1d]/20 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-[#d4af37]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="font-semibold text-[#4a3514]">Rp {{ number_format($row->{$key}, 0, ',', '.') }}</span>
                                    </div>
                                @elseif($key === 'waktu' && isset($row->{$key}))
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-linear-to-br from-[#4a3514]/20 to-[#674c1d]/20 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-[#4a3514]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-gray-900">{{ $row->{$key} }}</span>
                                    </div>
                                @elseif($key === 'lokasi' && isset($row->{$key}))
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-linear-to-br from-[#8b6f2f]/20 to-[#d4af37]/20 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-[#8b6f2f]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-gray-900">{{ $row->{$key} }}</span>
                                    </div>
                                @elseif($key === 'status' && isset($row->{$key}))
                                    @php
                                        $statusColor = match(strtolower($row->{$key})) {
                                            'menunggu', 'pending' => ['bg' => 'bg-[#d4af37]/20', 'text' => 'text-[#8b6f2f]', 'border' => 'border-[#d4af37]'],
                                            'selesai', 'completed', 'aktif' => ['bg' => 'bg-[#674c1d]/20', 'text' => 'text-[#674c1d]', 'border' => 'border-[#674c1d]'],
                                            default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'border' => 'border-gray-300']
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-3 py-1 {{ $statusColor['bg'] }} {{ $statusColor['text'] }} border {{ $statusColor['border'] }} rounded-full text-xs font-semibold">
                                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
                                        </svg>
                                        {{ $row->{$key} }}
                                    </span>
                                @elseif($key === 'jenis' && isset($row->{$key}))
                                    @php
                                        $jenisColor = match(strtolower($row->{$key})) {
                                            'setoran' => ['bg' => 'bg-[#674c1d]/10', 'text' => 'text-[#674c1d]', 'icon' => 'M12 6v6m0 0v6m0-6h6m-6 0H6'],
                                            'penarikan' => ['bg' => 'bg-[#8b6f2f]/10', 'text' => 'text-[#8b6f2f]', 'icon' => 'M20 12H4'],
                                            default => ['bg' => 'bg-gray-100', 'text' => 'text-gray-700', 'icon' => '']
                                        };
                                    @endphp
                                    <span class="inline-flex items-center gap-1.5 px-2.5 py-1 {{ $jenisColor['bg'] }} {{ $jenisColor['text'] }} rounded-lg text-xs font-medium">
                                        {{ $row->{$key} }}
                                    </span>
                                @elseif($key === 'via' && isset($row->{$key}))
                                    <div class="flex items-center gap-2">
                                        <div class="w-8 h-8 bg-linear-to-br from-[#4a3514]/20 to-[#674c1d]/20 rounded-lg flex items-center justify-center">
                                            <svg class="w-4 h-4 text-[#4a3514]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h18M7 15h1m4 0h1m-7 4h12a3 3 0 003-3V8a3 3 0 00-3-3H6a3 3 0 00-3 3v8a3 3 0 003 3z"></path>
                                            </svg>
                                        </div>
                                        <span class="text-gray-900 font-medium">{{ $row->{$key} }}</span>
                                    </div>
                                @else
                                    <span class="text-gray-900">{{ $row->{$key} ?? '-' }}</span>
                                @endif
                            </td>
                        @endforeach
                    </tr>
                @endforeach
            @else
                <tr>
                    <td colspan="{{ count($columns) }}" class="px-4 py-12 text-center">
                        <div class="flex flex-col items-center gap-3">
                            <div class="w-16 h-16 bg-linear-to-br from-[#674c1d]/10 to-[#8b6f2f]/10 rounded-full flex items-center justify-center">
                                <svg class="w-8 h-8 text-[#674c1d]/50" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                                </svg>
                            </div>
                            <p class="text-sm text-gray-500 font-medium">Tidak ada data</p>
                        </div>
                    </td>
                </tr>
            @endif
        </tbody>
    </table>
</div>

