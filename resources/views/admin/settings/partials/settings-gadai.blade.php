<form action="{{ route('admin.settings.struk.update-extra-kehilangan') }}" method="POST" class="space-y-4" onsubmit="let input = document.getElementById('extra_nilai_kehilangan'); if(input) input.value = input.value.replace(/[^0-9]/g, '');">
    @csrf
    <div>
        <label class="block text-sm font-medium text-gray-700 mb-1">Biaya Administrasi Cetak Ulang Struk (Rp)</label>
        <input type="text" name="extra_nilai_kehilangan" id="extra_nilai_kehilangan" value="{{ number_format($settings->extra_nilai_kehilangan, 0, ',', '.') }}" required
            class="w-full rounded-xl border-gray-300 focus:border-[#674c1d] focus:ring focus:ring-[#674c1d]/20 transition-shadow" oninput="formatCurrency(this)">
        <p class="text-xs text-gray-500 mt-1">Biaya yang dikenakan jika nasabah kehilangan struk dan meminta cetak ulang</p>
    </div>
    <div class="flex justify-end pt-2">
        <button type="submit" class="bg-[#674c1d] hover:bg-[#8b6f2f] text-white px-5 py-2.5 rounded-xl font-semibold transition-colors shadow-sm">
            Save Extra Kehilangan
        </button>
    </div>
</form>

<script>
    if (typeof formatCurrency !== 'function') {
        function formatCurrency(input) {
            let value = input.value.replace(/[^0-9]/g, '');
            if (value) value = parseInt(value).toLocaleString('id-ID');
            input.value = value;
        }
    }
</script>
