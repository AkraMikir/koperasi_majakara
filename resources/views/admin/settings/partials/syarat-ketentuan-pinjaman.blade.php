<form action="{{ route('admin.settings.struk.update-syarat-pinjaman') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Syarat & Ketentuan Pinjaman</label>
        <textarea name="syarat_ketentuan_pinjaman" rows="8" required
            class="w-full rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow"
            placeholder="Tuliskan syarat dan ketentuan pinjaman di sini...">{{ $settings->syarat_ketentuan_pinjaman }}</textarea>
        <p class="text-xs text-gray-400 mt-1">Syarat dan ketentuan ini akan dicetak di bagian tengah lembar struk pinjaman B5.</p>
    </div>
    <div class="flex justify-end">
        <button type="submit" class="bg-[#674c1d] hover:bg-[#8b6f2f] text-white px-5 py-2.5 rounded-xl font-semibold transition-colors shadow-sm">
            Simpan Syarat Pinjaman
        </button>
    </div>
</form>
