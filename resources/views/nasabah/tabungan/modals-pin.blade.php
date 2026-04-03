<!-- PIN Modal Transfer -->
<div id="pin-modal-transfer" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 transform transition-all border border-gray-100">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Konfirmasi PIN</h3>
                    <p class="text-sm text-gray-500">Masukkan 6 digit PIN Anda</p>
                </div>
            </div>
            <button onclick="closePinModalTransfer()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="mb-8">
            <input type="password" id="pin-input-transfer" maxlength="6" placeholder="••••••"
                class="w-full px-4 py-5 border-2 border-gray-100 rounded-2xl focus:border-[#674c1d] focus:ring-4 focus:ring-[#674c1d]/10 outline-none text-center text-3xl font-bold tracking-[0.5em] transition-all bg-gray-50 focus:bg-white"
                oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length === 6) submitFormTransfer();">
            <p id="pin-error-transfer" class="hidden text-sm text-red-600 mt-4 text-center font-medium bg-red-50 py-2 rounded-lg italic">PIN salah, silakan coba lagi</p>
        </div>
        
        <button onclick="submitFormTransfer()" class="w-full py-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-2xl font-bold text-lg shadow-xl shadow-[#674c1d]/20 hover:shadow-2xl hover:scale-[1.01] active:scale-95 transition-all">
            Verifikasi & Ajukan
        </button>
    </div>
</div>

<!-- PIN Modal Tunai -->
<div id="pin-modal-tunai" class="fixed inset-0 bg-black/60 backdrop-blur-sm z-50 hidden items-center justify-center p-4 transition-all">
    <div class="bg-white rounded-3xl shadow-2xl max-w-md w-full p-8 transform transition-all border border-gray-100">
        <div class="flex items-center justify-between mb-8">
            <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-gradient-to-br from-[#674c1d] to-[#8b6f2f] rounded-2xl flex items-center justify-center shadow-lg">
                    <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                    </svg>
                </div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900">Konfirmasi PIN</h3>
                    <p class="text-sm text-gray-500">Buat janji temu dengan PIN Anda</p>
                </div>
            </div>
            <button onclick="closePinModalTunai()" class="w-10 h-10 flex items-center justify-center rounded-xl bg-gray-50 text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
            </button>
        </div>
        
        <div class="mb-8">
            <input type="password" id="pin-input-tunai" maxlength="6" placeholder="••••••"
                class="w-full px-4 py-5 border-2 border-gray-100 rounded-2xl focus:border-[#674c1d] focus:ring-4 focus:ring-[#674c1d]/10 outline-none text-center text-3xl font-bold tracking-[0.5em] transition-all bg-gray-50 focus:bg-white"
                oninput="this.value = this.value.replace(/[^0-9]/g, ''); if(this.value.length === 6) submitFormTunai();">
            <p id="pin-error-tunai" class="hidden text-sm text-red-600 mt-4 text-center font-medium bg-red-50 py-2 rounded-lg italic">PIN salah, silakan coba lagi</p>
        </div>
        
        <button onclick="submitFormTunai()" class="w-full py-4 bg-gradient-to-r from-[#674c1d] to-[#8b6f2f] text-white rounded-2xl font-bold text-lg shadow-xl shadow-[#674c1d]/20 hover:shadow-2xl hover:scale-[1.01] active:scale-95 transition-all">
            Verifikasi & Buat Janji
        </button>
    </div>
</div>
