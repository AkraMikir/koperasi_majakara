@props(['placeholderTanggal' => 'tanggal', 'placeholderJumlah' => 'jumlah', 'placeholderIdTransaksi' => 'id transaksi'])

<div class="flex flex-col sm:flex-row gap-3 items-end">
    <!-- Filter Inputs -->
    <div class="flex-1 grid grid-cols-1 sm:grid-cols-3 gap-3">
        <!-- Tanggal Input -->
        <div class="relative">
            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-[#8b6f2f]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                </svg>
            </div>
            <input type="date" 
                class="w-full pl-11 pr-4 py-2.5 border-2 border-[#8b6f2f]/30 rounded-xl focus:ring-2 focus:ring-[#d4af37] focus:border-[#8b6f2f] transition-all outline-none text-sm bg-white"
                placeholder="{{ $placeholderTanggal }}">
        </div>
        
        <!-- Jumlah Input -->
        <div class="relative">
            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-[#4a3514]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
            </div>
            <input type="text" 
                class="w-full pl-11 pr-4 py-2.5 border-2 border-[#4a3514]/30 rounded-xl focus:ring-2 focus:ring-[#d4af37] focus:border-[#4a3514] transition-all outline-none text-sm bg-white"
                placeholder="{{ $placeholderJumlah }}">
        </div>
        
        <!-- ID Transaksi Input -->
        <div class="relative">
            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-[#674c1d]">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                </svg>
            </div>
            <input type="text" 
                class="w-full pl-11 pr-4 py-2.5 border-2 border-[#674c1d]/30 rounded-xl focus:ring-2 focus:ring-[#d4af37] focus:border-[#674c1d] transition-all outline-none text-sm bg-white"
                placeholder="{{ $placeholderIdTransaksi }}">
        </div>
    </div>
    
    <!-- Action Buttons -->
    <div class="flex gap-2">
        <button class="w-12 h-12 bg-gradient-to-br from-[#674c1d] to-[#4a3514] text-white rounded-xl hover:from-[#4a3514] hover:to-[#674c1d] transition-all flex items-center justify-center shadow-md hover:shadow-lg transform hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
        </button>
        <button class="w-12 h-12 bg-gradient-to-br from-[#8b6f2f] to-[#d4af37] text-white rounded-xl hover:from-[#d4af37] hover:to-[#8b6f2f] transition-all flex items-center justify-center shadow-md hover:shadow-lg transform hover:scale-105">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
            </svg>
        </button>
    </div>
</div>

