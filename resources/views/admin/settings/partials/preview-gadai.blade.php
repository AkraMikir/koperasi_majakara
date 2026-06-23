<div class="bg-white p-[4px] border border-gray-300 rounded-lg max-w-[240px] mx-auto text-[12px] font-mono text-black shadow-md relative leading-[1.5]" style="font-family: 'Courier', monospace;">
    <div class="absolute -top-2 left-1/2 -translate-x-1/2 bg-gray-200 text-gray-500 px-2 py-0.5 rounded-full text-[9px] font-sans font-bold uppercase tracking-wider">Gadai Struk</div>
    
    @php
        $mappedData = [
            'jenis_trans' => $data['jenis_trans'],
            'nama_anggota' => $data['nama_anggota'],
            'no_anggota' => '12345',
            'kategori' => $data['kategori'],
            'barang' => $data['barang'],
            'slot_kode' => $data['slot_kode'],
            'tgl_mulai' => $data['tgl_mulai'],
            'jatuh_tempo' => $data['jatuh_tempo'],
            'nominal_deal' => $data['nominal_deal'],
            'biaya_jasa' => $data['biaya_jasa'],
            'biaya_inap' => $data['biaya_inap'],
            'denda_aktif' => 0,
            'extra_pinjaman_nominal' => 0,
            'extra_pinjaman_reason' => '',
            'total_tagihan' => $data['total_tagihan'],
            'tanggal' => $data['tanggal']
        ];
    @endphp

    @include('admin.settings.partials.components.gadai-body', ['settings' => $settings, 'data' => $mappedData])
</div>
