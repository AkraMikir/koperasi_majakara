<form action="{{ route('admin.settings.struk.update-syarat-gadai') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Syarat & Ketentuan Gadai</label>
        <textarea name="syarat_ketentuan_gadai" rows="6" required
            class="w-full rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow"
            placeholder="Tuliskan syarat dan ketentuan gadai di sini...">{{ $settings->syarat_ketentuan_gadai }}</textarea>
        <p class="text-xs text-gray-400 mt-1">Syarat dan ketentuan ini akan dicetak di bagian bawah lembar struk gadai aktif.</p>
    </div>
    <div class="flex justify-end">
        <button type="submit" class="bg-[#674c1d] hover:bg-[#8b6f2f] text-white px-5 py-2.5 rounded-xl font-semibold transition-colors shadow-sm">
            Simpan Syarat Gadai
        </button>
    </div>
</form>
