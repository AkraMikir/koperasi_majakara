<!-- Modal Dana Masuk -->
<div id="modalMasuk" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-green-50">
            <h3 class="text-xl font-bold text-green-900 font-display">Tambah Dana Masuk (Modal)</h3>
            <button onclick="closeModal('modalMasuk')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('admin.petty-cash.owner-wallet.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="tipe" value="masuk">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nominal Cash</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                        <input type="number" name="nominal_cash" placeholder="0" 
                               class="w-full pl-10 pr-4 py-2 bg-gray-50 border-gray-200 rounded-xl focus:ring-green-500 focus:border-green-500 transition-all font-semibold">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nominal Transfer</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                        <input type="number" name="nominal_tf" placeholder="0" 
                               class="w-full pl-10 pr-4 py-2 bg-gray-50 border-gray-200 rounded-xl focus:ring-green-500 focus:border-green-500 transition-all font-semibold">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Keterangan / Sumber Dana</label>
                <textarea name="keterangan" rows="3" required placeholder="Contoh: Setoran Modal Awal Owner"
                          class="w-full px-4 py-2 bg-gray-50 border-gray-200 rounded-xl focus:ring-green-500 focus:border-green-500 transition-all text-sm"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Upload Bukti Cash</label>
                    <input type="file" name="bukti_foto_cash" accept="image/*"
                           class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-green-50 file:text-green-700 hover:file:bg-green-100">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Upload Bukti Transfer</label>
                    <input type="file" name="bukti_foto_tf" accept="image/*"
                           class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalMasuk')" class="px-4 py-2 text-gray-600 font-semibold text-sm hover:text-gray-800">Batal</button>
                <button type="submit" class="px-6 py-2 bg-green-600 text-white rounded-xl font-bold text-sm hover:bg-green-700 shadow-md">Simpan Modal</button>
            </div>
        </form>
    </div>
</div>

<!-- Modal Dana Keluar -->
<div id="modalKeluar" class="fixed inset-0 z-50 hidden items-center justify-center bg-black/50 backdrop-blur-sm p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-lg overflow-hidden animate-in fade-in zoom-in duration-200">
        <div class="p-6 border-b border-gray-100 flex items-center justify-between bg-red-50">
            <h3 class="text-xl font-bold text-red-900 font-display">Catat Dana Keluar (Biaya)</h3>
            <button onclick="closeModal('modalKeluar')" class="text-gray-400 hover:text-gray-600 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/></svg>
            </button>
        </div>
        <form action="{{ route('admin.petty-cash.owner-wallet.store') }}" method="POST" enctype="multipart/form-data" class="p-6 space-y-4">
            @csrf
            <input type="hidden" name="tipe" value="keluar">
            
            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nominal Cash</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                        <input type="number" name="nominal_cash" placeholder="0" 
                               class="w-full pl-10 pr-4 py-2 bg-gray-50 border-gray-200 rounded-xl focus:ring-red-500 focus:border-red-500 transition-all font-semibold">
                    </div>
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Nominal Transfer</label>
                    <div class="relative">
                        <span class="absolute left-3 top-1/2 -translate-y-1/2 text-gray-400 font-bold">Rp</span>
                        <input type="number" name="nominal_tf" placeholder="0" 
                               class="w-full pl-10 pr-4 py-2 bg-gray-50 border-gray-200 rounded-xl focus:ring-red-500 focus:border-red-500 transition-all font-semibold">
                    </div>
                </div>
            </div>

            <div>
                <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Keterangan Biaya</label>
                <textarea name="keterangan" rows="3" required placeholder="Contoh: Pembelian Inventaris Kantor / Pengeluaran Tak Terduga"
                          class="w-full px-4 py-2 bg-gray-50 border-gray-200 rounded-xl focus:ring-red-500 focus:border-red-500 transition-all text-sm"></textarea>
            </div>

            <div class="grid grid-cols-2 gap-4">
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Upload Bukti Cash</label>
                    <input type="file" name="bukti_foto_cash" accept="image/*"
                           class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-red-50 file:text-red-700 hover:file:bg-red-100">
                </div>
                <div>
                    <label class="block text-xs font-bold text-gray-700 uppercase mb-1">Upload Bukti Transfer</label>
                    <input type="file" name="bukti_foto_tf" accept="image/*"
                           class="w-full text-xs text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-xs file:font-semibold file:bg-blue-50 file:text-blue-700 hover:file:bg-blue-100">
                </div>
            </div>

            <div class="pt-4 border-t border-gray-100 flex justify-end gap-3">
                <button type="button" onclick="closeModal('modalKeluar')" class="px-4 py-2 text-gray-600 font-semibold text-sm hover:text-gray-800">Batal</button>
                <button type="submit" class="px-6 py-2 bg-red-600 text-white rounded-xl font-bold text-sm hover:bg-red-700 shadow-md">Simpan Pengeluaran</button>
            </div>
        </form>
    </div>
</div>
