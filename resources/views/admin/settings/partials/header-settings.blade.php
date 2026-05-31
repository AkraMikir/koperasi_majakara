<form action="{{ route('admin.settings.struk.update-header') }}" method="POST" class="space-y-4">
    @csrf
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Koperasi</label>
            <input type="text" name="nama_koperasi" value="{{ $settings->nama_koperasi }}" required
                class="w-full rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">No. Telp</label>
            <input type="text" name="no_telp" value="{{ $settings->no_telp }}" required
                class="w-full rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow">
        </div>
    </div>
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Alamat Koperasi</label>
        <textarea name="alamat_koperasi" rows="3" required
            class="w-full rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow">{{ $settings->alamat_koperasi }}</textarea>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input type="email" name="email" value="{{ $settings->email }}" required
                class="w-full rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Website</label>
            <input type="url" name="website" value="{{ $settings->website }}"
                class="w-full rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow">
        </div>
    </div>
    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama PT / Badan Hukum</label>
            <input type="text" name="nama_pt" value="{{ $settings->nama_pt }}" required
                class="w-full rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow">
        </div>
        <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Format No. Struk</label>
            <input type="text" name="format_no_struk" value="{{ $settings->format_no_struk }}" required
                class="w-full rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow">
            <p class="text-xs text-gray-400 mt-1">Placeholder: {YYYYMMDD} (Tanggal), {XXXX} (Random 4 Digit)</p>
        </div>
    </div>
    <div class="flex justify-end pt-2">
        <button type="submit" class="bg-[#674c1d] hover:bg-[#8b6f2f] text-white px-5 py-2.5 rounded-xl font-semibold transition-colors shadow-sm">
            Simpan Header
        </button>
    </div>
</form>
