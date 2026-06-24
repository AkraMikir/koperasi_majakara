<form action="{{ route('admin.settings.struk.update-info-box-pinjaman') }}" method="POST" class="space-y-4">
    @csrf
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Info Box Pinjaman (Kiri Bawah)</label>
        <textarea name="info_box_pinjaman" rows="5" required
            class="w-full rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow"
            placeholder="Tuliskan info box pinjaman di sini...">{{ $settings->info_box_pinjaman }}</textarea>
        <p class="text-xs text-gray-400 mt-1">Teks ini akan dicetak di dalam kotak informasi sebelah kiri bawah lembar struk pinjaman B5.</p>
    </div>
    <div class="flex justify-end">
        <button type="submit" class="bg-[#674c1d] hover:bg-[#8b6f2f] text-white px-5 py-2.5 rounded-xl font-semibold transition-colors shadow-sm">
            Simpan Info Box Pinjaman
        </button>
    </div>
</form>
