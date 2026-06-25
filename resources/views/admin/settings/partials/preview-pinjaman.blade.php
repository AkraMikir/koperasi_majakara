<div class="bg-white p-[4px] border border-gray-300 rounded-lg max-w-[240px] mx-auto text-[11px] font-bold font-mono text-black shadow-md relative leading-[1.4]" style="font-family: 'Courier', monospace; font-weight: bold;">
    <div class="absolute -top-2 left-1/2 -translate-x-1/2 bg-gray-200 text-gray-500 px-2 py-0.5 rounded-full text-[9px] font-sans font-bold uppercase tracking-wider">Pinjaman Struk</div>
    
    @php
        $mappedData = [
            'jenis_trans' => $data['jenis_trans'],
            'no_pinjaman' => $data['no_pinjaman'],
            'tanggal' => $data['tanggal'],
            'nama_anggota' => $data['nama_anggota'],
            'lama_pinjam' => $data['lama_pinjam'],
            'angsuran_pertama' => $data['angsuran_pertama'],
            'metode' => $data['jenis_trans'] === 'PENCAIRAN' ? 'Transfer' : 'Cash',
            'jumlah_pinjam' => $data['jumlah_pinjam'] ?? 0,
            'angsuran_ke' => '1',
            'status' => 'Lunas angsuran ke-1',
            'nominal' => $data['angsuran_pertama'],
            'tanggal_cetak' => $data['tanggal']
        ];
    @endphp

    @include('admin.settings.partials.components.pinjaman-body', ['settings' => $settings, 'data' => $mappedData, 'isPdf' => false])
</div>
