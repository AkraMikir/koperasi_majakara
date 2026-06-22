<div class="bg-white p-[4px] border border-gray-300 rounded-lg max-w-[240px] mx-auto text-[12px] font-mono text-black shadow-md relative leading-[1.5]" style="font-family: 'Courier', monospace;">
    <div class="absolute -top-2 left-1/2 -translate-x-1/2 bg-gray-200 text-gray-500 px-2 py-0.5 rounded-full text-[9px] font-sans font-bold uppercase tracking-wider">Tabungan Struk</div>
    
    @php
        $mappedData = [
            'jenis_trans' => $data['jenis_trans'],
            'no_struk' => $data['no_struk'],
            'tanggal' => $data['tanggal'],
            'nama_anggota' => $data['nama_anggota'],
            'nik' => '320101XXXXXXXXXX',
            'via' => 'Cash',
            'nominal' => $data['nominal'],
            'nominal_murni' => $data['nominal'],
            'biaya_transfer' => 0,
            'keterangan' => '',
            'approver_name' => '',
            'approver_role' => ''
        ];
    @endphp

    @include('admin.settings.partials.components.tabungan-body', ['settings' => $settings, 'data' => $mappedData])
</div>
