<div class="bg-white p-[4px] border border-gray-300 rounded-lg max-w-[240px] mx-auto text-[11px] font-bold font-mono text-black shadow-md relative leading-[1.4]" style="font-family: 'Courier', monospace; font-weight: bold;">
    <div class="absolute -top-2 left-1/2 -translate-x-1/2 bg-gray-200 text-gray-500 px-2 py-0.5 rounded-full text-[9px] font-sans font-bold uppercase tracking-wider">Deposito Struk</div>
    
    @php
        $bungaGross = ($data['nominal_awal'] * ($data['bunga']/100) * ($data['jangka_waktu']/12));
        $mappedData = [
            'nama_anggota' => $data['nama_anggota'],
            'no_anggota' => '12345',
            'no_deposito' => $data['no_deposito'],
            'jangka_waktu' => $data['jangka_waktu'],
            'bunga' => $data['bunga'],
            'tanggal' => $data['tanggal'],
            'jatuh_tempo' => $data['jatuh_tempo'],
            'status' => 'ACTIVE',
            'nominal_awal' => $data['nominal_awal'],
            'bunga_gross' => $bungaGross,
            'nominal_akhir' => $data['nominal_awal'] + $bungaGross
        ];
    @endphp

    @include('admin.settings.partials.components.deposito-body', ['settings' => $settings, 'data' => $mappedData, 'isPdf' => false])
</div>
